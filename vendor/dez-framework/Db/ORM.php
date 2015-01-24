<?php

    namespace Dez;

    use Dez\ORM\Autoloader,
        Dez\ORM\Common\Config,
        Dez\ORM\Exception\Error as ORMException,

        Dez\ORM\Connection,

        Dez\ORM\Entity\Table,
        Dez\ORM\Wrapper;

    class ORM {

        static private
            $connections    = array(),
            $connectionName = null;

        static public function init( $configFile = null, $connectionName = null ) {
            include_once __DIR__ . '/ORM/Autoloader.php';
            new Autoloader;

            try {
                Config::setConfig( $configFile );
            } catch ( ORMException $e ) {
                die( $e->getMessage() );
            }

            self::setConnectionName( $connectionName );
        }

        static public function setConnectionName( $connectionName = null ) {
            self::$connectionName = $connectionName;
        }

        static public function connect() {
            $hash   = md5( self::$connectionName );
            if( ! isset( self::$connections[ $hash ] ) ) {
                self::$connections[ $hash ] = new Connection\DBO( self::$connectionName );
            }
            return self::$connections[ $hash ];
        }

    }