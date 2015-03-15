<?php

    namespace Dez\ORM\Relation;

    class HasMany extends Relation {

        protected function makeRelation() {

            $ids                = ! $this->model->getCollection()
                ? [ $this->model->id() ]
                : $this->model->getCollection()->getIDs();
            $related            = $this->related;
            dump($related::query()->where( $this->foreignKey, $ids ));
            $this->collection   = $related::query()->where( $this->foreignKey, $ids )->find();

        }

    }