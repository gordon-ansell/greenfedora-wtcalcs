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

class PercentCalcs
{
    /**
     * Weight.
     * @var float
     */    
    protected $weight = null;

    /**
     * Rounding.
     * @var float
     */
    protected $rounding = null;

    /**
     * Constructor.
     *
     * @param   float   $weight    Weight lifted.
     * @param   float   $rounding  Number to round to.
     *
     * @return  void
     */
    public function __construct(float $weight, float $rounding)
    {
        $this->weight = $weight;
        $this->rounding = $rounding;
    }

    /**
     * Invoke it.
     *
     * @return  array   Results of calculations.
     */
    public function __invoke()
    {
        $results = [];

        for ($i = 100; $i >= 5; $i = $i - 5) {
            if (100 === $i) {
                $tmp = new OnermResult("100%", $this->weight);
                array_push($results, $tmp);
            } else {
                $tmp = new OnermResult(strval($i) . '%', ($this->weight / 100) * $i);
                array_push($results, $tmp);
            }
        }

        foreach($results as $item) {
            $item->rounded = Maths::mround($item->value, $this->rounding);
        }

        return $results;
    }
}
