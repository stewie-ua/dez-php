<?php

    namespace DB;

    use \Dez\ORM\Entity;

    class XTags extends Entity\Table {
        static protected $tableName     = 'xref_tags';
        static protected $joinTables    = array(
            'post'    => array(
                'joinColumn'    => 'post_id'
            ),
            'tag'    => array(
                'joinColumn'    => 'tag_id'
            )
        );
    }