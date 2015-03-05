<?php

    namespace Dez\ORM\Collection;

    class ModelCollection extends Collection {

        protected
            $keyName = 'id';

        public function setKeyName( $keyName = null ) {
            $this->keyName  = $keyName;
        }

        public function getKeyName() {
            return $this->keyName;
        }

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
                $dictionary[ $item->{ $this->getKeyName() } ] = $item;
            }
            return $dictionary;
        }

        public function save() {
            $this->each( function( $item ) { $item->save(); } );
        }

        public function toArray() {
            $items = $this->items;
            foreach( $items as & $item ) {
                $item = $item->toArray();
            }
            return $items;
        }

        public function toJSON() {
            return json_encode( $this->toArray() );
        }

    }