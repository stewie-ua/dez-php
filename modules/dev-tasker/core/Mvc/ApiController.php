<?php

    namespace Tasker\Mvc;

    use Dez\Auth\Api        as AuthAPI;
    use Dez\Auth\Web        as AuthWeb;
    use Dez\Controller\Controller,
        Tasker\Api\Response as ApiResponse;

    /**
     * @property AuthAPI $auth
     */

    class ApiController extends Controller {

        protected
            $requestMethod  = null,
            $auth           = null;

        public function beforeExecute() {
            $this->auth                 = AuthAPI::instance();
            $this->requestMethod        = strtoupper( $this->request->method );
            $wrappedRouter              = \Dez::app()->action->getWrapperRoute();
            $params                     = $wrappedRouter->getParams();
            $methodName                 = $wrappedRouter->getActionName() . $this->requestMethod;
            array_unshift( $params, $methodName );
            $wrappedRouter->setParams( $params );
            $wrappedRouter->setActionName( 'run' );
        }

        public function runAction() {
            $params     = func_get_args();
            $methodName = array_shift( $params );
            $wrappedRouter              = \Dez::app()->action->getWrapperRoute();
            if( $wrappedRouter->getControllerName() != 'auth' && $this->request->get( 'token' ) == $this->auth->get( 'token_key' ) ) {

            }
            try {
                return $this->forward( $this, $methodName, $params );
            } catch ( \Exception $e ) {
                \Dez\Response\Response::instance()->setCode( 404 );
                return ApiResponse::error( 'BAD REQUEST ('. $e->getMessage() .')', 101 );
            }
        }

    }