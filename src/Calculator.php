<?php
namespace Bidpath\Tt3UnitTesting;

use Bidpath\Tt3UnitTesting\Internal\DataProvider;
use Bidpath\Tt3UnitTesting\Result;
use DateTime;

class Calculator
{
    public DataProvider $dataProvider;

    public function __construct(?DataProvider $dataProvider = null) {
        $this->dataProvider = $dataProvider ?? new DataProvider();
    }

    public function calculateAuctionAmount(?float $auctionAmount = null) 
    {
        if ($auctionAmount) {
            return new Result($auctionAmount, Result::OK_BY_AUCTION_USER);
        }

        return null;
    }

    public function calculateUserAmount(?bool $exists = null, ?int $userAmount = null)
    {
        if (!$exists) {
            return Result::failed(Result::ERR_USER_ABSENT);
        }

        if ($userAmount) {
            return new Result($userAmount, Result::OK_BY_USER);
        }

        return null;
    }

    public function getDateAmount()
    {
        return $this->dataProvider->dateAmount;
    }

    public function getDateTime()
    {
        return $this->dataProvider->getDateTime();
    }

    public function calculateDateAmount(int $dateAmount, DateTime $dateNow, ?DateTime $dateAt = null)
    {
        if (
            $dateAt
            && $dateAt < $dateNow
        ) {
            return new Result($dateAmount, Result::OK_BY_DATE);
        }

        return null;
    }

    public function calculateFileAmount(bool $exists = false, ?string $fileAmount = null)
    {
        if (!$exists) {
            return Result::failed(Result::ERR_FILE_ABSENT);
        }

        if (!preg_match ("/^-?[0-9\.]+$/", $fileAmount)) {
            return Result::failed(Result::ERR_FILE_AMOUNT_INVALID);
        }

        $fileAmount = (float)$fileAmount;
        if ($fileAmount < 0) {
            return Result::failed(Result::ERR_FILE_AMOUNT_NEGATIVE);
        }

        return new Result($fileAmount, Result::OK_BY_FILE);

        return null;
    }

    public function getRandAmount()
    {
        return $this->dataProvider->getRandAmount();
    }

    public function getRandRate()
    {
        return $this->dataProvider->randRate;
    }

    public function calculateRandAmount(int $randAmount, int $randRate)
    {
        if ($randRate) {
            if ($randAmount >= $randRate) {
                return new Result($randAmount, Result::OK_BY_RAND);
            }
        }

        return null;
    }

    public function calculate(): Result {

        $auctionAmount = $this->dataProvider->auctionAmount();
        $result = $this->calculateAuctionAmount($auctionAmount);
        
        if ($result instanceof Result) {
            return $result;
        }

        $userExists = $this->dataProvider->UserAmountServiceExists();
        $userAmount = $this->dataProvider->UserAmountLoadAmount($userExists);
        $result = $this->calculateUserAmount($userExists, $userAmount);

        if ($result instanceof Result) {
            return $result;
        }

        $dateAmount = $this->getDateAmount();
        $dateNow = $this->getDateTime();
        $result = $this->calculateDateAmount($dateAmount, $dateNow, $this->DataProvider->getDateAt());

        if ($result instanceof Result) {
            return $result;
        }

        $fileExists = $this->dataProvider->fileExists();
        $fileAmount = $this->dataProvider->getFileAmount($fileExists);
        $result = $this->calculateFileAmount($fileExists, $fileAmount);

        if ($result instanceof Result) {
            return $result;
        }

        $randAmount = $this->getRandAmount();
        $randRate = $this->getRandRate();
        $result = $this->calculateRandAmount($randAmount, $randRate);

        if ($result instanceof Result) {
            return $result;
        }

        return Result::failed(Result::ERR_AMOUNT_NOT_FOUND);
    }
}
