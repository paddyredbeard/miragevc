<?php

/**
 * MirageVC.php
 *
 * @package	MirageVC
 * @author	Patrick Barabe
 * @copyright	Copyright &copy; 2007 Patrick Barabe
 * @license	http://creativecommons.org/licenses/GPL/2.0/ GNU Public License
 * @filesource
 *
 */


@include_once( 'myConfig.php' ) ;
require_once( 'mvcConfig.php' ) ;

#################################################################################
# Constants and Settings
#################################################################################

/////////////////////////////////////////////////////////////////////////////////
// The following constants MUST be defined in the application's config.php
////////////////////////////////////////
$configErrors = array() ;

if( !defined( 'APPLICATION_BASE_DIR' )) $configErrors[] = "Setting APPLICATION_BASE_DIR not set." ;
if( !defined( 'APPLICATION_BASE_URI' )) $configErrors[] = "Setting APPLICATION_BASE_URI not set." ;
if( !defined( 'APPLICATION_LOGIN_RESOURCE' )) $configErrors[] = "Setting APPLICATION_LOGIN_RESOURCE not set." ;
if( !defined( 'AUTH_USER_KEY' )) $configErrors[] = "Setting AUTH_USER_KEY not set." ;

if( !empty( $configErrors )) {
	showDebug( array( "The following configuration errors were encountered" => $configErrors )) ;
}



/////////////////////////////////////////////////////////////////////////////////
// The following constants are OPTIONALLY defined in the application's mvcConfig.php
// Default values are set here.
////////////////////////////////////////
if( !defined( 'SHOW_DEBUG' )) define( 'SHOW_DEBUG', false ) ;

if( !defined( 'APPLICATION_INDEX_URI' )) define( 'APPLICATION_INDEX_URI', '/index.php' ) ;
if( !defined( 'DEFAULT_URI_RESOURCE' )) define( 'DEFAULT_URI_RESOURCE', 'default' ) ;
if( !defined( 'APPLICATION_URI' )) define( 'APPLICATION_URI', APPLICATION_BASE_URI.APPLICATION_INDEX_URI ) ;

if( !defined( 'SHOWDEBUG_CONTENT_HEADER' )) define( 'SHOWDEBUG_CONTENT_HEADER', 'Content-type: text/plain' ) ;

if( !defined( 'CONTROLLERS_DIR' )) define( 'CONTROLLERS_DIR', 'controller' ) ;
if( !defined( 'VIEWS_DIR' )) define( 'VIEWS_DIR', 'view' ) ;
if( !defined( 'PRESENTATION_RESOURCES_DIR' )) define( 'PRESENTATION_RESOURCES_DIR', 'includes' ) ;

if( !defined( 'APPLICATION_OS' )) define( 'APPLICATION_OS', 'unix' ) ;

if( !defined( 'DB_DATATYPE_STRING_BASIC' )) define( 'DB_DATATYPE_STRING_BASIC', 'string' ) ;
if( !defined( 'DB_DATATYPE_STRING_EMAIL' )) define( 'DB_DATATYPE_STRING_EMAIL', 'email' ) ;
if( !defined( 'DB_DATATYPE_KEY' )) define( 'DB_DATATYPE_KEY', 'key' ) ;
if( !defined( 'DB_DATATYPE_INTEGER' )) define( 'DB_DATATYPE_INTEGER', 'integer' ) ;
if( !defined( 'DB_DATATYPE_FLOAT' )) define( 'DB_DATATYPE_FLOAT', 'float' ) ;


/////////////////////////////////////////////////////////////////////////////////
// setup include_path
////////////////////////////////////////
switch( APPLICATION_OS ) {
	case 'unix' :
		ini_set( 'include_path', ini_get( 'include_path' ).":".dirname(__FILE__)."/lib" ) ;
		if( !defined( 'NULL_FILE' )) { define ( 'NULL_FILE', '/dev/null' ) ; }
		break ;

	case 'windows' :
		ini_set( 'include_path', ini_get( 'include_path' ).";".dirname(__FILE__)."\\lib" ) ;
		if( !defined( 'NULL_FILE' )) { define ( 'NULL_FILE', 'NUL' ) ; }
		break ;

	default :
		die( "APPLICATION_OS is not properly defined as 'windows' or 'unix'." ) ;
		break ;
}// end switch



/////////////////////////////////////////////////////////////////////////////////
// setup error_reporting
////////////////////////////////////////
if( SHOW_DEBUG ) {
	ini_set( 'error_reporting', E_ALL ) ;
	ini_set( 'display_errors', 1 ) ;
} else {
	ini_set( 'display_errors', 0 ) ;
}





#################################################################################
# Functions
#################################################################################

/**
 * showDebug
 */
function showDebug( $arg, $die=true ) {

	if( !headers_sent() ) {
		//header( SHOWDEBUG_CONTENT_HEADER ) ;
		header( "Content-type: text/plain" ) ;
		print_r( $arg ) ;
	} else {
		print "<pre>" ;
		print_r( $arg ) ;
		print "</pre>" ;
	}


	if( $die ) {
		die() ;
	}

}// end showDebug


/**
 * __autoload
 */
function __autoload($class) {
	$file = str_replace('_','/',$class.'.php');

	if( SHOW_DEBUG ) {
		include_once( $file ) ;
	} else {
		@include_once( $file ) ;
	}
}// end __autoload


/**
 * getFiller
 */
function getFiller() {
	$loremIpsum = "Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi." ;

	return $loremIpsum ;
}





#################################################################################
# A little main logic
#################################################################################

/////////////////////////////////////////////////////////////////////////////////
// for PHP4, include lib4 files; die if < PHP4
////////////////////////////////////////
if( version_compare( PHP_VERSION, "5.0.0" ) < 0 ) {
	require_once( "MirageVC4.php" ) ;

} elseif( version_compare( PHP_VERSION, "4.0.0" ) < 0 ) {
	showDebug( "MirageVC only works with PHP version 4+" ) ;


} else {// PHP5
	/////////////////////////////////////////////////////////////////////////////////
	// figure out the requested page
	////////////////////////////////////////
	$requestedResources = UriResource::getRequestedResources() ;
	$requestedPage = $requestedResources['page'] ;
	$requestedClass = CONTROLLERS_DIR . "_" . $requestedPage ;
	$pageObject = new $requestedClass() ;

	@session_start() ;
	if( $pageObject->authenticated( AUTH_USER_KEY )) {
		$pageObject->doAction() ;
	} else {
		//$_SESSION['destination_uri'] = $requestedPage ;
		$_SESSION['destination_uri'] = $_SERVER['REQUEST_URI'] ;
		header( "Location: ".APPLICATION_URI.APPLICATION_LOGIN_RESOURCE ) ;
	}
}

?>
