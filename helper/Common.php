<?php

    namespace Helper;

    use \Dez\ORM\Common\Pagi,
        \Dez\Utils\HTML;

    class Common {

        static public function pagi( Pagi $pagi, $pathTemplate = null ) {
            $htmlOutput = '<div class="sy-pagi">{links}</div>';
            $links      = [];
            if( $pagi->getNumPages() == 1 ) return null;
            for( $i = 1; $i <= $pagi->getNumPages(); $i++ ) {
                $url = ! $pathTemplate
                    ? url( null, [ 'page' => $i ] )
                    : str_replace( '{i}', $i, $pathTemplate );
                $links[] = $pagi->getCurrentPage() == $i
                    ? HTML::span( HTML::a( $url, $i ), [ 'class' => 'sy-pagi-active' ] )
                    : HTML::a( $url, $i );
            }
            return str_replace( '{links}', join( '', $links ), $htmlOutput );
        }

    }