<?php
namespace Bidpath\Tt3UnitTesting\Internal;
use Bidpath\Tt3UnitTesting\Internal\Auction\AuctionAmountService;
use Bidpath\Tt3UnitTesting\Internal\User\UserAmountService;

class DataProvider
{
    public int $dateAmount = 0;
    public int $randRate = 0;
    private int $userId;
    private int $auctionId;
    private DateTime $dateAt;
    private string $filename;

    public function setUserId(?int $userId = null)
    {
        $this->userId = $userId;
    }

    public function setAuctionId(?int $auctionId = null)
    {
        $this->auctionId = $auctionId;
    }

    public function setDateAt(?\DateTime $dateAt = null)
    {
        $this->dateAt = $dateAt;
    }

    public function setFilename(?string $filename = null)
    {
        $this->filename = $filename;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getAuctionId()
    {
        return $this->auctionId;
    }

    public function getDateAt()
    {
        return $this->dateAt;
    }

    public function getFilename()
    {
        return $this->filename;
    }

    public function getAuctionAmountService(): AuctionAmountService {
        return new AuctionAmountService();
    }

    public function getUserAmountService(): UserAmountService {
        return new UserAmountService();
    }

    public function getDateTime(): \DateTime {
        return new \DateTime('now');
    }

    public function getRandAmount(): int {
        return random_int(1, 10) * 10;
    }

    public function fileExists()
    {
        if ($this->filename) {
            return file_exists($this->filename);
        }

        return null;
    }

    public function getFileAmount(bool $exists = false)
    {
        if ($exists) {
            return file_get_contents($this->filename);
        }

        return null;
    }

    public function auctionAmount()
    {
        if (
            $this->userId
            && $this->auctionId
        ) {
            return $this->getAuctionAmountService()->loadAmount($this->auctionId, $this->userId);
        }

        return null;
    }

    public static function getUserAmount(): UserAmountService
    {
        return $this->UserAmountService();
    }

    public function UserAmountServiceExists()
    {
        if (
            $this->userId
        ) {
            return self::getUserAmount()->existUserById($this->userId);
        }

        return null;
    }

    public function UserAmountLoadAmount(?bool $exists = null)
    {
        if (
            $this->userId
            && $exists
        ) {
            return self::getUserAmount()->loadAmount($this->userId);
        }

        return null;
    }
}
