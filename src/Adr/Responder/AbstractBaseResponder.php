<?php

/**
 * @see       https://github.com/gordon-ansell/greenfedora-wtcalcs for the canonical source repository.
 * @copyright https://github.com/gordon-ansell/greenfedora-wtcalcs/blob/master/COPYRIGHT.md
 * @license   https://github.com/gordon-ansell/greenfedora-wtcalcs/blob/master/LICENSE.md New BSD License.
 */

declare(strict_types=1);
namespace WTCalcs\Adr\Responder;

use GreenFedora\Adr\Responder\AbstractResponder;

use Spatie\SchemaOrg\Schema;
use Spatie\SchemaOrg\Graph;

/**
 * The base responder.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

abstract class AbstractBaseResponder extends AbstractResponder
{
    /**
     * Generate schema.
     * 
     * @return  Graph
     */
    protected function schema(): Graph
    {
        $graph = new Graph();

        // Author/org image.
        $graph->imageObject()
            ->identifier('/#general-image')
            ->url("https://wtcalcs.gordonansell.com/public/images/greenhat-1024x1024.png");

        // Author.
        $graph->person()
            ->identifier("/#author")
            ->name("Gordon Ansell")
            ->url("https://gordonansell.com/about/")
            ->image(['@id' => '/#general-image']);

        // Publisher.
        $graph->organization()
            ->identifier("/#publisher")
            ->name("Gordy's Discourse")
            ->url("https://gordonansell.com")
            ->logo(['@id' => '/#general-image']);

        // Website.
        $graph->website()
            ->identifier('/#website')
            ->name("Weight Training Calculations")
            ->url("https://wtcalcs.gordonansell.com")
            ->description("Calculate 1-rep maximum, Wilks score, SIFF score and allometric scores. A range of bodyweight and age-adjusted weight training calculations.")
            ->image(['@id' => '/#general-image'])
            ->publisher(['@id' => '/#publisher']);

        return $graph;

    }

}