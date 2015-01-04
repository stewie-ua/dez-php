<?php

    /**
     * @package DezAdmin
     * @author Ivan Gontarenko
     * @link https://vk.com/id.undefined
     * @version 1.0
    */

    $moduleName     = Dez::app()->action->getWrapperRoute()->getModuleName();

    Dez::setAlias( '@dezAdminCss',      "@media/{$moduleName}/css" );
    Dez::setAlias( '@dezAdminJs',       "@media/{$moduleName}/js" );
    Dez::setAlias( '@dezAdminModule',   "@modules/{$moduleName}" );

    Dez::setAlias( '@dezAdminModels',   "@dezAdminModule/model" );

    Dez::app()->layout
        ->setPath( \Dez::getAlias( '@dezAdminModule/view' ) )
        ->setTitle( 'dezAdmin' );

    function adminUrl( $page = 'index:index', array $queryParams = [], $method = 'get' ) {
        $params = explode( ':', $page );
        return url( Dez\Core\UrlBuilder::c( 'index:process', $params, $method ), $queryParams );
    }
