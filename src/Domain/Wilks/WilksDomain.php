<?php

/**
 * @see       https://github.com/gordon-ansell/greenfedora-wtcalcs for the canonical source repository.
 * @copyright https://github.com/gordon-ansell/greenfedora-wtcalcs/blob/master/COPYRIGHT.md
 * @license   https://github.com/gordon-ansell/greenfedora-wtcalcs/blob/master/LICENSE.md New BSD License.
 */

declare(strict_types=1);
namespace WTCalcs\Domain\Wilks;

use GreenFedora\Measure\Weight;

use WTCalcs\Domain\Wilks\WilksCalculator;
use WTCalcs\Domain\Wilks\AllometricCalculator;
use WTCalcs\Domain\Wilks\SiffCalculator;

/**
 * Domain entry point.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */
class WilksDomain
{
    /**
     * Data.
     * @var array
     */
    protected $data = array(
        'weight'        =>  array(null, 'kg'),
        'bodyWeight'    =>  array(null, 'kg'),
        'squat'         =>  array(null, 'kg'),
        'bench'         =>  array(null, 'kg'),
        'dead'          =>  array(null, 'kg'),
        'age'           =>  null,
        'method'        =>  'all',
        'gender'        =>  'male',
    );

    /**
     * Constructor.
     * @param   array       $data       Data
     * @return  void
     */
    public function __construct(array $data)
    {
        foreach(array_keys($this->data) as $k) {
            if (array_key_exists($k, $data)) {
                if (in_array($k, ['method', 'gender'])) {
                    $this->data[$k] = $data[$k];
                } else if ('age' == $k) {
                    $this->data[$k] = intval($data[$k]);
                } else {
                    $this->data[$k] = new Weight($data[$k]);
                }
            }
        }

        if ('separate' == $this->data['method']) {
            $sum = $this->data['squat']->kg() + $this->data['bench']->kg() + $this->data['dead']->kg();
            $this->data['weight'] = new Weight($sum, 'kg');
        }
    }

    /**
     * Get the results.
     * 
     * @return  array
     */
    public function results()
    {

        $doAge = (isset($this->data['age']) and !is_null($this->data['age']) and $this->data['age'] > 13);
       
        // Wilks.
        $resultsWilks = [];
        $calculator = new WilksCalculator();
        
        if ('separate' == $this->data['method']) {
            foreach (['squat', 'bench', 'dead'] as $lift) {

                $resultsWilks[] = $calculator->wilks($this->data[$lift]->kg(), $this->data['bodyWeight']->kg(), 
                    $this->data['gender'], null, ucfirst($lift));

                if ($doAge) {
                    $resultsWilks[] = $calculator->wilks($this->data[$lift]->kg(), $this->data['bodyWeight']->kg(), 
                        $this->data['gender'], $this->data['age'], ucfirst($lift));
                }
            }
        }

        $resultsWilks[] = $calculator->wilks($this->data['weight']->kg(), $this->data['bodyWeight']->kg(), 
            $this->data['gender']);

        if ($doAge) {
            $resultsWilks[] = $calculator->wilks($this->data['weight']->kg(), $this->data['bodyWeight']->kg(), 
                $this->data['gender'], $this->data['age']);
        }

        // Allometric.
        $resultsAllometric = [];
        $allo = new AllometricCalculator();

        if ('separate' == $this->data['method']) {
            foreach (['squat', 'bench', 'dead'] as $lift) {

                $resultsAllometric[] = $allo->doCalc($lift, $this->data[$lift]->kg(), $this->data['bodyWeight']->kg());

                if ($doAge) {
                    $resultsAllometric[] = $allo->doCalc($lift, floatval($this->data[$lift]->kg()), $this->data['bodyWeight']->kg(), 
                        $this->data['age']);
                }
            }

        } 

        $resultsAllometric[] = $allo->total($this->data['weight']->kg(), $this->data['bodyWeight']->kg());
            
        if ($doAge) {
            $resultsAllometric[] = $allo->total($this->data['weight']->kg(), $this->data['bodyWeight']->kg(),
                $this->data['age']);
        }

        // SIFF.
        $resultsSiff = [];
        $siff = new SiffCalculator();

        if ('separate' == $this->data['method']) {
            foreach (['squat', 'bench', 'dead'] as $lift) {

                $resultsSiff[] = $siff->doCalc($lift, $this->data[$lift]->kg(), $this->data['bodyWeight']->kg());

                if ($doAge) {
                    $resultsSiff[] = $siff->doCalc($lift, floatval($this->data[$lift]->kg()), $this->data['bodyWeight']->kg(), 
                        $this->data['age']);
                }
            }

        } 

        $resultsSiff[] = $siff->total($this->data['weight']->kg(), $this->data['bodyWeight']->kg());
            
        if ($doAge) {
            $resultsSiff[] = $siff->total($this->data['weight']->kg(), $this->data['bodyWeight']->kg(),
                $this->data['age']);
        }

        return array($resultsWilks, $resultsAllometric, $resultsSiff);
    }
}
