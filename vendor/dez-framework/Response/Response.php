<?php

    namespace Dez\Response;

    use Dez\Core\Object,
        Dez\Core\SingletonTrait,
        Dez\Error\Exception,
        Dez\Response\Format,
        Dez\Cookie\Cookie;

    class Response extends Object {

        use SingletonTrait;

        const
            RESPONSE_JSON       = 'json',
            RESPONSE_HTML       = 'html',
            RESPONSE_API_JSON   = 'api_json';

        protected
            $format     = self::RESPONSE_HTML,
            $code       = 200,
            $headers    = [],
            $body       = null;

        protected function init() {}

        public function setFormat( $format = self::RESPONSE_HTML ) {
            if( ! in_array( strtolower( $format ), [ self::RESPONSE_HTML, self::RESPONSE_JSON, self::RESPONSE_API_JSON ] ) ) {
                throw new Exception\InvalidArgs( 'Setting bad response format' );
            }
            $this->format = $format;
            return $this;
        }

        public function getFormat() {
            return $this->format;
        }

        public function setCode( $code = 200 ) {
            $this->code = (int) $code;
            return $this;
        }

        public function getCode() {
            return $this->code;
        }

        public function setHeader( $key, $value, $replace = true ) {
            $this->headers[$key]    = [ [ $value, $replace ] ];
            return $this;
        }

        public function addHeader( $key, $value ) {
            if( $this->hasHeader( $key ) ) {
                $this->headers[$key][]    = [ $value, false ];
            } else {
                $this->setHeader( $key, $value, false );
            }
            return $this;
        }

        public function hasHeader( $key ) {
            return isset( $this->headers[$key] );
        }

        public function getHeader( $key ) {
            return $this->hasHeader( $key ) ? $this->headers[$key] : '';
        }

        public function getHeaders() {
            return $this->headers;
        }

        public function setBody( $body ) {
            $this->body = $body;
            return $this;
        }

        public function getBody() {
            return $this->body;
        }

        public function send() {
            if( $this->getFormat() == self::RESPONSE_HTML ) {
                $formatter = Format\Html::instance( $this );
            } else if( $this->getFormat() == self::RESPONSE_JSON ) {
                $formatter = Format\Json::instance( $this );
            } else if( $this->getFormat() == self::RESPONSE_API_JSON ) {
                $formatter = Format\ApiJson::instance( $this );
            } else {
                throw new Exception\RuntimeError( 'Response cannot be possible because have bad format' );
            }

            $formatter->process();

            $this->sendHeaders();
            $this->sendBody();
        }

        protected function sendHeaders() {
            Cookie::instance()->sendCookies();
            http_response_code( $this->getCode() );
            foreach( $this->getHeaders() as $name => $data ) {
                foreach( $data as $value ) {
                    header( $name .': '. $value[0], $value[1] );
                }
            }
        }

        protected function sendBody() {
            print $this->getBody();
        }

    }