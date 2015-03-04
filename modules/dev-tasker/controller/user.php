<?php

    use Tasker\Mvc\ApiController,
        Tasker\Api\Response as ApiResponse;

    class UserController extends ApiController {

        public function itemGETAction( $id ) {


//            dump( \DB\UserModel::one(17)->getEmail() );

//            $user = \DB\UserModel::one(42);

            $user = new \DB\UserModel();

            $user->bind([
                'email'     => 'mail@mail.com',
                'password'  => md5( rand( 1, 10000 ) ),
            ]);



            dump( $user->toArray(), $user->exists(), $user->save(), $user->toArray(), $user->id(), $user->exists() );


//            dump(  );

//            \DB\UserModel::all();

//            $users = \DB\User::findAll();
////            dump($users);
//            $users;

            return ApiResponse::success( [ 'test' => \DB\UserModel::all()->toArray() ] );
        }

    }