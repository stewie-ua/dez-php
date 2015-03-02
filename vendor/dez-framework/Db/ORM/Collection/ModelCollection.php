<?php

    namespace Dez\ORM\Collection;

    use Dez\ORM\Model\Table;

    class ModelCollection extends Collection {

        protected
            $dictionary = [];

        public function add( $item ) {
            $this->validateItem( $item );
            $this->items[]                  = $item;
            $this->dictionary[$item->id()]  = & $item;
        }

        public function getIDs() {
            return array_keys( $this->dictionary );
        }

    }