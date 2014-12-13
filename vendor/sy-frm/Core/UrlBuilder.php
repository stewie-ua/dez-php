<?php

    namespace Sy\Core;

    class UrlBuilder {

        use SingletonTrait;

        protected
            $routes         = [],

            $controller     = null,
            $action         = null,
            $params         = [],
            $method         = null,

            $path           = [];

        protected function init() {
            $cacheFile = \Sy::app()->router->getCacheFile();
            if( $cacheFile != false )
                $this->routes   = include $cacheFile;
        }

        public function setControllerName( $name = null ) {
            $this->controller = $name; return $this;
        }

        public function setActionName( $name = null ) {
            $this->action = $name; return $this;
        }

        public function setMethodName( $name = null ) {
            $this->method = $name; return $this;
        }

        public function setParams( array $params = [] ) {
            $this->params = $params; return $this;
        }

        public function create( $placeholder = null, array $params = [], $method = null ) {

            if( $placeholder != null ) {
                list( $controller, $action )
                    = explode( ':', $placeholder );
                $this->setControllerName( $controller )->setActionName( $action );
            }

            if( $method != null )
                $this->setMethodName( $method );

            if( ! empty( $params ) )
                $this->setParams( $params );

            $cacheKey   = 'UrlBuilder.Key:'
                . join( '', $params )
                . $this->controller
                . $this->action
                . $this->method;

            $cache = new Cache( $cacheKey, 86400 );

            if( $cache->check() ) {
                $this->path = $cache->get();
            } else {
                $this->_findPath( $this->routes['routes'], [], 0 );
                ! $this->path ?: $cache->write( $this->path );
            }

            return ! empty( $this->path )
                ? join( '/',  $this->path )
                : null;
        }

        static public function c( $placeholder = null, array $params = [], $method = null ) {
            return static::instance()->create( $placeholder, $params, $method );
        }

        protected function _findPath( array $routes = [], array $parent = [], $i = 0 ) {
            foreach( $routes as $route ) {
                $route['parent'] = & $parent;
                if (
                    $route['controller']    == $this->controller
                    && $route['action']     == $this->action
                    && (
                        ! isset( $route['method'] )
                        || $route['method'] == $this->method
                    )
                ) {
                    $this->_buildPath( $route )->_renderParams();
                } else if( isset( $route['children'] ) ) {
                    $this->_findPath( $route['children'], $route, $i + 1 );
                }
            }
        }

        protected function _buildPath( array $route = [] ) {
            $parts  = [];
            while( $route ) {
                $parts[] = $route['match'];
                $route = isset( $route['parent'] ) ? $route['parent'] : false;
            }
            $this->path = array_reverse( $parts );
            return $this;
        }

        protected function _renderParams() {
            $params = $this->params;
            $parts  = [];

            if( count( $this->path ) > 0 ) {
                foreach( $this->path as $part ) {
                    if( strpos( $part, '}' ) === false ) {
                        $parts[] = $part;
                    } else {
                        $parts[] = preg_replace_callback( '/\{([-_\w]+)\|?(num|str)?\}/uis',
                            function( $match ) use ( & $params ) {
                                return count( $params ) > 0
                                    ? isset( $match[2] ) && $match[2] == 'num'
                                        ? (int)     array_shift( $params )
                                        : (string)  array_shift( $params )
                                    : 0;
                            }, $part );
                    }
                }
                $this->path = $parts;
            }
        }

    }