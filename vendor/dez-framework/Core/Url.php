<?php

    namespace Dez\Core;

    use Dez\Error\Exception;

    class Url {

        use SingletonTrait;

        const
            URL_SCHEMA_HTTP     = 1,
            URL_SCHEMA_HTTPS    = 2;

        protected
            $components = [],
            $query      = [];

        protected function init( $url = null ) {

            if( ! $url ) throw new Exception\InvalidArgs( __METHOD__ .': url is empty' );

            $components = parse_url( $url );

            $this->components['schema']             = ( isset( $components['scheme'] )
                ? $components['scheme']
                : 'http' ) .'://';

            $this->components['user']               = isset( $components['user'] )
                ? $components['user']
                : null;

            $this->components['password']           = isset( $components['pass'] )
                ? $components['pass']
                : null;

            $this->components['host']               = isset( $components['host'] )
                ? $components['host']
                : Server::host();

            $this->components['port']               = isset( $components['port'] )
                ? $components['port']
                : null;

            $this->components['script_directory']   = isset( $_SERVER['SCRIPT_NAME'] ) && $this->isLocalHost()
                    ? Server::scriptDirectory()
                    : null;

            $this->components['path']               = isset( $components['path'] )
                    ? $this->isLocalHost()
                        ? str_replace( Server::scriptDirectory(), '', $components['path'] )
                        : $components['path']
                    : null;

            $this->components['query']              = isset( $components['query'] )
                    ? $components['query']
                    : null;

            $this->components['fragment']           = isset( $components['fragment'] )
                    ? $components['fragment']
                    : null;

            ! $this->components['query']
                ? : parse_str( $this->components['query'], $this->query );

        }

        protected function build( array $components = [] ) {
            if( ! empty( $components ) ) {
                $url    = [
                    'schema'            => null,
                    'login_password'    => null,
                    'host'              => null,
                    'port'              => null,
                    'script_directory'  => null,
                    'path'              => null,
                    'query'             => null,
                    'fragment'          => null
                ];
                foreach( $components as $component ) {
                    switch( $component ) {
                        case 'schema': {
                            $url['schema']              = $this->components['schema'];
                            break;
                        }
                        case 'user': {
                            if( $this->components['user'] != null ) {
                                if( $this->components['password'] == null ) {
                                    $url['login_password']  = $this->components['user'];
                                } else {
                                    $url['login_password']  = join( ':', [
                                        $this->components['user'],
                                        $this->components['password']
                                    ] );
                                }
                                $url['login_password'] .= '@';
                            }
                        }
                        case 'host': {
                            $url['host']                = $this->components['host'];
                            break;
                        }
                        case 'port': {
                            $url['port'] = empty( $this->components['port'] )
                                ? null
                                : ':'. $this->components['port'];
                            break;
                        }
                        case 'script_directory': {
                            $url['script_directory']    = $this->components['script_directory'];
                            break;
                        }
                        case 'path': {
                            $url['path']                = $this->components['path'];
                            break;
                        }
                        case 'query': {
                            $url['query']    = ! $this->query
                                ? null
                                : '?'. urldecode( http_build_query( $this->query ) );
                            break;
                        }
                        case 'fragment': {
                            $url['fragment'] = empty( $this->components['fragment'] )
                                ? null
                                : '#'. $this->components['fragment'];
                            break;
                        }
                    }
                }
                return implode( $url );
            } else {
                throw new Exception\InvalidArgs( __METHOD__ .': components is empty' );
            }
        }

        protected function isLocalHost() {
            return in_array( $this->components['host'], [ Server::host(), 'localhost', '127.0.0.1' ] );
        }

        static public function getCurrentUrl() {
            static $url;
            if( empty( $url ) ) {
                $url = Server::currentURL();
            }
            return $url;
        }

        public function setVar( $key, $value ) {
            $this->query[$key] = $value;
            return $this;
        }

        public function setVars( array $variables = [] ) {
            foreach( $variables as $key => $value ) {
                $this->setVar( $key, $value );
            }
            return $this;
        }

        public function getHost() {
            return $this->build( [ 'schema', 'host' ] );
        }

        public function getBase() {
            return $this->build( [ 'schema', 'host', 'script_directory' ] );
        }

        public function getWebPath() {
            return $this->build( [ 'script_directory' ] );
        }

        public function getPath() {
            return $this->build( [ 'script_directory', 'path' ] );
        }

        public function getFullPath() {
            return $this->build( [ 'script_directory', 'path', 'query', 'fragment' ] );
        }

        public function getFull() {
            return $this->build( [ 'schema', 'host', 'script_directory', 'path', 'query', 'fragment' ] );
        }

        static public function host( $url = null ) {
            return static::instance( $url ? $url : static::getCurrentUrl() )->getHost();
        }

        static public function full( $url = null ) {
            return static::instance( $url ? $url : static::getCurrentUrl() )->getFull();
        }

        static public function base( $url = null ) {
            return static::instance( $url ? $url : static::getCurrentUrl() )->getBase();
        }

        static public function path( $url = null ) {
            return static::instance( $url ? $url : static::getCurrentUrl() )->getPath();
        }

        static public function web( $url = null ) {
            return static::instance( $url ? $url : static::getCurrentUrl() )->getWebPath();
        }

        static public function current( $host = false ) {
            $instance = static::instance( static::getCurrentUrl() );
            return $host ? $instance->getFull() : $instance->getFullPath();
        }

    }