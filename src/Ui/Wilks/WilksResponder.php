<?php

/**
 * @see       https://github.com/gordon-ansell/greenfedora-wtcalcs for the canonical source repository.
 * @copyright https://github.com/gordon-ansell/greenfedora-wtcalcs/blob/master/COPYRIGHT.md
 * @license   https://github.com/gordon-ansell/greenfedora-wtcalcs/blob/master/LICENSE.md New BSD License.
 */

declare(strict_types=1);
namespace WTCalcs\Ui\Wilks;

use WTCalcs\Ui\AbstractBaseResponder;
use GreenFedora\Application\Adr\ResponderInterface;
use GreenFedora\Payload\PayloadInterface;
use GreenFedora\Table\Table;
use GreenFedora\Table\TableInterface;
use GreenFedora\Filter\NumberFormat;
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
     * @param   string                   $name       Table name.
     * @param   PayloadInterface         $payload    Data payload.
     * @param   int                      $num        Table number.
     * @return  TableInterface 
     */
    protected function resultsTable(string $name, PayloadInterface $payload, int $num): TableInterface
    {
        $table = new Table($name, 'flextable stripe wtcalcs wilks' . $num);

        $table->addColumn('method', 'Method', 'size-50')
            ->addColumn('result', 'Result', 'size-50 right')
            ->addColumn('multiplier', 'Multiplier', 'size-50 right');

        $table->getColumn('result')->addFilter(new NumberFormat(array('decimals' => 2)));
        $table->getColumn('multiplier')->addFilter(new NumberFormat(array('decimals' => 2)));

        return $table;
    }

    /**
     * Dispatch the responder.
     */
    public function dispatch()
    {
        if ($this->request->isPost() and $this->request->formSubmitted('wilks')) {
            // Wilks results table.
            $wilksTable = $this->resultsTable('wilks', $this->payload, 1);
            $wilksTable->setData($this->payload->get('resultsWilks')->toArray());
            $this->payload->set('wilksTable', $wilksTable);

            // Allometric results table.
            $alloTable = $this->resultsTable('allometric', $this->payload, 2);
            $alloTable->setData($this->payload->get('resultsAllometric')->toArray());
            $this->payload->set('alloTable', $alloTable);

            // Allometric results table.
            $siffTable = $this->resultsTable('siff', $this->payload, 3);
            $siffTable->setData($this->payload->get('resultsSiff')->toArray());
            $this->payload->set('siffTable', $siffTable);
        }

        $this->payload->set('schema', json_encode($this->schema(), JSON_PRETTY_PRINT));

        $r = $this->container->get('template')->render('wilks', $this->payload->toArray());
        $this->response->setContent($r);
    }
}
