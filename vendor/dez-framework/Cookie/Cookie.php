<?php

    namespace Dez\Cookie;

    use Dez\Core\Object,
        Dez\Core\SingletonTrait;
    use Dez\Response\Response;

    class Cookie extends Object {

        use SingletonTrait;

        protected
            $stack  = [];

        protected function init() {}

        public function setCookie( $key, $value, $expired = 0, $path = null, $domain = null, $secure = null, $httpOnly = false ) {
            $this->stack[$key]  = [
                $value,
                $expired,
                $path,
                $domain,
                $secure,
                $httpOnly
            ];
        }

        public function sendCookies() {
            foreach( $this->stack as $name => $cookie ) {
                $tmpCookieSet       = [];
                $tmpCookieSet[]     = rawurlencode( $name ) .'='. rawurlencode( $cookie[0] );

                if( ! empty( $cookie[1] ) )
                    $tmpCookieSet[] = 'expired='. gmdate( 'D, d-M-Y H:i:s', $cookie[1] ) .' GMT';

                if( ! empty( $cookie[2] ) )
                    $tmpCookieSet[] = 'path='. $cookie[2];

                if( ! empty( $cookie[3] ) )
                    $tmpCookieSet[] = 'domain='. $cookie[3];

                if( ! empty( $cookie[4] ) && $cookie[4] == true )
                    $tmpCookieSet[] = 'secure';

                if( ! empty( $cookie[5] ) && $cookie[5] == true )
                    $tmpCookieSet[] = 'HttpOnly';

                Response::instance()->addHeader( 'Set-Cookie', implode( '; ', $tmpCookieSet ) );
            }
        }

        static public function set( $key, $value, $expired = 0, $path = null, $domain = null, $secure = null, $httpOnly = false ) {
            static::instance()->setCookie( $key, $value, $expired, $path, $domain, $secure, $httpOnly );
        }

    }
