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
use GreenFedora\Payload\PayloadInterface;
use GreenFedora\Table\Table;
use GreenFedora\Table\TableInterface;
use GreenFedora\Filter\NumberFormat;
use GreenFedora\Json\Json;

use Spatie\SchemaOrg\Schema;
use Spatie\SchemaOrg\ReferencedType;
use Spatie\SchemaOrg\Graph;


/**
 * The Wilks calculator responder.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class WilksResponder extends AbstractBaseResponder implements ResponderInterface
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
            ->name("Wilks Score Calculator")
            ->description("Calculate your weight training Wilks score, used in powerlifting competitions. Bodyweight and age-adjusted. Also calculates allometric and SIFF scores.")
            ->url("https://wtcalcs.gordonansell.com/wilks")
            ->lastReviewed("2021-04-08T08:00:00.000Z")
            ->author(['@id' => '/#author'])
            ->isPartOf(['@id' => '/#website']);

        // Web application.
        $graph->webApplication()
            ->name("Wilks Score Calculator")
            ->description("Calculate your weight training Wilks score, used in powerlifting competitions. Bodyweight and age-adjusted. Also calculates allometric and SIFF scores.")
            ->applicationCategory('Productivity')
            ->operatingSystem('any')
            ->browserRequirements('HTML5')
            ->screenshot(["https://wtcalcs.gordonansell.com/public/images/wilks-screenshot1.png","https://wtcalcs.gordonansell.com/public/images/wilks-screenshot1.png"])
            ->softwareVersion(APP_VERSION)
            ->mainEntityOfPage(['@id' => '/#webpage']);

        return $graph;
    }

    /**
     * Create the results table.
     * 
     * @param   PayloadInterface         $payload    Data payload.
     * @param   int                      $num        Table number.
     * @return  TableInterface 
     */
    protected function resultsTable(PayloadInterface $payload, int $num): TableInterface
    {
        $table = new Table('flextable stripe wtcalcs wilks' . $num);

        $table->addColumn('Method', 'size-50')
            ->addColumn('Result', 'size-50 right')
            ->addColumn('Multiplier', 'size-50 right');

        $table->getColumn(2)->addFilter(new NumberFormat(array('decimals' => 2)));
        $table->getColumn(3)->addFilter(new NumberFormat(array('decimals' => 2)));

        return $table;
    }

    /**
     * Dispatch the responder.
     */
    public function dispatch()
    {
        if ($this->input->isPost()) {
            // Wilks results table.
            $wilksTable = $this->resultsTable($this->payload, 1);
            $wilksTable->setData($this->payload->get('resultsWilks')->toArray());
            $this->payload->set('wilksTable', $wilksTable);

            // Allometric results table.
            $alloTable = $this->resultsTable($this->payload, 2);
            $alloTable->setData($this->payload->get('resultsAllometric')->toArray());
            $this->payload->set('alloTable', $alloTable);

            // Allometric results table.
            $siffTable = $this->resultsTable($this->payload, 3);
            $siffTable->setData($this->payload->get('resultsSiff')->toArray());
            $this->payload->set('siffTable', $siffTable);
        }

        $this->payload->set('schema', json_encode($this->schema(), JSON_PRETTY_PRINT));

        $r = $this->container->get('template')->render('wilks', $this->payload->toArray());
        $this->output->setBody($r);
    }
}
