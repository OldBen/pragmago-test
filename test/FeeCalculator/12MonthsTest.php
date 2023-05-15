<?php 

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PragmaGoTech\Interview\Model\LoanProposal;
use PragmaGoTech\Interview\FeeCalculator\FeeCalculatorFactory;

final class FeeCalcuator12MonthsTest extends TestCase
{
    /**
     * Test for exact loan amount.
     */
    public function testExactAmount(): void 
    {
        $app1 = new LoanProposal(12, 3000);
        $calc = FeeCalculatorFactory::factory($app1);
        $this->assertSame(floatval(90), $calc->calculate($app1));
        $app2 = new LoanProposal(12, 10000);
        $calc = FeeCalculatorFactory::factory($app2);
        $this->assertSame(floatval(200), $calc->calculate($app2));
    }

    /**
     * Test for interpolated values.
     */
    public function testInterpolatedAmount(): void
    {
        $app1 = new LoanProposal(12, 7500);
        $calc = FeeCalculatorFactory::factory($app1);
        $this->assertSame(floatval(150), $calc->calculate($app1));
        $app2 = new LoanProposal(12, 2500);
        $calc = FeeCalculatorFactory::factory($app2);
        $this->assertSame(floatval(90), $calc->calculate($app2));
        $app3 = new LoanProposal(12, 4500);
        $calc = FeeCalculatorFactory::factory($app3);
        $this->assertSame(floatval(110), $calc->calculate($app3));
    }

    /**
     * Test for values that require rounding.
     */
    public function testRoundedAmount(): void
    {
        $app1 = new LoanProposal(12, 10100);
        $calc = FeeCalculatorFactory::factory($app1);
        $this->assertSame(floatval(205), $calc->calculate($app1));
        $app2 = new LoanProposal(12, floatval(8000.1));
        $calc = FeeCalculatorFactory::factory($app2);
        $this->assertSame(floatval(164.9), $calc->calculate($app2));
    }
}