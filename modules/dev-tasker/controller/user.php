<?php

    use Tasker\Mvc\ApiController,
        Tasker\Api\Response as ApiResponse;

    class UserController extends ApiController {

        public function itemGETAction( $id ) {

            $event = \Dez\ORM\Common\Event::instance();

            $q = [];

            $event->attach( 'query', function ( $sql ) use ( & $q ) {
                $q[]    = $sql;
            } );

//            \DB\UserModel::one(17)->sessions();


            foreach( \DB\UserModel::all() as $user ) {
                $user->sessions();
                print $user->id() . "\n";
            }


            dump( 'sql dump', implode( "\n\n\n", $q ) );

//            $user17 = \DB\UserModel::one(17);

//            dump( $user17->sessions()->getUserAgent() );



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