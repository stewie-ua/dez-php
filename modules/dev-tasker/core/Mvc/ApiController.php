<?php

    namespace Tasker\Mvc;

    use Dez\Auth\Api        as AuthAPI;
    use Dez\Auth\Web        as AuthWeb;
    use Dez\Controller\Controller;
    use Dez\Response\Response;
    use Tasker\Api\Response as ApiResponse;

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
            $params         = func_get_args();
            $methodName     = array_shift( $params );
            $wrappedRouter  = \Dez::app()->action->getWrapperRoute();

            $this->auth->authenticate( $this->request->get( 'token' ) );

            if( $wrappedRouter->getControllerName() != 'auth' && 0 >= $this->auth->id() ) {
                Response::instance()->setCode( 500 );
                return ApiResponse::tokenError();
            }

            try {
                return $this->forward( $this, $methodName, $params );
            } catch ( \Exception $e ) {
                Response::instance()->setCode( 404 );
                return ApiResponse::error( 'BAD REQUEST ('. $e->getMessage() .')', 101 );
            }
        }

    }