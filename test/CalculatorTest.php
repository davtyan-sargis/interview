<?php
namespace Bidpath\Tt3UnitTesting;

use PHPUnit\Framework\TestCase;
use Bidpath\Tt3UnitTesting\Internal\Auction\AuctionAmountService;
use Bidpath\Tt3UnitTesting\Internal\User\UserAmountService;
use Bidpath\Tt3UnitTesting\Internal\DataProvider;
use Bidpath\Tt3UnitTesting\Calculator;
use \DateTime;

class CalculatorTest extends TestCase
{
    protected $DataProvider;

    protected function setUp(): void {
        $this->DataProvider = $this->createStub(DataProvider::class);
    } 

    /**
     * @test
     * @dataProvider dpForAuctionAmount
     */
    public function calculateAuctionAmount(array $data, string $expected): void
    {
        $calculator = new Calculator($this->DataProvider);
        $userId = $data[0];
        $auctionId = $data[1];
        
        $this->DataProvider->setUserId($userId);
        $this->DataProvider->setAuctionId($auctionId);

        $this->DataProvider->method('auctionAmount')->willReturn((float)55);
        $auctionAmount = $this->DataProvider->auctionAmount();
        $result = $calculator->calculateAuctionAmount($auctionAmount);

        if ($result) {
            $this->assertEquals(
                $expected,
                $result->message()
            );
        } else {
            $this->assertNull(
                $result,
                'AuctionAmount not found'
            );
        }
    }

    /**
     * @test
     * @dataProvider dpForUserAmount
     */
    public function calculateUserAmount(array $data, string $expected): void
    {
        $calculator = new Calculator($this->DataProvider);

        $this->DataProvider->setUserId($data[0]);

        if ($data[0] < 1) {
            $this->DataProvider->method('UserAmountServiceExists')->willReturn(false);
            $this->DataProvider->method('UserAmountLoadAmount')->willReturn(null);
        } else {
            $this->DataProvider->method('UserAmountServiceExists')->willReturn(true);
            $this->DataProvider->method('UserAmountLoadAmount')->willReturn(25.000000);
        }

        $userExists = $this->DataProvider->UserAmountServiceExists();
        $userAmount = $this->DataProvider->UserAmountLoadAmount($userExists);
        $result = $calculator->calculateUserAmount($userExists, $userAmount);

        if ($result) {
            $this->assertEquals(
                $expected,
                $result->message()
            );
        } else {
            $this->assertNull(
                $result,
                'UserAmount not found'
            );
        }
    }

    /**
     * @test
     * @dataProvider dpForDateAmount
     */
    public function calculateDateAmount(array $data, string $expected): void
    {
        $calculator = new Calculator($this->DataProvider);

        $this->DataProvider->dateAmount = $data[1];
        $dateAmount = $calculator->getDateAmount();
        $dateNow = $calculator->getDateTime();

        $this->DataProvider->setDateAt($data[0]);
        $this->DataProvider->method('getDateTime')->willReturn(new DateTime('2022-03-01'));
        $result = $calculator->calculateDateAmount($dateAmount, $dateNow, $this->DataProvider->getDateAt());
        
        if ($result) {
            $this->assertEquals(
                $expected,
                $result->message()
            );
        } else {
            $this->assertNull(
                $result,
                'DateAmount not found'
            );
        }
    }

    /**
     * @test
     * @dataProvider dpForFileAmount
     */
    public function calculateFileAmount(array $data, string $expected): void
    {
        $calculator = new Calculator($this->DataProvider);

        switch ($data[0]) {
            case './var/file_negative_amount.txt':
                $this->DataProvider->method('fileExists')->willReturn(true);
                $this->DataProvider->method('getFileAmount')->willReturn(-1);
                break;
            case 'not_exists.txt':
                $this->DataProvider->method('fileExists')->willReturn(false);
                $this->DataProvider->method('getFileAmount')->willReturn(null);
                break;
            case './var/file_invalid_amount.txt':
                $this->DataProvider->method('fileExists')->willReturn(true);
                $this->DataProvider->method('getFileAmount')->willReturn('abc');
                break;
            case './var/file_amount.txt':
                $this->DataProvider->method('fileExists')->willReturn(true);
                $this->DataProvider->method('getFileAmount')->willReturn(125);
                break;
            default:
                $this->DataProvider->method('fileExists')->willReturn(false);
                $this->DataProvider->method('getFileAmount')->willReturn(null);
                break;
        }

        $fileExists = $this->DataProvider->fileExists();
        $fileAmount = $this->DataProvider->getFileAmount($fileExists);
        $result = $calculator->calculateFileAmount($fileExists, $fileAmount);
        
        if ($result) {
            $this->assertEquals(
                $expected,
                $result->message()
            );
        } else {
            $this->assertNull(
                $result,
                'FileAmount not found'
            );
        }
    }

    /**
     * @test
     * @dataProvider dpForRandAmount
     */
    public function calculateRandAmount(array $data, string $expected): void
    {
        $calculator = new Calculator($this->DataProvider);

        $this->DataProvider->randRate = $data[0];
        $this->DataProvider->method('getRandAmount')->willReturn(30);

        $randAmount = $calculator->getRandAmount();
        $randRate = $calculator->getRandRate();
        $result = $calculator->calculateRandAmount($randAmount, $randRate);
        
        if ($result) {
             $this->assertEquals(
                $expected,
                $result->message()
            );
        } else {
            $this->assertNull(
                $result,
                'RandAmount not found'
            );
        }
    }

    public function dpForAuctionAmount(): array
    {
        return [
            [[2,3], '2) Success: By auction and user amount: 55.000000']
        ];
    }

    public function dpForUserAmount(): array
    {
        return [
            [[-4], '11) Failed: User not found'],
            [[2], '1) Success: By user amount: 25.000000']
        ];
    }

    public function dpForDateAmount(): array
    {
        $dateAt = new DateTime('2022-03-01');
        $dateAtBefore = new DateTime('2022-03-01');
        $dateAtBefore->modify('-3 month');
        $dateAtAfter = new DateTime('2022-03-01');
        $dateAtAfter->modify('+3 month');

        return [
            [[$dateAt, 5], ''],
            [[$dateAtBefore, 5], '3) Success: By date amount: 5.000000'],
            [[$dateAtAfter, 5], '']
        ];
    }

    public function dpForFileAmount(): array
    {
        return [
            [['./var/file_amount.txt'], '4) Success: By file amount: 125.000000'],
            [['./var/file_invalid_amount.txt'], '13) Failed: File amount invalid'],
            [['./var/file_negative_amount.txt'], '14) Failed: File amount negative'],
            [['not_exists.txt'], '12) Failed: File absent']
        ];
    }

    public function dpForRandAmount(): array
    {
        return [
            [[0], ''],
            [[-1], '5) Success: By random rate: 30.000000'],
            [[1300], '']
        ];
    }
}
