<?php

/**
 * @see       https://github.com/gordon-ansell/greenfedora-wtcalcs for the canonical source repository.
 * @copyright https://github.com/gordon-ansell/greenfedora-wtcalcs/blob/master/COPYRIGHT.md
 * @license   https://github.com/gordon-ansell/greenfedora-wtcalcs/blob/master/LICENSE.md New BSD License.
 */

declare(strict_types=1);
namespace WTCalcs\Domain\Onerm;

use GreenFedora\Maths\Maths;

use WTCalcs\Domain\Onerm\OnermResult;

/**
 * The 1-rep maximum calculator action.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class OnermCalcs
{    
    /**
     * Weight.
     * @var float
     */    
    protected $weight = null;

    /**
     * Reps.
     * @var int
     */    
    protected $reps = null;

    /**
     * Rounding.
     * @var float
     */
    protected $rounding = null;

    /**
     * Constructor.
     *
     * @param   float   $weight    Weight lifted.
     * @param   float   $reps      Reps performed.
     * @param   float   $rounding  Number to round to.
     *
     * @return  void
     */
    public function __construct(float $weight, int $reps, float $rounding)
    {
        $this->weight = $weight;
        $this->reps = $reps;
        $this->rounding = $rounding;
    }

    /**
     * Calculate the various one rep maximums.
     *
     * @return  array
     */
    public function __invoke()
    {
        $results = [];

        if (1 === $this->reps) {

            foreach(['Epley', 'Brzycki', 'McGlothin', 'Lombardi', 'Mayhew', 'Wathan', "O'Conner"] as $item) {
                $tmp = new OnermResult($item, $this->weight);
                array_push($results, $tmp);
            }

        } else {
    
            // Epley.
            $tmp = new OnermResult('Epley', $this->weight * (1 + ($this->reps / 30)));
            array_push($results, $tmp);
        
            // Brzycki.
            $tmp = new OnermResult('Brzycki', $this->weight * (36 / (37 - $this->reps)));
            array_push($results, $tmp);
        
            // McGlothin.
            $tmp = new OnermResult('McGlothin', (100 * $this->weight) / (101.3 - (2.67123 * $this->reps)));
            array_push($results, $tmp);
        
            // Lombardi.
            $tmp = new OnermResult('Lombardi', $this->weight * ($this->reps ** 0.1));
            array_push($results, $tmp);
        
            // Mayhew.
            $tmp = new OnermResult('Mayhew', (100 * $this->weight) / (52.2 + 41.9 * (M_E ** (-0.055 * $this->reps))));
            array_push($results, $tmp);
        
            // Wathan.
            $tmp = new OnermResult('Wathan', ($this->weight * 100) / (48.8 + 53.8 * (M_E ** (-0.075 * $this->reps))));
            array_push($results, $tmp);
        
            // O'Conner.
            $tmp = new OnermResult("O'Conner", $this->weight * (1 + ($this->reps / 40)));
            array_push($results, $tmp);
        }
    
        // Total.
        $tot = 0;
        foreach($results as $item) {
            $item->rounded = Maths::mround($item->value, $this->rounding);
            $tot += $item->value;
        }

        $avg = new OnermResult();
        $avg->name = 'Average';
        $avg->value = $tot / sizeof($results);
        $avg->rounded = Maths::mround($avg->value, $this->rounding);
    
        return array($results, $avg);
    }
}
