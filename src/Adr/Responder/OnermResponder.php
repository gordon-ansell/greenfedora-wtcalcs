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

use Spatie\SchemaOrg\Schema;
use Spatie\SchemaOrg\ReferencedType;
use Spatie\SchemaOrg\Graph;

/**
 * The 1-rep maximum calculator responder.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class OnermResponder extends AbstractBaseResponder implements ResponderInterface
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
            ->name("1-Rep Maximum Calculator")
            ->description("Calculate your weight training 1-rep maximum from 2-15 reps. Displays the results for many formulae and gives an average. Epley, Brzycki, McGlothin, Lombardi, Mayhew, Wathan, O'Conner.")
            ->url("https://wtcalcs.gordonansell.com/wilks")
            ->lastReviewed("2021-04-08T08:00:00.000Z")
            ->author(['@id' => '/#author'])
            ->isPartOf(['@id' => '/#website']);

        // Web application.
        $graph->webApplication()
            ->name("1-Rep Maximum Calculator")
            ->description("Calculate your weight training 1-rep maximum from 2-15 reps. Displays the results for many formulae and gives an average. Epley, Brzycki, McGlothin, Lombardi, Mayhew, Wathan, O'Conner.")
            ->applicationCategory('Productivity')
            ->operatingSystem('any')
            ->browserRequirements('HTML5')
            ->screenshot("https://wtcalcs.gordonansell.com/public/images/onerm-screenshot1.png")
            ->softwareVersion(APP_VERSION)
            ->mainEntityOfPage(['@id' => '/#webpage']);

        return $graph;
    }

    /**
     * Create the results table.
     * 
     * @param   PayloadInterface         $payload    Data payload.
     * @return  TableInterface 
     */
    protected function resultsTable(PayloadInterface $payload): TableInterface
    {
        $table = new Table('flextable stripe wtcalcs onerm1');

        $table->addColumn('Method', 'size-50')
            ->addColumn('Value', 'size-50 right')
            ->addColumn('Result (to nearest ' . $payload->get('rounding') . ')', 'size-50 right');

        $table->getColumn(2)->addFilter(new NumberFormat(array('decimals' => 2)));
        $table->getColumn(3)->addFilter(new NumberFormat(array('decimals' => 4)));
        $table->getColumn(2)->setHidden();

        return $table;
    }

    /**
     * Create the percents table.
     * 
     * @param   PayloadInterface         $payload    Data payload.
     * @return  TableInterface 
     */
    protected function percentTable(PayloadInterface $payload): TableInterface
    {
        $table = new Table('flextable stripe wtcalcs onerm2');

        $table->addColumn('Percentage', 'size-50')
            ->addColumn('Value', 'size-50 right')
            ->addColumn('Result (to nearest ' . $payload->get('rounding') . ')', 'size-50 right');

        $table->getColumn(2)->addFilter(new NumberFormat(array('decimals' => 2)));
        $table->getColumn(3)->addFilter(new NumberFormat(array('decimals' => 2)));
        $table->getColumn(2)->setHidden();

        return $table;
    }

    /**
     * Dispatch the responder.
     */
    public function dispatch()
    {
        if ($this->input->isPost()) {
            // Results table.
            $resultsTable = $this->resultsTable($this->payload);
            $resultsTable->setData(array_merge($this->payload->get('results')->toArray(), 
                [$this->payload->get('average')->asArray()]));
            $this->payload->set('resultsTable', $resultsTable);

            // Percent table.
            $percentTable = $this->percentTable($this->payload);
            $percentTable->setData($this->payload->get('percents')->toArray());
            $this->payload->set('percentTable', $percentTable);
        }

        $this->payload->set('schema', json_encode($this->schema(), JSON_PRETTY_PRINT));

        $r = $this->container->get('template')->render('onerm', $this->payload->toArray());
        $this->output->setBody($r);
    }
}
