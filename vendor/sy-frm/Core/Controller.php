<?php
	
	namespace Sy\Core;
	
	class Controller extends Object {

        static private
			$models = array();

        protected
            $response   = null,
            $request    = null,
            $view       = null,
            $context    = null;

        public function __construct(){
            $app            = \Sy::app();
            $this->response = $app->response;
            $this->request  = $app->request;
            $this->view     = new View( APP_PATH . DS . 'view' );
        }

        public function getResponse() {
            return $this->response;
        }

        public function getRequest() {
            return $this->request;
        }

        public function getView() {
            return $this->view;
        }

        public function setContext( $wrapperRoute ) {
            $this->context  = $wrapperRoute;
        }

        public function getContext() {
            return $this->context;
        }

        public function beforeExecute() {}

        public function afterExecute() {}

        protected function render( $file, $data ) {
            return $this->getView()->render( $file, $data );
        }

        protected function forward( $path, array $args = array() ) {
            list( $controllerName, $actionName ) = explode( '/', $path );
            $action     = \Sy::app()->action;
            $instance   = $action->getControllerInstance( $controllerName );
            return $action->executeAction( $instance, $actionName, $args );
        }

        protected function getModel( $model_name = null ) {

			$model_name = preg_replace( '/[^0-9a-z_]/iu', '', $model_name );
			$model_name = ucfirst( strtolower( $model_name ) );

			$hash       = md5( $model_name );

			if( ! isset( self::$models[$hash] ) ) {
				self::$models[$hash] = $this->_createModel( $model_name );
			}

			return self::$models[$hash];

		}

        private function _createModel( $model_name ) {

			$model_file = APP_PATH . DS . 'model' . DS . strtolower( $model_name ) . '.php';

			if( ! file_exists( $model_file ) ) {
				Error::critical( 'Model file not found ['. $model_name .']' );
				return false;
			}

			include_once $model_file;

			$model_class = $model_name . 'Model';

			if( class_exists( $model_class ) == false ) {
				Error::critical( 'Model class not found ['. $model_class .']' );
				return false;
			}

			return new $model_class;

		}

        protected function redirect( $url ) {
            \Sy::app()->redirect( $url );
        }

	}