<?php

/**
 * @see       https://github.com/gordon-ansell/greenfedora-wtcalcs for the canonical source repository.
 * @copyright https://github.com/gordon-ansell/greenfedora-wtcalcs/blob/master/COPYRIGHT.md
 * @license   https://github.com/gordon-ansell/greenfedora-wtcalcs/blob/master/LICENSE.md New BSD License.
 */

declare(strict_types=1);
namespace WTCalcs\Adr\Domain;

use GreenFedora\Adr\Domain\AbstractModel;
use GreenFedora\Adr\Domain\ModelInterface;

use WTCalcs\Adr\Domain\OnermResult;

/**
 * The 1-rep maximum calculator action.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class OnermCalcs extends AbstractModel implements ModelInterface
{

    /**
     * Round a number to the nearest multiplier.
     *
     * @param   float   $num   Number to round.
     * @param   float   $mult  Multiplier to round it to.
     *
     * @return  float          Rounded number.
     */
    protected function mround(float $num, float $mult): float 
    {
        $multiplier = 1 / $mult;
        return round($num * $multiplier) / $multiplier;
    }
    
    /**
     * Calculate the various one rep maximums.
     *
     * @param   float   $weight    Weight lifted.
     * @param   int     $reps      Reps performed.
     * @param   float   $rounding  Number to round to.
     * @param   array   $results   Where to add the results - array of Calc objects. (optional)
     *
     * @return  OnermResult        Average of the results.
     */
    public function onermcalcs(float $weight, int $reps, float $rounding, array &$results = null): OnermResult
    {
        if (null === $results) {
            $results = [];
        }
    
        // Epley.
        $tmp = new OnermResult('Epley', $weight * (1 + ($reps / 30)));
        array_push($results, $tmp);
    
        // Brzycki.
        $tmp = new OnermResult('Brzycki', $weight * (36 / (37 - $reps)));
        array_push($results, $tmp);
    
        // McGlothin.
        $tmp = new OnermResult('McGlothin', (100 * $weight) / (101.3 - (2.67123 * $reps)));
        array_push($results, $tmp);
    
        // Lombardi.
        $tmp = new OnermResult('Lombardi', $weight * ($reps ** 0.1));
        array_push($results, $tmp);
    
        // Mayhew.
        $tmp = new OnermResult('Mayhew', (100 * $weight) / (52.2 + 41.9 * (M_E ** (-0.055 * $reps))));
        array_push($results, $tmp);
    
        // Wathan.
        $tmp = new OnermResult('Wathan', ($weight * 100) / (48.8 + 53.8 * (M_E ** (-0.075 * $reps))));
        array_push($results, $tmp);
    
        // O'Conner.
        $tmp = new OnermResult("O'Conner", $weight * (1 + ($reps / 40)));
        array_push($results, $tmp);
    
        // Total.
        $tot = 0;
        foreach($results as $item) {
            $item->rounded = $this->mround($item->value, $rounding);
            $tot += $item->value;
        }
        $avg = new OnermResult();
        $avg->value = $tot / sizeof($results);
        $avg->rounded = $this->mround($avg->value, $rounding);
    
        return $avg;
    }
}
