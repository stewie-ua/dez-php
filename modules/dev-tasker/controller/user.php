<?php

    use Tasker\Mvc\ApiController,
        Tasker\Api\Response as ApiResponse;

    class UserController extends ApiController {

        public function itemGETAction( $id ) {

//            $users = \DB\UserModel::query();


//            dump( \DB\UserModel::one(17)->getEmail() );

            $user = new \DB\UserModel();

            $user->bind([
                'email'     => 'mail@mail.com',
                'password'  => '123qwe',
            ]);

            $user->setName( 'Коля' );

            $user->updated_at = '2014-03-03 00:00:01';

            dump( $user->save()  );


//            dump(  );

//            \DB\UserModel::all();

//            $users = \DB\User::findAll();
////            dump($users);
//            $users;

            return ApiResponse::success( [ 'test' => \DB\UserModel::all()->toArray() ] );
        }

    }