<?php

    namespace Dez\Helper;

    use Dez\Core;

    class Debug {

        use Core\SingletonTrait;

        const
            DEBUGGER_NAME = 'SyDebugger (rc-2014_12)';

        protected
            $view   = null,
            $data   = [ 'sql' => [], 'dump' => [], 'profiler' => [] ];

        protected function init() {
            $this->view = Core\View::instance( __DIR__ );
        }

        public function sql( $query = null ) {
            $this->data['sql'][] = $query;
        }

        public function dump( $data = null ) {
            $this->data['dump'][] = $data;
        }

        public function render() {
            $this->data['debugger_name'] = static::DEBUGGER_NAME;
            return $this->view->render( 'DebugTemplate', [
                'data'  => $this->data
            ] );
        }

    }