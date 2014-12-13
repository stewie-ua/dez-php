<?php

    namespace Sy\Utils;

    class HTML {

        static public function tag( $tagName = null, $text = null, array $attrs = [] ) {
            return '<'. $tagName . static::buildAttrs( $attrs ) .'>'. $text .'</'. $tagName .'>';
        }

        static public function a( $href = null, $text = null, array $attrs = [] ) {
            $attrs['href']  = $href;
            return '<a'. static::buildAttrs( $attrs ) .'>'. $text .'</a>';
        }

        static public function span( $html = null, array $attrs = [] ) {
            return '<span'. static::buildAttrs( $attrs ) .'>'. $html .'</span>';
        }

        static public function select( array $data = [], $name = null, $currentValue = null, array $attrs = [] ) {
            if( ! empty( $data ) ) {
                $options = []; $attrs['name']  = $name;
                foreach( $data as $value => $text ) {
                    $optionAttr = [ 'value' => $value ];
                    if( $currentValue == $value ) $optionAttr['selected'] = 'selected';
                    $options[]  = static::option( $text, $optionAttr );
                } unset( $key, $text, $optionAttr );
                return '<select'. static::buildAttrs( $attrs ) .'>'. join( "\n", $options ) .'</select>';
            }
            return null;
        }

        static protected function option( $text = null, array $attrs = [] ) {
            return '<option'. static::buildAttrs( $attrs ) .'>'. static::entities( $text ) .'</option>';
        }

        static protected function buildAttrs( array $attrs = [] ) {
            if( ! empty( $attrs ) ) {
                $output = [];
                foreach( $attrs as $name => $value ) {
                    $output[] = $name . '="'. static::entities( $value ) .'"';
                }
                return ' '. join( ' ', $output );
            }
            return null;
        }

        static protected function entities( $string = '' ) {
            return htmlentities( $string, ENT_QUOTES );
        }

    }