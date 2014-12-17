<?php

    namespace Sy\Core;

    use Sy\Error\Exception,
        Sy\Utils\HTML;

    class Object {

        use ObjectTrait;

        public function __set( $name, $value ) {
            $setter = $this->setterName( $name );
            $getter = $this->getterName( $name );

            if( $this->hasMethod( $setter ) ) {
                $this->$setter( $value );
            } else if ( $this->hasMethod( $getter ) ) {
                throw new Exception\InvalidCall( $this->getClassName() .'::$'. $name . HTML::tag( 'b', ' Setting read-only property' ) );
            } else {
                throw new Exception\InvalidCall( $this->getClassName() .'::$'. $name . HTML::tag( 'b', ' Setting undefined property' ) );
            }
        }

        public function __get( $name ) {
            $setter = $this->setterName( $name );
            $getter = $this->getterName( $name );

            if( $this->hasMethod( $getter ) ) {
                return $this->$getter();
            } else if ( $this->hasMethod( $setter ) ) {
                throw new Exception\InvalidCall( $this->getClassName() .'::$'. $name . HTML::tag( 'b', ' Getting write-only property' ) );
            } else {
                throw new Exception\InvalidCall( $this->getClassName() .'::$'. $name . HTML::tag( 'b', ' Getting undefined property' ) );
            }
        }

        public function __isset( $name ) {
            $getter = $this->getterName( $name );
            return $this->hasMethod( $getter );
        }

        public function __unset( $name ) {
            $setter = $this->setterName( $name );
            $getter = $this->getterName( $name );

            if( $this->hasMethod( $setter ) ) {
                $this->$setter( null );
            } else if( $this->hasMethod( $getter ) ) {
                throw new Exception\InvalidCall( $this->getClassName() .'::$'. $name . HTML::tag( 'b', ' Unsetting read-only property' ) );
            }
        }

        public function __call( $name, $args ) {
            throw new Exception\InvalidCall( $this->getClassName() .'::'. $name .'()'. HTML::tag( 'b', ' Calling unknown method' ) );
        }

        static public function __callStatic( $name, $args ) {
            throw new Exception\InvalidCall( get_called_class() . '::' . $name .'()'. HTML::tag( 'b', ' Statically call unknown method' ) );
        }

        public function __toString() {
            return $this->getClassName();
        }

    }