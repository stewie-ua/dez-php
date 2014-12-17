<?php

    namespace Dez\Core;

    class Server {

        use SingletonTrait;

        protected
            $schema             = null,
            $host               = null,
            $port               = null,
            $requestUri         = null,
            $realPath           = null,
            $queryString        = null,
            $scriptDirectory    = null;

        protected function init() {
            $request                = Request::instance();
            $this->schema           = $request->server( 'request_scheme', 'http' ) . '://';
            $this->host             = $request->http( 'host', $request->server( 'server_addr', '127.0.0.1' ) );
            $this->port             = $request->server( 'server_port', 80 );
            $this->queryString      = parse_url( $request->server( 'request_uri' ), PHP_URL_QUERY );
            $this->requestUri       = parse_url( $request->server( 'request_uri' ), PHP_URL_PATH );
            $this->scriptDirectory  = dirname( $request->server( 'script_name', '' ) );
            $this->realPath         = str_replace( $this->scriptDirectory, '', $this->requestUri );
        }

        public function getSchema() {
            return $this->schema;
        }

        public function getHost() {
            return $this->host;
        }

        public function getPort() {
            return $this->port;
        }

        public function getRequestUri() {
            return $this->requestUri;
        }

        public function getRealPath() {
            return $this->realPath;
        }

        public function getScriptDirectory() {
            return $this->scriptDirectory;
        }

        public function getQueryString() {
            return $this->queryString;
        }

        static public function schema() {
            return static::instance()->getSchema();
        }

        static public function host() {
            return static::instance()->getHost();
        }

        static public function port() {
            return static::instance()->getPort();
        }

        static public function requestUri() {
            return static::instance()->getRequestUri();
        }

        static public function realPath() {
            return static::instance()->getRealPath();
        }

        static public function scriptDirectory() {
            return static::instance()->getScriptDirectory();
        }

        static public function queryString() {
            return static::instance()->getQueryString();
        }

        static public function currentURL() {
            return implode( [
                static::schema(),
                static::host(),
                static::scriptDirectory(),
                static::realPath(),
                static::queryString() != '' ? '?'. Server::queryString() : null
            ] );
        }

    }