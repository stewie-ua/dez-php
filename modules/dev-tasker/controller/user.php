<?php

    use Tasker\Mvc\ApiController,
        Tasker\Api\Response as ApiResponse;

    class UserController extends ApiController {

        public function itemGETAction( $id ) {

//            $users = \DB\UserModel::query();

            dump( \DB\UserModel::all() );

//            $users = \DB\User::findAll();
////            dump($users);
//            $users;

            return ApiResponse::success( [ 'test' => $id ] );
        }

    }