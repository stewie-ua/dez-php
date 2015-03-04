<?php

    use Tasker\Mvc\ApiController,
        Tasker\Api\Response as ApiResponse;

    class UserController extends ApiController {

        public function itemGETAction( $id ) {


//            dump( \DB\UserModel::one(17)->getEmail() );

            $user = \DB\UserModel::one(42);

            dump( $user );

//            dump( $user, $user->exists() );
//die('ok');

            $users = \DB\UserModel::all();


                foreach( $users as $user ) {

                    var_dump( $user->id() );

                } die;


//            $user->bind([
//                'email'     => 'mail-'. rand( 1, 10000 ) .'@mail.com',
//                'password'  => md5( rand( 1, 10000 ) ),
//            ]);

//            dump( $user->id(), $user->save(), $user->exists(), $user->id() );

//            dump( $user );


//            dump(  );

//            \DB\UserModel::all();

//            $users = \DB\User::findAll();
////            dump($users);
//            $users;

            return ApiResponse::success( [ 'test' => \DB\UserModel::all()->toArray() ] );
        }

    }