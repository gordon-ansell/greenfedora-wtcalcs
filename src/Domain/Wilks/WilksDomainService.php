<?php

/**
 * @see       https://github.com/gordon-ansell/greenfedora-wtcalcs for the canonical source repository.
 * @copyright https://github.com/gordon-ansell/greenfedora-wtcalcs/blob/master/COPYRIGHT.md
 * @license   https://github.com/gordon-ansell/greenfedora-wtcalcs/blob/master/LICENSE.md New BSD License.
 */

declare(strict_types=1);
namespace WTCalcs\Domain\Wilks;

use GreenFedora\Arr\Arr;
use GreenFedora\Arr\ArrInterface;

use WTCalcs\Domain\Wilks\WilksCalculator;
use WTCalcs\Domain\Wilks\WilksResult;


/**
 * The Wilks domain service module.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class WilksDomainService
{
    /**
     * The inputs.
     * @var ArrInterface
     */
    protected $data = null;

    /**
     * Constructor.
     * 
     * @param  array   $inputs  Inputs to configure us.
     * @return void 
     */
    public function __construct(array $inputs = [])
    {
        $this->data = new Arr($inputs);
    }

    /**
     * Calculate wilks.
     * 
     * @return WilksResult[]
     */
    public function calculateWilks(): array
    {
        $calculator = new WilksCalculator();

        $resultsWilks = [];

        return $resultsWilks;

    }
}
