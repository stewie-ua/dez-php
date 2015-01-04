<?php

    namespace Dez\Response;

    use Dez\Core\Object,
        Dez\Core\SingletonTrait,
        Dez\Error\Exception,
        Dez\Response\Format;

    class Response extends Object {

        use SingletonTrait;

        const
            RESPONSE_JSON   = 'json',
            RESPONSE_HTML   = 'html';

        protected
            $format     = self::RESPONSE_HTML,
            $code       = 200,
            $headers    = [],
            $body       = null;

        protected function init() {}

        public function setFormat( $format = self::RESPONSE_HTML ) {
            if( ! in_array( strtolower( $format ), [ self::RESPONSE_HTML, self::RESPONSE_JSON ] ) ) {
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

        public function setHeader( $key, $value ) {
            $this->headers[$key]    = [ $value ];
            return $this;
        }

        public function addHeader( $key, $value ) {
            if( $this->hasHeader( $key ) ) {
                $this->headers[$key][]    = $value;
            } else {
                $this->setHeader( $key, $value );
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
            } else {
                throw new Exception\RuntimeError( 'Response cannot be possible because have bad format' );
            }

            $formatter->process();

            $this->sendHeaders();
            $this->sendBody();
        }

        protected function sendHeaders() {
            http_response_code( $this->getCode() );
            foreach( $this->getHeaders() as $name => $value ) {
                header( $name .': '. join( ', ', $value ) );
            }
        }

        protected function sendBody() {
            print $this->getBody();
        }

    }