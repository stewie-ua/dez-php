<?php

    namespace DB;

    use \Dez\ORM\Entity,
        \Dez\Utils\NumConv;

    class Url extends Entity\Table {
        static protected
            $tableName      = 'short_urls',
            $rowClass       = '\DB\UrlRow';
    }

    class UrlRow extends Entity\Row {
        public function xid() {
            return NumConv::instance()->encode( $this->id() );
        }
    }