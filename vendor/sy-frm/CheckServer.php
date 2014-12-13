<?php

	if( version_compare( PHP_VERSION, '5.4' ) <= 0 ){
		die( '<span style="font-family: courier new;">Requires a newer version of <b>PHP</b><br />Current version ['. PHP_VERSION .']</span>' );
	}
	
	if( ! function_exists( 'curl_init' ) ){
		die( '<span style="font-family: courier new;">cURL not allowed</span>' );
	}