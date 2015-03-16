<?php

    namespace DB;

    use Dez\ORM\Model\Table;

    class Session extends Table {
        static protected $table = 'system_auth_sessions';
    }