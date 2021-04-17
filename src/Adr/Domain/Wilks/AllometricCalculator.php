<?php

/**
 * @see       https://github.com/gordon-ansell/greenfedora-wtcalcs for the canonical source repository.
 * @copyright https://github.com/gordon-ansell/greenfedora-wtcalcs/blob/master/COPYRIGHT.md
 * @license   https://github.com/gordon-ansell/greenfedora-wtcalcs/blob/master/LICENSE.md New BSD License.
 */

declare(strict_types=1);
namespace WTCalcs\Adr\Domain\Wilks;

use GreenFedora\Adr\Domain\AbstractModel;
use GreenFedora\Adr\Domain\ModelInterface;

use WTCalcs\Adr\Domain\Wilks\AbstractCalculator;
use WTCalcs\Adr\Domain\Wilks\WilksResult;

/**
 * The allometric calculator.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class AllometricCalculator extends AbstractCalculator implements ModelInterface
{
    /**
     * Calculate the allometric squat result.
     *
     * @param   float   $weight     Weight lifted.(KG)
     * @param   float   $bodyWeight Body weight.(KG)
     *
     * @return  WilksResult         Result.
     */
    public function squat(float $weight, float $bodyWeight): WilksResult
    {
        $smult = $bodyWeight ** -0.60;
        $sresult = $weight * $smult;

        return new WilksResult('Squat', $sresult, $smult);
    }

    /**
     * Calculate the allometric bench result.
     *
     * @param   float   $weight     Weight lifted.(KG)
     * @param   float   $bodyWeight Body weight.(KG)
     *
     * @return  WilksResult         Result.
     */
    public function bench(float $weight, float $bodyWeight): WilksResult
    {
        $bmult = $bodyWeight ** -0.57;
        $bresult = $weight * $bmult;

        return new WilksResult('Bench', $bresult, $bmult);
    }

    /**
     * Calculate the allometric deadlift result.
     *
     * @param   float   $weight     Weight lifted.(KG)
     * @param   float   $bodyWeight Body weight.(KG)
     *
     * @return  WilksResult         Result.
     */
    public function dead(float $weight, float $bodyWeight): WilksResult
    {
        $bmult = $bodyWeight ** -0.46;
        $bresult = $weight * $bmult;

        return new WilksResult('Dead', $bresult, $bmult);
    }

    /**
     * Calculate the allometric total result.
     *
     * @param   float   $weight     Weight lifted.(KG)
     * @param   float   $bodyWeight Body weight.(KG)
     *
     * @return  WilksResult         Result.
     */
    public function total(float $weight, float $bodyWeight): WilksResult
    {
        $bmult = ($bodyWeight) ** -0.54;
        $bresult = ($weight/3) * $bmult;

        return new WilksResult('Total', $bresult, $bmult);
    }

    /**
     * Calculate the age-adjusted allometric squat result.
     *
     * @param   float   $weight     Weight lifted.(KG)
     * @param   float   $bodyWeight Body weight.(KG)
     * @param   int     $age        Age.    
     *
     * @return  WilksResult         Result.
     */
    public function squatAge(float $weight, float $bodyWeight, int $age): WilksResult
    {
        $raw = $this->squat($weight, $bodyWeight)->value;
        $result = $this->applyAge($raw, $age);

        return new WilksResult('Squat/Age', $result[0], $result[1]);
    }

    /**
     * Calculate the age-adjusted allometric bench result.
     *
     * @param   float   $weight     Weight lifted.(KG)
     * @param   float   $bodyWeight Body weight.(KG)
     * @param   int     $age        Age.    
     *
     * @return  WilksResult         Result.
     */
    public function benchAge(float $weight, float $bodyWeight, int $age): WilksResult
    {
        $raw = $this->bench($weight, $bodyWeight)->value;
        $result = $this->applyAge($raw, $age);

        return new WilksResult('Bench/Age', $result[0], $result[1]);
    }

    /**
     * Calculate the age-adjusted allometric deadlift result.
     *
     * @param   float   $weight     Weight lifted.(KG)
     * @param   float   $bodyWeight Body weight.(KG)
     * @param   int     $age        Age.    
     *
     * @return  WilksResult         Result.
     */
    public function deadAge(float $weight, float $bodyWeight, int $age): WilksResult
    {
        $raw = $this->dead($weight, $bodyWeight)->value;
        $result = $this->applyAge($raw, $age);

        return new WilksResult('Dead/Age', $result[0], $result[1]);
    }

    /**
     * Calculate the age-adjusted allometric total result.
     *
     * @param   float   $weight     Weight lifted.(KG)
     * @param   float   $bodyWeight Body weight.(KG)
     * @param   int     $age        Age.    
     *
     * @return  WilksResult         Result.
     */
    public function totalAge(float $weight, float $bodyWeight, int $age): WilksResult
    {
        $raw = $this->total($weight, $bodyWeight)->value;
        $result = $this->applyAge($raw, $age);

        return new WilksResult('Total/Age', $result[0], $result[1]);
    }
}
