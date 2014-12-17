<?php

    use Sy\Core;

	function dump() {
        $args = func_get_args();
		ob_start();
			call_user_func_array( 'var_dump', $args );
			$content = ob_get_contents();
		ob_clean();
		if( ! extension_loaded( 'xdebug' ) || ! in_array( strtolower( ini_get( 'html_errors' ) ), array( 'on', '1' ) ) ){
			$content = '<pre>'. $content .'</pre>';
		}
		die( $content );		
	}

    function hexColor( $data ) {
        $hexColor       = base_convert( substr( md5( $data ), 6, 6 ), 16, 10 );

        if( 0x0000CC >= ( 0x0000FF & $hexColor ) ) {
            $hexColor += 0x000022;
        }
        if( 0x00CC00 >= ( 0x00FF00 & $hexColor ) ) {
            $hexColor += 0x002200;
        }
        if( 0xCC0000 >= ( 0xFF0000 & $hexColor ) ) {
            $hexColor += 0x220000;
        }

        $hexColor = base_convert( $hexColor, 10, 16 );
        if( strlen( $hexColor ) < 6 ) {
            $hexColor .= str_repeat( 0, 6 - strlen( $hexColor ) ) . $hexColor;
        }

        return $hexColor;
    }

    function HTTPStatus( $num = 200, $returnHeader = false ) {

        static $http = array (
            100 => "HTTP/1.1 100 Continue",
            101 => "HTTP/1.1 101 Switching Protocols",
            200 => "HTTP/1.1 200 OK",
            201 => "HTTP/1.1 201 Created",
            202 => "HTTP/1.1 202 Accepted",
            203 => "HTTP/1.1 203 Non-Authoritative Information",
            204 => "HTTP/1.1 204 No Content",
            205 => "HTTP/1.1 205 Reset Content",
            206 => "HTTP/1.1 206 Partial Content",
            300 => "HTTP/1.1 300 Multiple Choices",
            301 => "HTTP/1.1 301 Moved Permanently",
            302 => "HTTP/1.1 302 Found",
            303 => "HTTP/1.1 303 See Other",
            304 => "HTTP/1.1 304 Not Modified",
            305 => "HTTP/1.1 305 Use Proxy",
            307 => "HTTP/1.1 307 Temporary Redirect",
            400 => "HTTP/1.1 400 Bad Request",
            401 => "HTTP/1.1 401 Unauthorized",
            402 => "HTTP/1.1 402 Payment Required",
            403 => "HTTP/1.1 403 Forbidden",
            404 => "HTTP/1.1 404 Not Found",
            405 => "HTTP/1.1 405 Method Not Allowed",
            406 => "HTTP/1.1 406 Not Acceptable",
            407 => "HTTP/1.1 407 Proxy Authentication Required",
            408 => "HTTP/1.1 408 Request Time-out",
            409 => "HTTP/1.1 409 Conflict",
            410 => "HTTP/1.1 410 Gone",
            411 => "HTTP/1.1 411 Length Required",
            412 => "HTTP/1.1 412 Precondition Failed",
            413 => "HTTP/1.1 413 Request Entity Too Large",
            414 => "HTTP/1.1 414 Request-URI Too Large",
            415 => "HTTP/1.1 415 Unsupported Media Type",
            416 => "HTTP/1.1 416 Requested range not satisfiable",
            417 => "HTTP/1.1 417 Expectation Failed",
            500 => "HTTP/1.1 500 Internal Server Error",
            501 => "HTTP/1.1 501 Not Implemented",
            502 => "HTTP/1.1 502 Bad Gateway",
            503 => "HTTP/1.1 503 Service Unavailable",
            504 => "HTTP/1.1 504 Gateway Time-out"
        );

        if( $returnHeader === true ) {
            return $http[$num];
        } else {
            header( $http[$num] );
        }
    }

    function getRealIP(){
        if( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) && $_SERVER['HTTP_X_FORWARDED_FOR'] != '' ) {
            $client_ip = ( !empty($_SERVER['REMOTE_ADDR']) ) ? $_SERVER['REMOTE_ADDR'] :(( !empty($_ENV['REMOTE_ADDR']) ) ? $_ENV['REMOTE_ADDR'] : "unknown" );

            $entries = explode( ',', @ $_SERVER['HTTP_X_FORWARDED_FOR'] );
            reset( $entries );
            while ( list( , $entry ) = each( $entries ) ){
                $entry = trim( $entry );
                $ip_list = array();
                if ( preg_match("/^([0-9]+.[0-9]+.[0-9]+.[0-9]+)/", $entry, $ip_list) ){
                    // http://www.faqs.org/rfcs/rfc1918.html
                    $private_ip = array(
                        '/^0./',
                        '/^127.0.0.1/',
                        '/^192.168..*/',
                        '/^172.((1[6-9])|(2[0-9])|(3[0-1]))..*/',
                        '/^10..*/');
                    $found_ip = preg_replace($private_ip, $client_ip, $ip_list[1]);
                    if ($client_ip != $found_ip){
                        $client_ip = $found_ip;
                        break;
                    }
                }
            }
        } else {
            $client_ip = ( !empty($_SERVER['REMOTE_ADDR']) ) ? $_SERVER['REMOTE_ADDR'] : ( ( !empty($_ENV['REMOTE_ADDR']) ) ? $_ENV['REMOTE_ADDR'] : "unknown" );
            if ($client_ip == 'unknown') {
                if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
                {
                    $ip=$_SERVER['HTTP_CLIENT_IP'];
                }
                elseif ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) )   //to check ip is pass from proxy
                {
                    $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
                }
                else {
                    $ip=$_SERVER['REMOTE_ADDR'];
                }
                $client_ip = $ip;
            }
        }
        return $client_ip;
    }

	function getMicroTime() {
		list( $usec, $sec ) = explode( ' ', microtime() );
		return ( (float) $usec + (float) $sec );
	}

	function callModule( $route = null ){
        $requestClone            = ( clone Sy::app()->request );
        $requestClone->get['r']  = $route;
        try {
            $output = Sy::app()->action->setRequest( $requestClone )->execute();
        } catch( Exception $e ) {
            $output = $e->getMessage();
        }
        print $output;
	}

    function url( $url = null, $vars = array(), $host = false ) {
        $url = ! $url
            ? Core\Url::current()
            : (
                strtolower( substr( $url, 0, 4 ) ) == 'http'
                    ? $url
                    : '/'. ltrim( $url, '/' )
            );

        $urlInstance = Core\Url::instance( $url );

        if( ! empty( $vars ) ) $urlInstance->setVars( $vars );

        return $host
            ? $urlInstance->getFull()
            : $urlInstance->getFullPath();
    }
