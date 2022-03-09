<?php

include_once "./vendor/autoload.php";

use Bidpath\Tt3UnitTesting\Calculator;

$result = (new Calculator())->calculate(3);
echo $result->message() . PHP_EOL;

$result = (new Calculator())->calculate(3, 2);
echo $result->message() . PHP_EOL;

$result = (new Calculator(45))->calculate(null, null, new DateTime("2022-01-01"));
echo $result->message() . PHP_EOL;

$result = (new Calculator())->calculate(null, null, null, __DIR__ . "/var/file_amount.txt");
echo $result->message() . PHP_EOL;

$calculator = new Calculator(45, 10);
$result = $calculator->calculate();
echo $result->message() . PHP_EOL;

$result = (new Calculator())->calculate(1);
echo $result->message() . PHP_EOL;

$result = (new Calculator())->calculate(null, null, null, __DIR__ . "/var/file_absent.txt");
echo $result->message() . PHP_EOL;

$result = (new Calculator())->calculate(null, null, null, __DIR__ . "/var/file_invalid_amount.txt");
echo $result->message() . PHP_EOL;

$result = (new Calculator())->calculate(null, null, null, __DIR__ . "/var/file_negative_amount.txt");
echo $result->message() . PHP_EOL;

$result = (new Calculator())->calculate();
echo $result->message() . PHP_EOL;
