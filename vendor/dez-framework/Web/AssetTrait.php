<?php

    namespace Dez\Web;

    use Dez\Utils\HTML;

    trait AssetTrait {

        static protected
            $stack  = [ 'css' => [], 'js' => [] ];

        protected function renderCss() {
            $output = [];
            if( ! empty( static::$stack['css'] ) ) {
                foreach( static::$stack['css'] as $css ) {
                    $output[] = HTML::link( \Dez::getAlias( $css ), 'all' );
                }
            }
            return join( "\n", $output );
        }

    }