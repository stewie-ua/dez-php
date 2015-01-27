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
            $login      = $this->request->get( 'login', null );
            $password   = $this->request->get( 'password', null );
            try {
                $this->auth->login( [ $login, $password ] );
                $session = Session::instance()->get( 'session_data' );
                return ApiResponse::success( [
                    'token' => $session['access_token']
                ] );
            } catch ( \Exception $e ) {
                return ApiResponse::error( $e->getMessage(), 20 );
            }
        }

        public function processDELETEAction() {
            $this->auth->logout();
            return ApiResponse::success( [
                'message' => 'You are logged out'
            ] );
        }

    }