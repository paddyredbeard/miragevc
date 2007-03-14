<?php

/**
 * Controller.php
 *
 * @package	MirageVC
 * @author	Patrick Barabe
 * @copyright	Copyright &copy; 2007 Patrick Barabe
 * @license	http://creativecommons.org/licenses/GPL/2.0/ GNU Public License
 *
 */


/**
 * Controller
 *
 * An abstract class that helps negotiate the resource for the requested URI.
 */ 
abstract class Controller {

    /**
     * @var {@link GetVars}
     */
    protected $_getVars ;

    /**
     * @var {@link PostVars}
     /*
    protected $_postVars ;

    /**
     * @var {@link UriResource}
     */
    protected $_uriResource ;

    /**
     * @var integer
     */
    protected $_itemNumber ;

    /**
     * @var {@link View}
     */
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

    /**
     * doAction
     *
     * The &quot;main&quot; method of instantiable Controller classes
     */
    abstract public function doAction() ;

    /**
     * authenticated
     *
     * Abstract method used to determine whether a user may access the requested URI.
     *
     * @return bool
     */
    abstract public function authenticated( $authUserKey ) ;

}// end Controller

?>
