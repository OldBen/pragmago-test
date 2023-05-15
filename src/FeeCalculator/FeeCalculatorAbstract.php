<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\FeeCalculator;

use PragmaGoTech\Interview\FeeCalculator;
use PragmaGoTech\Interview\Model\LoanProposal;

abstract class FeeCalculatorAbstract implements FeeCalculator
{
    /**
     * Fee structure for particular term
     * @var Array
     */
    protected $structure = [];

    /**
     * @return float The calculated total fee.
     */
    public function calculate(LoanProposal $application): float
    {
        $initial_fee = $this->interpolate($application->amount());
        return $this->roundFee($application->amount(), $initial_fee);
    }

    /**
     * Calculate the fee by finding nearest breakpoints in fee structure and interpolating value between them.
     * @return float Calculated fee.
     */
    private function interpolate(float $amount): float 
    {
        if (array_key_exists($amount, $this->structure)) return $this->structure[$amount];
        reset($this->structure);
        while (current($this->structure) !== null)
        {
            $lower_bound = key($this->structure);
            $lower_fee = current($this->structure);
            if(next($this->structure) === false) throw new \Exception("Loan amount outside expected range");
            $upper_bound = key($this->structure);
            $upper_fee = current($this->structure);

            if ($lower_bound <= $amount && $amount < $upper_bound) {
                $bound_diff = $upper_bound - $lower_bound;
                $fee_diff = $upper_fee - $lower_fee;
                $x = ($amount - $lower_bound) / $bound_diff;
                return round($lower_fee + $fee_diff * $x, 2);
            }
        }
        throw new \Exception("Loan amount outside expected range");
    }

    /**
     * Round up the loan amount and fee to the nearest multiple of 5 PLN.
     * @return float Adjusted fee.
     */
    private function roundFee(float $amount, float $fee): float
    {
        $rem = round(fmod($amount + $fee, 5), 2);
        return floatval($rem === 0.0 ? $fee : $fee + 5 - $rem);

    }

}