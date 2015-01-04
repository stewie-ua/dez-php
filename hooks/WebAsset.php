<?php

    namespace Hook;

    use Dez\Core\Object,
        Dez\Dispatcher\Dispatcher,
        Dez\Dispatcher\EventInterface;

    class WebAsset extends Object implements EventInterface {

        public function registerListeners () {
            Dispatcher::instance()->listen( 'beforeRun', [ $this, 'beforeRun' ] );
        }

        public function beforeRun( $application ) {
            $application->layout->js( '@js/dom.js' )->css( '@css/main.css' );
        }

    }