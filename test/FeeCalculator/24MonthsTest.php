<?php 

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PragmaGoTech\Interview\Model\LoanProposal;
use PragmaGoTech\Interview\FeeCalculator\FeeCalculatorFactory;

final class FeeCalcuator24MonthsTest extends TestCase
{
    /**
     * Test for exact loan amount.
     */
    public function testExactAmount(): void 
    {
        $app1 = new LoanProposal(24, 3000);
        $calc = FeeCalculatorFactory::factory($app1);
        $this->assertSame(floatval(120), $calc->calculate($app1));
        $app2 = new LoanProposal(24, 10000);
        $calc = FeeCalculatorFactory::factory($app2);
        $this->assertSame(floatval(400), $calc->calculate($app2));
    }

    /**
     * Test for interpolated values.
     */
    public function testInterpolatedAmount(): void
    {
        $app1 = new LoanProposal(24, 7500);
        $calc = FeeCalculatorFactory::factory($app1);
        $this->assertSame(floatval(300), $calc->calculate($app1));
        $app2 = new LoanProposal(24, 2500);
        $calc = FeeCalculatorFactory::factory($app2);
        $this->assertSame(floatval(110), $calc->calculate($app2));
        $app3 = new LoanProposal(24, 1500);
        $calc = FeeCalculatorFactory::factory($app3);
        $this->assertSame(floatval(85), $calc->calculate($app3));
    }

    /**
     * Test for values that require rounding.
     */
    public function testRoundedAmount(): void
    {
        $app1 = new LoanProposal(24, 10100);
        $calc = FeeCalculatorFactory::factory($app1);
        $this->assertSame(floatval(405), $calc->calculate($app1));
        $app2 = new LoanProposal(24, floatval(8000.1));
        $calc = FeeCalculatorFactory::factory($app2);
        $this->assertSame(floatval(324.9), $calc->calculate($app2));
    }
}