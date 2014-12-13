<?php

    use \Sy\Core,
        \Sy\Core\App;

	class SyCore {

		static public
			$startTime = 0;
		
		static private
			$_app       = null,
			$_conf      = null;

        static protected
            $_aliases   = [];

        /**
         * @return App|null
        */

		static public function app(){			
			if( ! is_object( self::$_app ) && ! ( self::$_app instanceOf Core\App ) ){
				throw new \Exception( \Sy::t( 'Create application' ) );
				return false;
			}			
			return self::$_app;			
		}
		
		static public function newWebApplication( Core\Config $config ){
			self::$startTime = getMicroTime();
			if( empty( self::$_app ) ){
				self::$_app = new App\Web( $config );
			}
		}

		static public function newCliApplication( Core\Config $config, $args = array() ) {

		}

		static public function createConfig( $config_file ){
			self::$_conf = new Core\Config( $config_file );
			return self::$_conf;
		}
		
		static public function cfg( $key = null ){
            if( empty( $key ) ) {
                return self::$_conf;
            } else {
                return self::$_conf->get( $key );
            }
		}

        static public function setAlias( $alias, $replacement = null ) {
            static::$_aliases[$alias] = $replacement;
        }

        static public function getAlias( $value = null ) {
            return str_replace(
                array_keys( static::$_aliases ),
                array_values( static::$_aliases ),
                $value
            );
        }
	
		static public function t( $text ){
			return $text;
		}

		static public function env(){
			return self::$_app->environment;
		}

		static public function poweredBy(){
			return SY_NAME . ' ' . SY_VERVION;
		}

		static public function getTimeDiff() {
			return round( getMicroTime() - self::$startTime, 4 );
		}

		static public function getMemoryUse() {
			$memory = memory_get_usage();
			return round( $memory / 1024, 4 );
		}

	}