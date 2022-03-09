<?php
namespace Bidpath\Tt3UnitTesting\Internal\Auction;

class AuctionAmountService
{
    private const AUCTION_USER_AMOUNT = 35;

    public function loadAmount(int $auctionId, int $userId): ?float
    {
        // This is mock for method that loads data from DB in real app
        return static::AUCTION_USER_AMOUNT;
    }
}
