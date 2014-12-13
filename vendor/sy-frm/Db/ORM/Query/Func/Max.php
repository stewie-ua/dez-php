<?php

    namespace Sy\ORM\Query\Func;

    use \Sy\ORM\Query;

    class Max extends Query\Func {
        protected function _createExpression() {
            $this->expression   = 'MAX('. $this->tableName .'.'. $this->columnName .')';
            return $this;
        }
    }