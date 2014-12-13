<?php

    namespace Sy\ORM\Entity;

    class TableIterator implements \Iterator {

        private
            $row        = array(),
            $position   = 0;

        public function current() {
            return static::rowInstance( $this->row, $this );
        }

        public function next() {
            $this->row = $this->getStmt()->loadArray();
            $this->position++;
        }

        public function key() {
            return $this->position;
        }

        public function valid() {
            return $this->row != false;
        }

        public function rewind() {
            $this->next();
            $this->position = 0;
        }

    }