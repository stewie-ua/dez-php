<?php

    namespace Dez\Auth\Model;

    use \Dez\ORM\Query;

	class Sessions extends \Dez\Core\Model {

		static public
            $table_name = 'system_sessions';

        static private
            $builder = null;

        public function __construct() {
            parent::__construct();
            static::$builder = new Query\ActiveQuery( new Query\Builder( $this->db ) );
        }

        public function deleteOldSessions() {
            return static::$builder->delete( self::$table_name, array( 'expired_date', date( 'Y-m-d H:i:s' ), '<' ) );
        }

        public function getSessionByUniKey( $uniKey ) {
            return static::$builder->select( static::$table_name, array( 'uni_key', $uniKey ) )->loadArray();
        }

        public function addSession( array $sessionData = array() ){
            return static::$builder->insert( self::$table_name, $sessionData );
        }

        public function deleteSession( $uniKey = null ) {
            return static::$builder->delete( self::$table_name, array( 'uni_key', $uniKey ) );
        }

        public function updateOnline( $authId = -1, $uniKey = null ) {
            return static::$builder->update( self::$table_name, array(
                'last_date'     => date( 'Y-m-d H:i:s' )
            ), array( array( 'user_id', $authId ), array( 'uni_key', $uniKey ) ) );
        }

	}