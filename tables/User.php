<?php

    namespace DB;

    use Dez\ORM\Model\Table;

    class User extends Table {
        static protected $table = 'system_auth';
    }