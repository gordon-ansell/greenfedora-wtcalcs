<?php

/**
 * @see       https://github.com/gordon-ansell/greenfedora-wtcalcs for the canonical source repository.
 * @copyright https://github.com/gordon-ansell/greenfedora-wtcalcs/blob/master/COPYRIGHT.md
 * @license   https://github.com/gordon-ansell/greenfedora-wtcalcs/blob/master/LICENSE.md New BSD License.
 */

declare(strict_types=1);
namespace WTCalcs\Domain\Wilks;

use GreenFedora\Arr\Arr;

/**
 * Contains a wilks calculation.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */
class WilksResult extends Arr
{
    /**
     * Constructor.
     *
     * @param   string  $name   Name of the calculation type.
     * @param   float   $value  Value of the calculation.
     *
     * @return  void
     */
    public function __construct(string $name = null, float $value = null, $extra = null)
    {
        parent::__construct(['name' => $name, 'value' => $value, 'extra' => $extra]);
    }

}
