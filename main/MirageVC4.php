<?php


/**
 * MirageVC4.php
 *
 * @package	MirageVC
 * @subpackage	MirageVC4
 * @author	Patrick Barabe
 * @copyright	Copyright &copy; 2007 Patrick Barabe
 * @license	http://creativecommons.org/licenses/GPL/2.0/ GNU Public License
 *
 */


includeLib4() ;
includeAppModels() ;

/////////////////////////////////////////////////////////////////////////////////
// figure out the requested page
////////////////////////////////////////
$requestedResources = UriResource::getRequestedResources() ;
include( APPLICATION_BASE_DIR . "/" . CONTROLLERS_DIR . "/" . $requestedResources['include'] ) ;
include( APPLICATION_BASE_DIR . "/" . VIEWS_DIR . "/" . $requestedResources['include'] ) ;
$requestedPage = $requestedResources['page'] ;
$requestedClass = CONTROLLERS_DIR . "_" . $requestedPage ;
$pageObject = new $requestedClass() ;

@session_name( APPLICATION_SESSION_NAME ) ;
@session_start() ;
if( $pageObject->authenticated( AUTH_USER_KEY )) {
	$pageObject->doAction() ;
} else {
	//$_SESSION['destination_uri'] = $requestedPage ;
	$_SESSION['destination_uri'] = $_SERVER['REQUEST_URI'] ;
	header( "Location: ".APPLICATION_URI.APPLICATION_LOGIN_RESOURCE ) ;
}



function includeLib4() {
	require_once( "lib4/HttpRequestVars.php" ) ;
	require_once( "lib4/GetVars.php" ) ;
	require_once( "lib4/PostVars.php" ) ;
	require_once( "lib4/View.php" ) ;
	require_once( "lib4/Controller.php" ) ;
	require_once( "lib4/AuthNone.php" ) ;
	require_once( "lib4/AuthUser.php" ) ;
	require_once( "lib4/Model.php" ) ;
	require_once( "lib4/UriResource.php" ) ;
}



function includeAppModels() {
	$modelDirPath = APPLICATION_BASE_DIR."/model/" ;
	if( is_dir( $modelDirPath )) {
		if( $modelDir = openDir( $modelDirPath )) {
			while(( $nextFile = readdir( $modelDir )) !== false ) {
				if( isPhpFile( $modelDirPath.$nextFile )) {
					require_once( $modelDirPath.$nextFile ) ;
				}
			}
		}
	}
}

function isPhpFile( $aFile ) {

	$isPhp = false ;

	if( is_file( $aFile )) {
		if( filetype( $aFile ) == "file" ) {
			if( strpos( $aFile, ".php" ) == (strlen( $aFile ) - 4)) {
				$isPhp = true ;
			}
		}
	}

	return $isPhp ;
}

?>
