<?php

    use Dez\Core,
        Dez\Core\App,
        Dez\Error\Exception\RuntimeError;

	class DezCore {

		static public
			$startTime = 0;

        static protected
            $aliases   = [],
            $app       = null,
            $conf      = null;

        /**
         * @throws \Exception
         * @return \Dez\Core\App
        */

		static public function app(){			
			if( ! is_object( self::$app ) && ! ( self::$app instanceOf Core\App ) )
                throw new RuntimeError( 'App not created' );
			return self::$app;
		}
		
		static public function newWebApplication( Core\Config $config ){
			self::$startTime = getMicroTime();
			if( empty( self::$app ) )
                static::$app = new App\Web( $config );
		}

		static public function newCliApplication( Core\Config $config, $args = array() ) {

		}

		static public function createConfig( $config_file ){
			self::$conf = new Core\Config( $config_file );
			return self::$conf;
		}
		
		static public function cfg( $key = null ){
            return ! $key ? static::$conf : static::$conf->get( $key );
		}

        static public function setAlias( $alias, $replacement = null ) {
            static::$aliases[$alias] = $replacement;
        }

        static public function getAlias( $value = null ) {
            while( strpos( $value, '@' ) !== false )
                $value = str_replace( array_keys( static::$aliases ), array_values( static::$aliases ), $value );
            return $value;
        }
	
		static public function t( $text ){
			return $text;
		}

		static public function env(){
			return self::$app->environment;
		}

		static public function poweredBy(){
			return DEZ_NAME . ' ' . DEZ_VERVION;
		}

		static public function getTimeDiff() {
			return round( getMicroTime() - self::$startTime, 4 );
		}

		static public function getMemoryUse() {
			$memory = memory_get_usage();
			return round( $memory / 1024, 4 );
		}

	}