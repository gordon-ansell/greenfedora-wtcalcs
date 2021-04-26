<?php

/**
 * @see       https://github.com/gordon-ansell/greenfedora-wtcalcs for the canonical source repository.
 * @copyright https://github.com/gordon-ansell/greenfedora-wtcalcs/blob/master/COPYRIGHT.md
 * @license   https://github.com/gordon-ansell/greenfedora-wtcalcs/blob/master/LICENSE.md New BSD License.
 */

declare(strict_types=1);
namespace WTCalcs\Ui\NotFound;

use WTCalcs\Ui\AbstractBaseResponder;
use GreenFedora\Application\ResponseInterface;

/**
 * The not found responder.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class NotFoundResponder extends AbstractBaseResponder
{
    /**
     * Dispatch the responder.
     * 
     * @return  HttpResponseInterface
     */
    public function respond(): ResponseInterface
    {
        $r = $this->container->get('template')->render('404', $this->payload->getdata()->toArray());
        $this->response->setContent($r);
        return $this->response;
    }
}
