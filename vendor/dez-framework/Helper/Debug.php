<?php

    namespace Dez\Helper;

    use Dez\Core,
        Dez\View\View;

    class Debug {

        use Core\SingletonTrait;

        const
            DEBUGGER_NAME = 'DezDebug (1.0-beta)';

        protected
            $view   = null,
            $data   = [ 'sql' => [], 'dump' => [], 'profiler' => [] ];

        protected function init() {
            $this->view = ( new View )->setPath( __DIR__ .'/Debug' );
        }

        public function sql( $query = null ) {
            $this->data['sql'][] = $query;
        }

        public function dump( $data = null ) {
            $this->data['dump'][] = $data;
        }

        public function render() {
            $this->data['debugger_name'] = static::DEBUGGER_NAME;
            return $this->view->render( 'template', [
                'data'  => $this->data
            ] );
        }

    }