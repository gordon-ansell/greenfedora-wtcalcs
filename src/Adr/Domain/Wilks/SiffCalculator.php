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
 * The SIFF calculator.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class SiffCalculator extends AbstractCalculator implements ModelInterface
{
    /**
     * Calculate the SIFF squat result.
     *
     * @param   float   $weight     Weight lifted.(KG)
     * @param   float   $bodyWeight Body weight.(KG)
     *
     * @return  WilksResult         Result.
     */
    public function squat(float $weight, float $bodyWeight): WilksResult
    {
        $smult = 638.01 - 9517.7 * $bodyWeight ** -0.7911;
        $sresult = ($weight /$smult) * 100;

        return new WilksResult('Squat', $sresult, $smult);
    }

    /**
     * Calculate the SIFF bench result.
     *
     * @param   float   $weight     Weight lifted.(KG)
     * @param   float   $bodyWeight Body weight.(KG)
     *
     * @return  WilksResult         Result.
     */
    public function bench(float $weight, float $bodyWeight): WilksResult
    {
        $smult = 408.15 - 11047 * $bodyWeight ** -0.9371;
        $sresult = ($weight /$smult) * 100;

        return new WilksResult('Bench', $sresult, $smult);
    }

    /**
     * Calculate the SIFF deadlift result.
     *
     * @param   float   $weight     Weight lifted.(KG)
     * @param   float   $bodyWeight Body weight.(KG)
     *
     * @return  WilksResult         Result.
     */
    public function dead(float $weight, float $bodyWeight): WilksResult
    {
        $smult = 433.14 - 493825 * $bodyWeight ** -1.9712;
        $sresult = ($weight /$smult) * 100;


        return new WilksResult('Dead', $sresult, $smult);
    }

    /**
     * Calculate the SIFF total result.
     *
     * @param   float   $weight     Weight lifted.(KG)
     * @param   float   $bodyWeight Body weight.(KG)
     *
     * @return  WilksResult         Result.
     */
    public function total(float $weight, float $bodyWeight): WilksResult
    {
        $smult = 1270.4 - 172970 * $bodyWeight ** -1.3925;
        $sresult = ($weight /$smult) * 100;

        return new WilksResult('Total', $sresult, $smult);
    }

    /**
     * Calculate the age-adjusted SIFF squat result.
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
     * Calculate the age-adjusted SIFF bench result.
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
     * Calculate the age-adjusted SIFF deadlift result.
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
     * Calculate the age-adjusted SIFF total result.
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
