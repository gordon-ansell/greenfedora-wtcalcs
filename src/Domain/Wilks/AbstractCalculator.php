<?php

/**
 * @see       https://github.com/gordon-ansell/greenfedora-wtcalcs for the canonical source repository.
 * @copyright https://github.com/gordon-ansell/greenfedora-wtcalcs/blob/master/COPYRIGHT.md
 * @license   https://github.com/gordon-ansell/greenfedora-wtcalcs/blob/master/LICENSE.md New BSD License.
 */

declare(strict_types=1);
namespace WTCalcs\Domain\Wilks;

/**
 * The abstract calculator.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

abstract class AbstractCalculator
{
    const WOLD = "41|1.01, 42|1.02, 43|1.031, 44|1.043, 45|1.055, 46|1.068, 47|1.082, 48|1.097, 49|1.113, 50|1.130,
        51|1.147, 52|1.165, 53|1.184, 54|1.204, 55|1.225, 56|1.246, 57|1.268, 58|1.291, 59|1.315, 60|1.340,
        61|1.366, 62|1.393, 63|1.421, 64|1.450, 65|1.480, 66|1.511, 67|1.543, 68|1.576, 69|1.610, 70|1.645,
        71|1.681, 72|1.718, 73|1.756, 74|1.795, 75|1.835, 76|1.876, 77|1.918, 78|1.961, 79|2.005, 80|2.050,
        81|2.096, 82|2.143, 83|2.190, 84|2.238, 85|2.287, 86|2.337, 87|2.388, 88|2.440, 89|2.494, 90|2.549";

    const WYOUNG = "14|1.23, 15|1.18, 16|1.13, 17|1.08, 18|1.06, 19|1.04, 20|1.03, 21|1.02, 22|1.01";

    /**
     * Age factors.
     * @var array
     */
    protected $ageFactor = [];

    /**
     * Constructor.
     * 
     * @return void
     */
    public function __construct()
    {
        // Parse out the age multipliers.
        $wysp = explode(',', self::WYOUNG);
        $wosp = explode(',', self::WOLD);

        foreach ($wysp as $item) {
            $tmp = explode('|', trim($item));
            $this->ageFactor[$tmp[0]] = $tmp[1];
        }

        for ($i = 23; $i < 41; $i++) {
            $this->ageFactor[$i] = 1;
        }

        foreach ($wosp as $item) {
            $tmp = explode('|', trim($item));
            $this->ageFactor[$tmp[0]] = $tmp[1];
        }
    }

    /**
     * Apply an age allowance.
     *
     * @param   float   $source     Source metric.
     * @param   int     $age        Age.   
     *
     * @return  array               Result, mult.
     */
    public function applyAge(float $source, int $age): array
    {
        $ageFactor = $this->ageFactor[$age];

        return array($source * $ageFactor, $ageFactor);
    }
}
