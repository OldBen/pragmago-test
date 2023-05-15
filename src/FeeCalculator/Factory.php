<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\FeeCalculator;

use PragmaGoTech\Interview\Model\LoanProposal;

class FeeCalculatorFactory
{
    private const TERM_12_MONTHS = 12;
    private const TERM_24_MONTHS = 24;

    public function factory(LoanProposal $application): FeeCalculatorAbstract
    {
        switch($application->term()) {
            case self::TERM_12_MONTHS:
                return new FeeCalculator12Months();
            case self::TERM_24_MONTHS:
                return new FeeCalculator24Months();
            default:
                throw new \Exception("Term not implemented");
        }
    }
}