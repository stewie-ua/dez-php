<?php

    namespace Sy\ORM\Query\Func;

    use \Sy\ORM\Query;

    class Min extends Query\Func {
        protected function _createExpression() {
            $this->expression   = 'MIN('. $this->tableName .'.'. $this->columnName .')';
            return $this;
        }
    }