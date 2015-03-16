<?php

    use Tasker\Mvc\ApiController,
        Tasker\Api\Response as ApiResponse;

    class UserController extends ApiController {

        public function itemGETAction( $id ) {
            dump($this);
            return ApiResponse::success( [ 'user' => \DB\User::one( $id )->toArray() ] );
        }

    }