<?php

abstract class Controller {

    protected $_getVars ;
    protected $_postVars ;
    protected $_uriResource ;
    protected $_view ;

    public function __construct() {
	$this->_uriResource = UriResource::getRequestedResource() ;
	$this->_getVars = new GetVars() ;
	$this->_postVars = new PostVars() ;
	$viewClass = VIEWS_DIR . "_" . $this->_uriResource ;
	$this->_view = new $viewClass() ;
    }

    public function __destruct() {}

    abstract public function doAction() ;

    abstract public function authenticated( $authUserKey ) ;

}// end Controller

?>
