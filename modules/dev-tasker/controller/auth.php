<?php

    use Tasker\Mvc\ApiController,
        Dez\Core\Session,

        Tasker\Api\Response as ApiResponse;

    class AuthController extends ApiController {

        public function processGETAction() {
            return ApiResponse::success( [
                'status'            => $this->auth->id() > 0,
                'auth_id'           => (int) $this->auth->id(),
            ] );
        }

        public function processPOSTAction() {
            $login      = $this->request->post( 'login', null );
            $password   = $this->request->post( 'password', null );
            $token      = $this->auth->getToken( $login, $password );
            return ApiResponse::success( [
                'token' => $token->getTokenKey()
            ] );
        }

        public function processDELETEAction() {
            $this->auth->logout();
            return ApiResponse::success( [
                'message' => 'You are logged out'
            ] );
        }

    }