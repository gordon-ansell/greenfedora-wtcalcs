<?php

/**
 * @see       https://github.com/gordon-ansell/greenfedora-wtcalcs for the canonical source repository.
 * @copyright https://github.com/gordon-ansell/greenfedora-wtcalcs/blob/master/COPYRIGHT.md
 * @license   https://github.com/gordon-ansell/greenfedora-wtcalcs/blob/master/LICENSE.md New BSD License.
 */

declare(strict_types=1);
namespace WTCalcs\Adr\Domain\Onerm;

/**
 * Contains a 1-rep max calculation.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */
class OnermResult 
{
    /**
     * Name.
     * @var string
     */
    public $name = null;

    /**
     * Value.
     * @var float
     */
    public $value = null;

    /**
     * Rounded value.
     * @var float
     */
    public $rounded = null;

    /**
     * Constructor.
     *
     * @param   string  $name   Name of the calculation type.
     * @param   float   $value  Value of the calculation.
     *
     * @return  void
     */
    public function __construct(string $name = null, float $value = null)
    {
        $this->name = $name;
        $this->value = $value;
    }
}
