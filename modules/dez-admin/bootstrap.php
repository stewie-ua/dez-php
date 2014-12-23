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

    Dez::app()->layout
        ->setPath( \Dez::getAlias( '@dezAdminModule/view' ) )
        ->js( '@dezAdminJs/site.js' )
        ->setTitle( 'dezAdmin' );

    function adminUrl( $page = 'index:index', array $queryParams = [] ) {
        return url( Dez\Core\UrlBuilder::c( 'entry:run', explode( ':', $page ) ), $queryParams );
    }
