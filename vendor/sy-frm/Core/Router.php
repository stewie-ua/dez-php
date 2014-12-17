<?php
	
	namespace Sy\Core;

    use Sy\Error\Exception,
        Sy\Utils,
        Sy\Core\Router\Result as ResultWrapper;

	class Router extends Object {

        use SingletonTrait;

		private
            $routes		    = [],
            $cacheDirectory	= __DIR__,
            $cacheFile      = null,
            $resultStack    = [ 'forceRun' => [], 'lastRoute' => [] ];
		
		protected function init( $routesXml = 'routes.xml', $cacheDirectory = __DIR__, $debug = false ) {

			if( Utils\File::fileExists( $routesXml ) ){

				$this->cacheDirectory	    = $cacheDirectory;
				$cacheFile			        = $cacheDirectory . DIRECTORY_SEPARATOR . basename( $routesXml ) . '.php';
                $this->cacheFile            = $cacheFile;

				if( Utils\File::fileExists( $this->cacheFile ) && $debug == false ){
					$this->routes       = include $cacheFile;
				} else {
					$xml			= simplexml_load_file( $routesXml );
					$this->routes	= $this->_buildRoutes( $xml );
					$this->_cache( $cacheFile );
				}

			} else {
				throw new Exception\RuntimeError( 'File not found: '. $routesXml );
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
			$parts 	    = array_filter( explode( '/', trim( $url, '/' ) ), 'strlen' );
			$result     = $this->_find( $parts, $this->routes['routes'], $method );
			return $result;
		}

        public function getCacheFile() {
            return file_exists( $this->cacheFile )
                ? $this->cacheFile
                : false;
        }
		
		private function _indexPage(){
			$page	= 'index';
			$route	= array_filter( $this->routes['system'], function( $route ) use ( $page ) {
				return ( $route['match'] == $page );
			}); 
			$route = current( $route );
			return [
				'controller'	=> $route['controller'],
				'action'		=> $route['action'],
				'values'		=> []
			];
		}
		
		private function _page404(){
			$page = 'error404';
			$route	= array_filter( $this->routes['system'], function( $route ) use ( $page ) {
				return ( $route['match'] == $page );
			}); 
			$route = current( $route );
			return [
				'controller'	=> $route['controller'],
				'action'		=> $route['action'],
				'values'		=> []
			];
		}

		private function _find( array $parts = [], $routes, $method = 'GET' ) {
			while( $segment = array_shift( $parts ) ){
				foreach( $routes as $route ) {
                    $methodSuccess = (
                        ! isset( $route['method'] )
                        || strtoupper( $route['method'] ) == strtoupper( $method )
                    );
                    $matches    = [];
                    if( $methodSuccess && ( $route['match'] == $segment
                            || ( isset( $route['regexp'] ) && preg_match( $route['regexp'], $segment, $matches ) ) )
                    ) {
                        $this->_handleResult( $route, $matches );
                        if( isset( $route['children'] ) && count( $route['children'] ) > 0 ) {
                            $routes = & $route['children']; break;
                        }
                    }
				}
			}

            if( 0 >= ( count( $this->resultStack['forceRun'] ) + count( $this->resultStack['lastRoute'] ) ) ) {
                $this->_handleResult( ( count( $parts ) > 0 ? $this->_page404() : $this->_indexPage() ) );
            }

            $resultStack        = $this->resultStack;

            $this->resultStack  = [ 'forceRun' => [], 'lastRoute' => [] ];

            $resultStack        = array_merge( $resultStack['forceRun'], [ $resultStack['lastRoute'] ] );
            $resultStack        = array_filter( $resultStack, 'count' );

			return $resultStack;
		}

        private function _handleResult( array $result = [], array $matches = [] ) {
            $prepareResult = [
                'controller'    => strtolower( $result['controller'] ),
                'action'        => strtolower( $result['action'] ),
                'method'        => isset( $result['method'] )       ? strtoupper( $result['method'] )   : 'GET',
                'forceRun'      => isset( $result['force-run'] )    ? $result['force-run'] > 0          : false,
                'module'        => isset( $result['module'] )       ? strtolower( $result['module'] )   : false,
                'params'        => isset( $result['values'] )       ? $result['values']                 : [],
            ];

            if( count( $matches ) > 0 ) {
                foreach( $matches as $i => $finded ){
                    if( isset( $result['keys'][$i-1] ) ){
                        $prepareResult['params'][$result['keys'][$i-1]] = $finded;
                    }
                }
            }

            $this->_collectResult( $prepareResult );
        }

        private function _collectResult( array $prepareResult = [] ) {
            $wrapResult = ResultWrapper::instance( $prepareResult );
            if( $wrapResult->forceRun ) {
                $this->resultStack['forceRun'][]    = $wrapResult;
            } else {
                $this->resultStack['lastRoute']     = $wrapResult;
            }
        }
		
		private function _cache( $cache_file ) {
            // @TODO Use system cache!!!111
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

			if( empty( $nodes ) ) return false;

			$params			= [];

			foreach( $nodes->children() as $node ){

				$attributes = [];

				foreach( $node->attributes() as $name => $value ){
					$attributes[strtolower( $name )] = (string) $value;
				}

				if( strpos( $attributes['match'], '{' ) !== false ){					
					$attributes['keys']		= [];
					$this->_createRegexp( $attributes );
				}

				if( $node->count() > 0 )
                    $attributes['children'] = $this->_buildArray( $node );

				$params[] = $attributes;
			}

			return $params;			
		}
		
		private function _createRegexp( & $attributes ){

			$type2rexexp	= [
				'num'	=> '(\d+)',
				'str'	=> '(\w+)'
			];

			$attributes['regexp']	= preg_replace_callback( '/\{([-_\w]+)\|?(num|str)?\}/Uuis', function( $match ) use ( & $attributes, $type2rexexp ) {
				$keys 	= & $attributes['keys'];			
				$keys[] = $match[1];					
				return isset( $match[2] ) ? $type2rexexp[$match[2]] : '(.+)';						
			}, $attributes['match'] );

			$attributes['regexp']	= '/^'. $attributes['regexp'] .'$/ui';	
		}
		
		
		
	}