<?php

    namespace Dez\Core;

    trait HasDataTrait {

        public function & get( $key = null, $default = null ) {
            $data = & $this->getData();

            if( $this->has( $key ) ) {
                $value = & $data[$key];
            } else {
                $value = $default;
            }

            return $value;
        }

        public function set( $key = null, $value = null ) {
            $data       = & $this->getData();
            $data[$key] = $value;
            return $this;
        }

        public function add( $key = null, $value = null ) {
            $data       = & $this->getData();

            if( ! $this->has( $key ) ) {
                $this->set( $key, [ $value ] );
            } else {
                $data[$key][]   = $value;
            }

            return $this;
        }

        public function & all() {
            return $this->getData();
        }

        public function has( $key = null ) {
            return array_key_exists( $key, $this->getData() );
        }

        abstract protected function & getData();

    }