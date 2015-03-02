<?php

    namespace Dez\ORM\Collection;

    class ModelCollection extends Collection {

        public function add( $item ) {
            $this->validateItem( $item );
            $this->items[]  = $item;
        }

        public function getIDs() {
            return array_keys( $this->getDictionary() );
        }

        public function getByID( $id ) {
            $dictionary = $this->getDictionary();
            return isset( $dictionary[ $id ] ) ? $dictionary[ $id ] : null;
        }

        public function getDictionary() {
            $dictionary = [];
            foreach( $this->items as $item ) {
                $dictionary[ $item->id() ] = $item;
            }
            return $dictionary;
        }

    }