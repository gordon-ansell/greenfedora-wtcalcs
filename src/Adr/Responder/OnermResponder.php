<?php

/**
 * @see       https://github.com/gordon-ansell/greenfedora-wtcalcs for the canonical source repository.
 * @copyright https://github.com/gordon-ansell/greenfedora-wtcalcs/blob/master/COPYRIGHT.md
 * @license   https://github.com/gordon-ansell/greenfedora-wtcalcs/blob/master/LICENSE.md New BSD License.
 */

declare(strict_types=1);
namespace WTCalcs\Adr\Responder;

use GreenFedora\Adr\Responder\AbstractResponder;
use GreenFedora\Adr\Responder\ResponderInterface;

use GreenFedora\Payload\PayloadInterface;

use GreenFedora\Table\Table;
use GreenFedora\Table\TableInterface;

use GreenFedora\Filter\NumberFormat;

/**
 * The 1-rep maximum calculator responder.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class OnermResponder extends AbstractResponder implements ResponderInterface
{
    /**
     * Create the results table.
     * 
     * @param   PayloadInterface         $payload    Data payload.
     * @return  TableInterface 
     */
    protected function resultsTable(PayloadInterface $payload): TableInterface
    {
        $table = new Table('flextable stripe');

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
        $table = new Table('flextable stripe');

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

        $r = $this->container->get('template')->render('onerm', $this->payload->toArray());
        $this->output->setBody($r);
    }
}
