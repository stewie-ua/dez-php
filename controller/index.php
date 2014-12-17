<?php

    use \Dez\Core,
        \Dez\Common\Validator,
        \Dez\Error\Error,
        \Dez\Utils,
        \Dez\Web;

    class IndexController extends Core\Controller {

        public function __construct() {
            parent::__construct();
            $this->response->setLayout( 'index' );
        }

        public function pageUnderConstructionAction() {
            return '<h2>Sorry, this page coming soon...</h2>';
        }

        public function page404Action(){

            Web\Asset::css( '@cache/media/css/head.css' );
            Web\Asset::css( '@media/css/style.css' );
            Web\Asset::css( '@css/footer.css' );
            Web\Asset::css( '@js/js-folder.css' );

            return sprintf( '<h2>Sorry, this page %s not found...</h2>', url() );
        }

        public function loginPageAction() {
            $this->response->addTitle( 'Авторизация' );
            if( \Dez::app()->auth->isLogged() ) {
                Error::critical( 'Вы уже авторизированы' );
            } else {
                return $this->render( 'auth/login', array( ) );
            }
        }

        public function loginDoAction() {
            $data = array(
                'email'     => $this->request->post( 'email', null ),
                'password'  => $this->request->post( 'password', null ),
            );
            if( $this->request->isPost() ) {
                try {
                    \Dez::app()->auth->login( array( $data['email'], $data['password'] ) );
                    $this->request->get( 'return_url', false )
                        ? $this->redirect( $this->request->get( 'return_url' ) )
                        : $this->redirect( '/' );
                } catch( \Exception $e ) {
                    Error::critical( $e->getMessage() );
                    $this->redirect( 'auth/login' );
                }
            }
        }

        public function logoutAction() {
            \Dez::app()->auth->logout();
            $this->redirect( '/' );
        }

        public function registrationAction() {
            $this->response->addTitle( 'Регистрация' );
            return $this->render( 'auth/registration', array() );
        }

        public function registrationProcessAction() {
            $data = array(
                'email'     => $this->request->post( 'email', null ),
                'login'     => $this->request->post( 'login', null ),
                'password'  => $this->request->post( 'password', null ),
            );
            $passwordRepeat = $this->request->post( 'password_repeat', null );

            $validator = new Validator();

            $validator->attachData( $data );
            $validator->addRule(
                new Validator\Rule( 'email', 'email', null, 'Укажите правильный e-mail' )
            );
            $validator->addRule(
                new Validator\Rule( 'login', 'len', '3-32', 'Длина ника должна быть не менее 3-х символов и не более 32-х' )
            );
            $validator->addRule(
                new Validator\Rule( 'password', 'notempty', null, 'Пароль не можеть быть пустым' )
            );
            $validator->addRule(
                new Validator\Rule( 'password', 'callback', function( $password ) use ( $passwordRepeat ) {
                    return ( $password === $passwordRepeat );
                }, 'Пароли не одинаковые' )
            );

            $validator->run();

            if( $validator->isError() ) {
                Error::critical( join( '<br />', $validator->getErrors() ) );
                $this->redirect( 'auth/registration' );
            } else {
                if( $this->request->isPost() ) {
                    try {
                        \Dez::app()->auth->add( array( $data['login'], $data['email'], $data['password'] ) );
                        $this->redirect( '/' );
                    } catch( \Exception $e ) {
                        Error::critical( $e->getMessage() );
                        $this->redirect( 'auth/registration' );
                    }
                }
            }
        }

        public function indexAction() {

            $this->response->addTitle( 'Главная' );

            Core\Message::success( 'ok, all successfully' );

            $qb = new \Dez\ORM\Query\Builder( \Dez::app()->db );

            $qb
                ->select( array( 'id', 'name tagName', 'post.title postTitle' ) )
                ->table( 'tags' )
                ->innerJoin( 'xref_tags', 'tags', [ 'tag_id', 'id' ] )
                ->innerJoin( 'post', 'xref_tags', [ 'id', 'post_id' ] )
                ->orderAlias( array( 'tagName', 'desc' ) )
                ->where(
                    array( 'post.created', date( 'Y-m-d H:i:s' ), '>' ),
                    array( 'name', 'lol', '=' )
                );

            Dez\Helper\Debug::instance()->value( $qb );

            $encoded = Utils\Crypt::instance()->encode( $qb->query(), '1' );
            $decoded = Utils\Crypt::instance()->decode( $encoded, '1' );

            return '<pre>'. $encoded ."\n\n". $decoded .'</pre>';
        }

    }