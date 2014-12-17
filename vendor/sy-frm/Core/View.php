<?php

    namespace Sy\Core;

    class View extends Object {

        use SingletonTrait;

        private
            $driver             = array(),
            $driverName         = 'native',
            $templateDirectory  = null,
            $templateExt        = null;

        protected function init( $templateDirectory = null, $templateExt = 'php' ) {
            if( $templateDirectory == null ) {
                $templateDirectory = APP_PATH . DS . 'view';
            }
            $this->templateDirectory    = $templateDirectory;
            $this->templateExt          = $templateExt;
            $this->driverName           = 'native';
            $this->_loadDriver();
        }

        public function setDriver( $driver_name = 'native' ) {
            $this->driverName = $driver_name;
            if( ! isset( $this->driver[$driver_name] ) ) {
                $this->_loadDriver();
            }
        }

        public function getDriver( $driver_name = 'native' ) {
            if( isset( $this->driver[$driver_name] ) ) {
                return $this->driver[$driver_name];
            } else {
                return null;
            }
        }

        public function render( $templateFile = null, array $templateData = array() ) {
            return $this->driver[$this->driverName]->render( $templateFile, $templateData );
        }

        private function _loadDriver() {
            $className
                = __NAMESPACE__ .'\\View\\Drivers\\'. ucfirst( strtolower( $this->driverName ) );
            $this->driver[$this->driverName]
                = new $className( $this->templateDirectory, $this->templateExt );
        }

    }