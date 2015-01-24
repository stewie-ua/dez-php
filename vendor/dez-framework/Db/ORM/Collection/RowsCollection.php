<?php

    namespace Dez\ORM\Collection;

    class RowsCollection extends Collection {

        private $table;

        public function setTable( $table ) {
            $this->table    = $table;
        }

        public function getTable() {
            return $this->table;
        }

        public function table() {
            return $this->getTable();
        }

        public function getPagi() {
            return $this->getTable()->getPagi();
        }

        public function pagi() {
            return $this->getPagi();
        }

    }