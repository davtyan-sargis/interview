<?php
namespace Bidpath\Tt3UnitTesting;

class Result
{
    public const OK_BY_USER = 1;
    public const OK_BY_AUCTION_USER = 2;
    public const OK_BY_DATE = 3;
    public const OK_BY_FILE = 4;
    public const OK_BY_RAND = 5;
    public const ERR_USER_ABSENT = 11;
    public const ERR_FILE_ABSENT = 12;
    public const ERR_FILE_AMOUNT_INVALID = 13;
    public const ERR_FILE_AMOUNT_NEGATIVE = 14;
    public const ERR_AMOUNT_NOT_FOUND = 15;

    private const MESSAGES = [
        self::OK_BY_USER => 'By user amount',
        self::OK_BY_AUCTION_USER => 'By auction and user amount',
        self::OK_BY_DATE => 'By date amount',
        self::OK_BY_FILE => 'By file amount',
        self::OK_BY_RAND => 'By random rate',
        self::ERR_USER_ABSENT => 'User not found',
        self::ERR_FILE_ABSENT => 'File absent',
        self::ERR_FILE_AMOUNT_INVALID => 'File amount invalid',
        self::ERR_FILE_AMOUNT_NEGATIVE => 'File amount negative',
        self::ERR_AMOUNT_NOT_FOUND => 'Amount cannot be calculated',
    ];

    public ?float $amount = null;
    public ?int $status = null;

    public function __construct(?float $amount, int $status)
    {
        $this->amount = $amount;
        $this->status = $status;
    }

    public static function failed(int $errorStatus): self
    {
        return new static(null, $errorStatus);
    }

    public function message(): string
    {
        if ($this->amount === null) {
            return sprintf('%d) Failed: %s', $this->status, self::MESSAGES[$this->status]);
        }
        return sprintf('%d) Success: %s: %f', $this->status, self::MESSAGES[$this->status], $this->amount);
    }
}
