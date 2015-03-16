<?php

    use Tasker\Mvc\ApiController,
        Tasker\Api\Response as ApiResponse;

    class UserController extends ApiController {

        public function itemGETAction( $id ) {
            return ApiResponse::success( [ 'user' => \DB\UserModel::one( $id )->toArray() ] );
        }

    }