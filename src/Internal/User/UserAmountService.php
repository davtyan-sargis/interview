<?php
namespace Bidpath\Tt3UnitTesting\Internal\User;

class UserAmountService
{
    private const USER_AMOUNT = 25;

    public function loadAmount(int $userId): ?float
    {
        // This is mock for method that loads data from DB in real app
        return static::USER_AMOUNT;
    }

    public function existUserById(int $userId): bool
    {
        // This is mock for method that checks user presence in DB by his ID
        return $userId > 1;
    }
}