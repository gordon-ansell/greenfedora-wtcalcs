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
 * The Wilks calculator responder.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class WilksResponder extends AbstractResponder implements ResponderInterface
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
            $wilksTable = $this->resultsTable($this->payload);
            $wilksTable->setData($this->payload->get('resultsWilks')->toArray());
            $this->payload->set('wilksTable', $wilksTable);

            // Allometric results table.
            $alloTable = $this->resultsTable($this->payload);
            $alloTable->setData($this->payload->get('resultsAllometric')->toArray());
            $this->payload->set('alloTable', $alloTable);

            // Allometric results table.
            $siffTable = $this->resultsTable($this->payload);
            $siffTable->setData($this->payload->get('resultsSiff')->toArray());
            $this->payload->set('siffTable', $siffTable);
        }

        $r = $this->container->get('template')->render('wilks', $this->payload->toArray());
        $this->output->setBody($r);
    }
}
