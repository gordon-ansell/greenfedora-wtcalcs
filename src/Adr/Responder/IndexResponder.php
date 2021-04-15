<?php

/**
 * @see       https://github.com/gordon-ansell/greenfedora-wtcalcs for the canonical source repository.
 * @copyright https://github.com/gordon-ansell/greenfedora-wtcalcs/blob/master/COPYRIGHT.md
 * @license   https://github.com/gordon-ansell/greenfedora-wtcalcs/blob/master/LICENSE.md New BSD License.
 */

declare(strict_types=1);
namespace WTCalcs\Adr\Responder;

use WTCalcs\Adr\Responder\AbstractBaseResponder;
use GreenFedora\Adr\Responder\ResponderInterface;

use Spatie\SchemaOrg\Schema;
use Spatie\SchemaOrg\ReferencedType;
use Spatie\SchemaOrg\Graph;

/**
 * The index responder.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class IndexResponder extends AbstractBaseResponder implements ResponderInterface
{
    /**
     * Generate schema.
     * 
     * @return  Graph
     */
    protected function schema(): Graph
    {
        $graph = parent::schema();

        // WebPage.
        $graph->webPage()
            ->identifier("/#webpage")
            ->name("Weight Training Calculations")
            ->url("https://wtcalcs.gordonansell.com")
            ->description("Calculate 1-rep maximum, Wilks score, SIFF score and allometric scores. A range of bodyweight and age-adjusted weight training calculations.")
            ->lastReviewed("2021-04-08T08:00:00.000Z")
            ->author(['@id' => '/#author'])
            ->isPartOf(['@id' => '/#website']);

        return $graph;
    }

    /**
     * Dispatch the responder.
     */
    public function dispatch()
    {
        $this->payload->set('schema', json_encode($this->schema(), JSON_PRETTY_PRINT));

        $r = $this->container->get('template')->render('index', $this->payload->toArray());
        $this->output->setBody($r);
    }
}
