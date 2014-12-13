<?php
	
	namespace Sy\Core;

	class Router{
		
		private $routes		= array(),
				$cache_dir	= __DIR__,
                $cache_file = null;
		
		public function __construct( $routes_xml = 'routes.xml', $cache_dir = __DIR__, $debug = false ){
			if( file_exists( $routes_xml ) ){
				$this->cache_dir	= $cache_dir;
				$cache_file			= $cache_dir . DIRECTORY_SEPARATOR . basename( $routes_xml ) . '.php';
                $this->cache_file   = $cache_file;
				if( file_exists( $cache_file ) && $debug === false ){
					$this->routes       = include $cache_file;
				} else {
					$xml			= simplexml_load_file( $routes_xml );
					$this->routes	= $this->_buildRoutes( $xml );
					$this->_cache( $cache_file );
				}
			} else {
				throw new \Exception( 'File not found: '. $routes_xml );
			}
		}

        public function get( $url ) {
            return $this->getResult( $url, 'GET' );
        }

        public function post( $url ) {
            return $this->getResult( $url, 'POST' );
        }

        public function delete( $url ) {
            return $this->getResult( $url, 'DELETE' );
        }

        public function put( $url ) {
            return $this->getResult( $url, 'PUT' );
        }

		public function getResult( $url, $method = 'GET' ) {
			$url 	= explode( '/', trim( $url, '/' ) );
			$url 	= array_filter( $url, 'strlen' );
			$result = $this->_find( $url, $this->routes['routes'], $method );

			if( empty( $result ) ){
				if( empty( $url ) ){
					return $this->_indexPage();
				}else{
					return $this->_page404();
				}
			}

			return $result;
		}
		
		public function addRule( $match = null, $controller = null, $action = null ){			
			if( empty( $match ) || empty( $controller ) || empty( $action ) ){
				throw new \Exception( 'Empty parameters' );
			}
			$match 				= trim( $match, '/' );	
			$chunks				= explode( '/', $match );			
			if( empty( $chunks ) ){
				throw new \Exception( 'Not correctly rule' );
			}
			array_push( $this->routes['routes'], $this->_getRoutes( $chunks, $controller, $action ) );
		}

        public function getCacheFile() {
            return file_exists( $this->cache_file )
                ? $this->cache_file
                : false;
        }

		private function _getRoutes( $chunks, $controller, $action ){
			$segment 					= array_shift( $chunks );
			$attributes 				= array( 'keys' => array() );				
			$attributes['match'] 		= $segment;
			$attributes['controller'] 	= $controller;
			$attributes['action'] 		= $action;
			if( strpos( $attributes['match'], '{' ) !== false ){							
				$this->_createRegexp( $attributes );
			}
			if( sizeOf( $chunks ) <> 0 ){
				$attributes['children'] = array( $this->_getRoutes( $chunks, $controller, $action ) );
			}			
			return $attributes;
		}
		
		private function _indexPage(){
			$page	= 'index';
			$route	= array_filter( $this->routes['system'], function( $route ) use ( $page ) {
				return ( $route['match'] == $page );
			}); 
			$route = current( $route );
			return array(
				'controller'	=> $route['controller'],
				'action'		=> $route['action'],
				'values'		=> array()
			);
		}
		
		private function _page404(){
			$page = 'error404';
			$route	= array_filter( $this->routes['system'], function( $route ) use ( $page ) {
				return ( $route['match'] == $page );
			}); 
			$route = current( $route );
			return array(
				'controller'	=> $route['controller'],
				'action'		=> $route['action'],
				'values'		=> array()
			);
		}

		private function _find( $url, $routes, $method = 'GET' ){
			$result = array();
			while( $segment = array_shift( $url ) ){				
				foreach( $routes as $route ) {
                    $methodSuccess = (
                        ! isset( $route['method'] )
                        || strtoupper( $route['method'] ) == strtoupper( $method )
                    );
					if( ! isset( $route['regexp'] ) ){						
						if( $route['match'] == $segment && $methodSuccess == true ) {
							$result['controller']	= $route['controller'];
							$result['action']		= $route['action'];														
							if( ! isset( $result['values'] ) ){
								$result['values'] = array();
							}
							if( isset( $route['children'] ) && ! empty( $route['children'] ) ){
								$routes = & $route['children']; break;
							}
						}
					}else{
						if( preg_match( $route['regexp'], $segment, $matches ) && $methodSuccess == true ) {
							$result['controller']	= $route['controller'];
							$result['action']		= $route['action'];							
							if( ! isset( $result['values'] ) ){
								$result['values'] = array();
							}							
							foreach( $matches as $i => $finded ){
								if( isset( $route['keys'][$i-1] ) ){
									$result['values'][$route['keys'][$i-1]] = $finded;
								}
							}							
							if( isset( $route['children'] ) && ! empty( $route['children'] ) ){
								$routes = & $route['children']; break;
							}
						}
					}
				}
			}

			return $result;			
		}
		
		private function _cache( $cache_file ){			
			$dir_name = dirname( $cache_file );					
			if( ! is_dir( $dir_name ) ){
				mkdir( $dir_name, 0777, true );
			}						
			$php = '<?php' . "\n" . 'return '. var_export( $this->routes, true ) . ';';			
			return @ file_put_contents( $cache_file, $php );			
		}
		
		private function _buildRoutes( $xml ){
			$system			= $xml->xpath( '/root/system' );
			$routes			= $xml->xpath( '/root/routes' );			
			return array(
				'routes'	=> $this->_buildArray( @ $routes[0] ),
				'system'	=> $this->_buildArray( @ $system[0] )
			);			
		}
		
		private function _buildArray( $nodes ){					
			if( empty( $nodes ) ){
				return false;
			}
			$params			= array();			
			foreach( $nodes->children() as $node ){				
				$attributes = array();					
				foreach( $node->attributes() as $name => $value ){
					$attributes[strtolower( $name )] = (string) $value;
				}					
				if( strpos( $attributes['match'], '{' ) !== false ){					
					$attributes['keys']		= array();				
					$this->_createRegexp( $attributes );
				}	
				if( $node->count() > 0 ){
					$attributes['children'] = $this->_buildArray( $node );
				}					
				$params[] = $attributes;
			}			
			return $params;			
		}
		
		private function _createRegexp( & $attributes ){
			$type2rexexp	= array(
				'num'	=> '(\d+)',
				'str'	=> '(\w+)'
			);
			$attributes['regexp']	= preg_replace_callback( '/\{([-_\w]+)\|?(num|str)?\}/Uuis', function( $match ) use ( & $attributes, $type2rexexp ) {
				$keys 	= & $attributes['keys'];			
				$keys[] = $match[1];					
				return isset( $match[2] ) ? $type2rexexp[$match[2]] : '(.+)';						
			}, $attributes['match'] );
			$attributes['regexp']	= '/^'. $attributes['regexp'] .'$/ui';	
		}
		
		
		
	}