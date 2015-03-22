<?php

    use Tasker\Mvc\ApiController,
        Dez\Core\Session,

        Tasker\Api\Response as ApiResponse;

    class AuthController extends ApiController {

        public function processGETAction() {
            return ApiResponse::success( [
                'status'            => $this->auth->isLogged(),
                'auth_id'           => (int) $this->auth->id(),
            ] );
        }

        public function processPOSTAction() {
            $login      = $this->request->post( 'login', null );
            $password   = $this->request->post( 'password', null );

            $this->auth->getToken( $login, $password );
            $session = Session::instance()->get( 'session_data' );
            return ApiResponse::success( [
                'token' => $session['access_token']
            ] );
        }

        public function processDELETEAction() {
            $this->auth->logout();
            return ApiResponse::success( [
                'message' => 'You are logged out'
            ] );
        }

    }