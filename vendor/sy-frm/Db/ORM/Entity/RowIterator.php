<?php

    namespace Sy\ORM\Entity;

    abstract class RowIterator implements \Iterator {

        private
            $position = 0;

        public function __construct() {
            $this->rewind();
        }

        public function current() {
            return current( $this->row );
        }

        public function next() {
            $this->position++;
            return next( $this->row );
        }

        public function key() {
            return key( $this->row );
        }

        public function valid() {
            return ( count( $this->row ) >= $this->position + 1 );
        }

        public function rewind() {
            $this->position = 0;
            reset( $this->row );
        }

    }