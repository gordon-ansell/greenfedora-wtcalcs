<?php

/**
 * This kicks off the whole application.
 * 
 * @see       https://github.com/gordon-ansell/greenfedora-wtcalcs for the canonical source repository.
 * @copyright https://github.com/gordon-ansell/greenfedora-wtcalcs/blob/master/COPYRIGHT.md
 * @license   https://github.com/gordon-ansell/greenfedora-wtcalcs/blob/master/LICENSE.md New BSD License.
 */

namespace WTCalcs;

/**
 * Main WTCalcs app.
 */
class WTCalcsApplication
{
    /**
     * Constructor.
     *
     * @param   string  $mode       Application mode.
     * 
     * @return  void
     */
    public function __construct(string $mode = 'dev')
    {
        if ('dev' == $mode) {
            error_reporting(E_ALL);
            ini_set('display_errors', 'On');
            ini_set('display_startup_errors', 'On');
            define('RENDER_EXCEPTIONS', true);
        } else {
            define('RENDER_EXCEPTIONS', true);
        }
    }

    /**
     * Dispatch the application.
     *
     * @return  void
     */
    public function dispatch()
    {
        echo 'Here.';
    }
}
