<?php

    namespace Dez\Web\Asset;

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
            return "\n<!-- dez [CSS:START] -->\n" . join( "\n", $output ) . "\n<!-- dez [CSS:END] -->";
        }

        protected function renderJs() {
            $output = [];
            if( ! empty( static::$stack['js'] ) ) {
                foreach( static::$stack['js'] as $js ) {
                    $output[] = HTML::script( \Dez::getAlias( $js ) );
                }
            }
            return "\n<!-- dez [JS:START] -->\n" . join( "\n", $output ) . "\n<!-- dez [JS:END] -->";
        }

    }