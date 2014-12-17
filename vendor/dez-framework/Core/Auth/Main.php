<?php

	namespace Dez\Core\Auth;

    use \Dez\ORM\Query;
	
	class Main extends \Dez\Core\Model {

		static public
            $table_name = 'system_auth';

        static private
            $builder = null;

        public function __construct() {
            parent::__construct();
            static::$builder = new Query\ActiveQuery( new Query\Builder( $this->db ) );
        }

        public function getAuthById( $userId = null ) {
            return static::$builder->select(
                static::$table_name,
                array( 'id', $userId )
            )->loadArray();
        }

        public function isExists( $login, $email ) {
            return static::$builder->select(
                static::$table_name,
                array(
                    array( 'login', $login ),
                    array( 'email', $email )
                )
            )->loadArray();
        }

        public function addNewAuth( array $authData = array() ) {
            return static::$builder->insert( self::$table_name, $authData )->lastInsertId();
        }

        public function getFullAuth( $login, $password ) {
            return static::$builder->select(
                static::$table_name,
                array(
                    array( 'email', $login ),
                    array( 'password', $password )
                )
            )->loadArray();
        }

	}