<?php

class Controller {

    var $_getVars ;
    var $_postVars ;
    var $_uriResource ;
    var $_itemNumber ;
    var $_view ;

    function Controller() {
	$requestedResources = UriResource::getRequestedResources() ;
	$this->_uriResource = $requestedResources['page'] ;
	$this->_itemNumber = $requestedResources['itemNumber'] ;
	$this->_getVars = new GetVars() ;
	$this->_postVars = new PostVars() ;
	$viewClass = VIEWS_DIR . "_" . $this->_uriResource ;
	$this->_view = new $viewClass() ;
    }


    function doAction() {}

    function authenticated( $authUserKey ) {}

}// end Controller

?>
