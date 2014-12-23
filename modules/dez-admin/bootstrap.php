<?php

    /**
     * @package DezAdmin
     * @author Ivan Gontarenko
     * @link https://vk.com/id.undefined
     * @version 1.0
    */

    $moduleName     = Dez::app()->action->getWrapperRoute()->getModuleName();

    Dez::setAlias( '@dezAdminCss',  "@media/{$moduleName}/css" );
    Dez::setAlias( '@dezAdminJs',   "@media/{$moduleName}/js" );

    Dez::app()->layout->js( '@dezAdminJs/site.js' )->setTitle( 'dezAdmin' );