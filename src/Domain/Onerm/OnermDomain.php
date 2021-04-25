<?php

/**
 * @see       https://github.com/gordon-ansell/greenfedora-wtcalcs for the canonical source repository.
 * @copyright https://github.com/gordon-ansell/greenfedora-wtcalcs/blob/master/COPYRIGHT.md
 * @license   https://github.com/gordon-ansell/greenfedora-wtcalcs/blob/master/LICENSE.md New BSD License.
 */

declare(strict_types=1);
namespace WTCalcs\Domain\Onerm;

use WTCalcs\Domain\Onerm\OnermCalcs;
use WTCalcs\Domain\Onerm\PercentCalcs;

/**
 * Domain entry point.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */
class OnermDomain
{
    /**
     * Get the results.
     * 
     * @param   float   $weight     Weight.
     * @param   int     $reps       Reps.
     * @param   float   $rounding   Rounding.
     * @return  array
     */
    public function results($weight, $reps, $rounding)
    {
        $calculator = new OnermCalcs(floatval($weight), intval($reps), floatval($rounding));
        list($onerms, $average) = $calculator();

        $calculator = new PercentCalcs(floatval($weight), floatval($rounding));
        $percents = $calculator();

        return array($onerms, $average, $percents);
    }
}
