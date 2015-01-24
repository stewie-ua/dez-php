<?php

    /**
     * @package DezAdmin
     * @author Ivan Gontarenko
     * @link https://vk.com/id.undefined
     * @version 1.0
    */

    $moduleName     = Dez::app()->action->getWrapperRoute()->getModuleName();

    Dez::setAlias( '@dezMedia',         "@web/modules/{$moduleName}/media" );

    Dez::setAlias( '@dezAdminCss',      "@dezMedia/css" );
    Dez::setAlias( '@dezAdminJs',       "@dezMedia/js" );
    Dez::setAlias( '@dezAdminModule',   "@modules/{$moduleName}" );

    Dez::setAlias( '@dezAdminModels',   "@dezAdminModule/model" );

    Dez::app()->layout
        ->setPath( \Dez::getAlias( '@dezAdminModule/view' ) )
        ->setTitle( 'dezAdmin' )
        ->css( '@dezAdminCss/dez-admin.css' )
        ->js( '@dezAdminJs/dez-admin.js' )
        ->js( '@dezAdminJs/dom.tabs.js' );

    function adminUrl( $page = 'index:index', array $queryParams = [], $method = 'get' ) {
        $params = explode( ':', $page );
        return url( Dez\Core\UrlBuilder::c( 'index:process', $params, $method ), $queryParams );
    }
