<?php

/**
 * Controller.php
 *
 * @package	MirageVC
 * @author	Patrick Barabe
 * @copyright	Copyright &copy; 2007 Patrick Barabe
 * @license	http://creativecommons.org/licenses/GPL/2.0/ GNU Public License
 * @filesource
 *
 */


abstract class Controller {

    protected $_getVars ;
    protected $_postVars ;
    protected $_uriResource ;
    protected $_itemNumber ;
    protected $_view ;

    public function __construct() {
	$requestedResources = UriResource::getRequestedResources() ;
	$this->_uriResource = $requestedResources['page'] ;
	$this->_itemNumber = $requestedResources['itemNumber'] ;
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
