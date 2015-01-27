<?php

    /**
     * @package Dez Tasker App
     * @author Ivan Gontarenko
     * @link https://vk.com/id.undefined
     * @version 1.0
     */

    use Dez\Response\Response;

    Dez\Autoloader::addIncludeDirs( __DIR__ . '/core' );

    Response::instance()->setFormat( Response::RESPONSE_API_JSON );