<?php

/**
 * @see       https://github.com/gordon-ansell/greenfedora-wtcalcs for the canonical source repository.
 * @copyright https://github.com/gordon-ansell/greenfedora-wtcalcs/blob/master/COPYRIGHT.md
 * @license   https://github.com/gordon-ansell/greenfedora-wtcalcs/blob/master/LICENSE.md New BSD License.
 */

declare(strict_types=1);
namespace WTCalcs\Domain\Wilks;

use WTCalcs\Domain\Wilks\AbstractCalculator;
use WTCalcs\Domain\Wilks\WilksResult;

/**
 * The SIFF calculator.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class SiffCalculator extends AbstractCalculator
{
    /**
     * Calculation values.
     * @var array
     */
    protected $calc = array(
        'squat'     => array(638.01, 9517.7, -0.7911),
        'bench'     => array(408.15, 11047, -0.9371),
        'dead'      => array(433.14, 493825, -1.9712),
        'total'     => array(1270.4, 172970, -1.3925)
    );

    /**
     * Do the calculation.
     *
     * @param   string  $type       Type.
     * @param   float   $weight     Weight lifted.(KG)
     * @param   float   $bodyWeight Body weight.(KG)
     * @param   int     $age        Age.    
     *
     * @return  WilksResult         Result.
     */
    public function doCalc(string $type, float $weight, float $bodyWeight, ?int $age = null): WilksResult
    {
        $f = $this->calc[$type];

        $mult = $f[0] - $f[1] * $bodyWeight ** $f[2];
        $result = ($weight /$mult) * 100;

        if (is_null($age)) {
            return new WilksResult(ucfirst($type), $result, $mult);
        } else {
            $r = $this->applyAge($result, $age);
            return new WilksResult(ucfirst($type) . '/Age', $r[0], $r[1]);    
        }
    }

    /**
     * Calculate the SIFF squat result.
     *
     * @param   float   $weight     Weight lifted.(KG)
     * @param   float   $bodyWeight Body weight.(KG)
     * @param   int     $age        Age.    
     *
     * @return  WilksResult         Result.
     */
    public function squat(float $weight, float $bodyWeight, ?int $age = null): WilksResult
    {
        return $this->doCalc('squat', $weight, $bodyWeight, $age);
    }

    /**
     * Calculate the SIFF bench result.
     *
     * @param   float   $weight     Weight lifted.(KG)
     * @param   float   $bodyWeight Body weight.(KG)
     * @param   int     $age        Age.    
     *
     * @return  WilksResult         Result.
     */
    public function bench(float $weight, float $bodyWeight, ?int $age = null): WilksResult
    {
        return $this->doCalc('bench', $weight, $bodyWeight, $age);
    }

    /**
     * Calculate the SIFF deadlift result.
     *
     * @param   float   $weight     Weight lifted.(KG)
     * @param   float   $bodyWeight Body weight.(KG)
     * @param   int     $age        Age.    
     *
     * @return  WilksResult         Result.
     */
    public function dead(float $weight, float $bodyWeight, ?int $age = null): WilksResult
    {
        return $this->doCalc('dead', $weight, $bodyWeight, $age);
    }

    /**
     * Calculate the SIFF total result.
     *
     * @param   float   $weight     Weight lifted.(KG)
     * @param   float   $bodyWeight Body weight.(KG)
     * @param   int     $age        Age.    
     *
     * @return  WilksResult         Result.
     */
    public function total(float $weight, float $bodyWeight, ?int $age = null): WilksResult
    {
        return $this->doCalc('total', $weight, $bodyWeight, $age);
    }

}
