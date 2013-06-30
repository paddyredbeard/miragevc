<?php

ini_set( 'include_path', "./lib:" . ini_get( 'include_path' )) ;

if( !defined( 'APPLICATION_LOGIN_RESOURCE' )) define( 'APPLICATION_LOGIN_RESOURCE', '/' ) ;
if( !defined( 'APPLICATION_SESSION_NAME' )) define( 'APPLICATION_SESSION_NAME', 'MIRAGEVC' ) ;
if( !defined( 'AUTH_USER_KEY' )) define( 'AUTH_USER_KEY', null ) ;

?>
