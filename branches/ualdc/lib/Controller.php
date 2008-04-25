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
     */
    protected $_postVars ;

    /**
     * @var {@link SessionVars}
     */
    protected $_sessionVars ;

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
	$this->_sessionVars = new SessionVars() ;
	$viewClass = VIEWS_DIR . "_" . $this->_uriResource ;
	$viewClass = str_replace( "__", "_", $viewClass ) ;
	$this->_view = new $viewClass() ;

        // add a crude history to session
        $referer = !empty( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : null ;
        $lastPage = !empty( $_SESSION['last_page'] ) ? $_SESSION['last_page'] : null ;

        if( $lastPage !== $referer && !empty( $referer )) {
                $_SESSION['last_page'] = $referer ;
        }

        if( empty( $_SESSION['last_page'] )) {
                $_SESSION['last_page'] = APPLICATION_URI ;
        }
	
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
