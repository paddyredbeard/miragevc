<?php

includeLib4() ;

/////////////////////////////////////////////////////////////////////////////////
// figure out the requested page
////////////////////////////////////////
$requestedResources = UriResource::getRequestedResources() ;
include( APPLICATION_BASE_DIR . "/" . CONTROLLERS_DIR . "/" . $requestedResources['include'] ) ;
include( APPLICATION_BASE_DIR . "/" . VIEWS_DIR . "/" . $requestedResources['include'] ) ;
$requestedPage = $requestedResources['page'] ;
$requestedClass = CONTROLLERS_DIR . "_" . $requestedPage ;
$pageObject = new $requestedClass() ;

@session_start() ;
if( $pageObject->authenticated( AUTH_USER_KEY )) {
	$pageObject->doAction() ;
} else {
	$_SESSION['destination_uri'] = $requestedPage ;
	header( "Location: ".APPLICATION_URI.APPLICATION_LOGIN_RESOURCE ) ;
}



/**
 * includeLib4
 */
function includeLib4() {
	require_once( "lib4/HttpRequestVars.php" ) ;
	require_once( "lib4/GetVars.php" ) ;
	require_once( "lib4/PostVars.php" ) ;
	require_once( "lib4/View.php" ) ;
	require_once( "lib4/Controller.php" ) ;
	require_once( "lib4/AuthNone.php" ) ;
	require_once( "lib4/AuthUser.php" ) ;
	//require_once( "Model.php" ) ;
	require_once( "lib4/UriResource.php" ) ;
}



?>
