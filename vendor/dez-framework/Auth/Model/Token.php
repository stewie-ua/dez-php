<?php

    namespace Dez\Auth\Model;

    use Dez\ORM\Model\QueryBuilder;
    use Dez\ORM\Model\Table;

    class Token extends Table {

        static protected $table = 'system_auth_tokens';

    }