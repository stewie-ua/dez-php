<?php

    namespace Dez\ORM\Relation;

    use Dez\ORM\Model\Table as TableModel;

    class HasMany extends Relation {

        /**
         * @param   TableModel $model
         * @param   TableModel $relatedModelName
         * @param   string $foreignKey
        */

        protected function init( $model, $relatedModelName, $foreignKey ) {
            $ids                = ! $model->getCollection() ? [ $this->id() ] : $model->getCollection()->getIDs();
            $collection         = $relatedModelName::query()->where( $foreignKey, $ids )->find();
            $this->collection   = $collection->findAll( function( $item ) use ( $foreignKey, $model ) {
                $id     = $foreignKey == $item->pk() ? $item->id() : $item->get( $foreignKey ) ;
                return $model->id() == $id;
            } );
        }

    }