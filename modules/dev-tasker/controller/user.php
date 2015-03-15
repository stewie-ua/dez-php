<?php

    use Tasker\Mvc\ApiController,
        Tasker\Api\Response as ApiResponse;

    class UserController extends ApiController {

        public function itemGETAction( $id ) {

            $event = \Dez\ORM\Common\Event::instance();
//
//            $q = [];
//
//            $event->attach( 'query', function ( $sql ) use ( & $q ) {
//                $q[]    = $sql;
//            } );

//            \DB\UserModel::one(17)->sessions();


//            $sessions = [];
//
//            foreach( \DB\UserModel::all() as $user ) {
//                print $user->id() . "\n --- \n";
//
//                foreach( $user->sessions() as $session ) {
//                    print $session->getUniKey();
//                }
//
//                print "\n----\n";
//            }
//            die;
//            dump( $sessions );


//            dump( 'sql dump', implode( "\n\n\n", $q ) );

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

//            $asdasdasd = \DB\UserModel::all()->toArray();
//
//            dump( $asdasdasd );


//            $q = 'select * from system_auth where id = 99';
//
//            $stmt = \Dez\ORM::connect()->query( $q );
//
//            dump( $stmt->loadArray() );



            $user = \DB\UserModel::one(17);

            dump( $user->toArray() );


            $users = \DB\UserModel::all();

            $sessions = [];

            foreach( $users as $user ) {
                $sessions[$user->id()]  = $user->session()->toArray();
            }

            return ApiResponse::success( [ 'users' => $users->toArray(), 'users_sessions' => $sessions, 'sql_queries' => $q ] );
        }

    }