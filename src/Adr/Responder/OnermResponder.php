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

use GreenFedora\Arr\ArrInterface;

use GreenFedora\Table\Table;
use GreenFedora\Table\TableInterface;

use GreenFedora\Filter\NumberFormat;

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
     * @return  TableInterface 
     */
    protected function resultsTable(): TableInterface
    {
        $table = new Table('onerm-table', 'flextable stripe wtcalcs onerm1');

        $table->addColumn('method', 'Method', 'size-50')
            ->addColumn('value', 'Value', 'size-50 right')
            ->addSortableColumn('result', 'Result', 'size-50 right');

        $table->getColumn('value')->addFilter(new NumberFormat(array('decimals' => 4)));
        $table->getColumn('result')->addFilter(new NumberFormat(array('decimals' => 2)));
        $table->getColumn('value')->setHidden();

        return $table;
    }

    /**
     * Create the percents table.
     * 
     * @return  TableInterface 
     */
    protected function percentTable(): TableInterface
    {
        $table = new Table('percent', 'flextable stripe wtcalcs onerm2');

        $table->addColumn('percentage', 'Percentage', 'size-50')
            ->addColumn('value', 'Value', 'size-50 right')
            ->addColumn('result', 'Result', 'size-50 right');

        $table->getColumn('value')->addFilter(new NumberFormat(array('decimals' => 2)));
        $table->getColumn('result')->addFilter(new NumberFormat(array('decimals' => 2)));
        $table->getColumn('value')->setHidden();

        return $table;
    }

    /**
     * Sort results.
     * 
     * @param   ArrInterface    $results    Results to sort.
     * @param   string          $col        Column to sort by.
     * @param   string          $dir        Direction to sort.
     * @return  void
     */
    protected function sortResults(ArrInterface $results, string $col, string $dir)
    {
        $realDir = ('asc' == $dir) ? SORT_ASC : SORT_DESC;
        $results->sortByCol('rounded', $realDir);
        return $results;
    }

    /**
     * Dispatch the responder.
     */
    public function dispatch()
    {
        $resultsTable = $this->resultsTable($this->payload);

        if ($this->request->isPost()) {
            // Results table.
            $avg = ($this->payload->has('average')) ? $this->payload->get('average')->toArray() : [];
            $resultsTable->setData(array_merge($this->payload->get('results')->toArray(), [$avg]));
            $this->payload->set('resultsTable', $resultsTable);

            // Percent table.
            $percentTable = $this->percentTable($this->payload);
            $percentTable->setData($this->payload->get('percents')->toArray());
            $this->payload->set('percentTable', $percentTable);
        }

        // Configure sorting.
        $resultsTable->checkSort($this->request, $this->get('session'));

        // Do we need to sort?
        $resultsSort = $resultsTable->getSort();
        if (null !== $resultsSort and null !== $this->payload->get('results') and null !== $this->payload->get('average')) {
            $this->sortResults($this->payload->get('results'), $resultsSort[0], $resultsSort[1]);
            $resultsTable->setData(array_merge($this->payload->get('results')->toArray(), 
                [$this->payload->get('average')->toArray()]));
        }

        // Set the schema.
        $this->payload->set('schema', json_encode($this->schema(), JSON_PRETTY_PRINT));

        $r = $this->container->get('template')->render('onerm', $this->payload->toArray());

        $this->response->setContent($r);
    }
}
