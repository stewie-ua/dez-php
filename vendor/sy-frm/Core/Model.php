<?php

	namespace Sy\Core;

	class Model {

        protected
            $db         = null,
            $request    = null;

		public function __construct(  ) {
            $this->db       = \Sy::app()->db;
            $this->request  = \Sy::app()->request;
		}

	}