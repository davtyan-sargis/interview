# Unit testing task

Below is fictional code sample that performs some decision-making logic. It calculates some amount according to inputs on the base of conditional logic. It produces `Result` object:

* either with calculated amount and success status code, in case of successfully completed calculation,
* or error status code, in case of failed calculation.

There are 2 artificial services (`UserAmountService`, `AuctionAmountService`) that should read data from external out-of-process resource in real application. Imagine they do that in this task.

Your task is write unit test for `Calculator` logic. You can refactor `Calculator` anyway you would like to do, so it will be possible to cover it with unit test. We don't expect functional/integration testing in this task.

Implement unit test in `test/CalculatorTest.php`. It should be based on PHPUnit framework and verify testing data-sets organized with help of `@dataProvider`.

Before start coding please think and tell:

* What kind of refactorings should be performed on `Calculator` class, so it could be unit tested in the desired way?
* What is cyclomatic complexity of the logic in SUT?
* List obligatory edge-cases we have to verify in the SUT (System Under Test).
* What equivalence classes could you distinguish there?

Call `composer test` to run unit test.

Call `composer example` for running example CLI entry-point for checking different scenarios.
