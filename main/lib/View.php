<?php

/**
 * View.php
 *
 * @package	MirageVC
 * @author	Patrick Barabe
 * @copyright	Copyright &copy; 2007 Patrick Barabe
 * @license	http://creativecommons.org/licenses/GPL/2.0/ GNU Public License
 *
 */


abstract class View {

    protected $_vars ;

    abstract public function display() ;

    public function __construct() {
	$this->_vars = array() ;
    }

    public function setVars( $varsArray=array() ) {
	$this->_vars = $varsArray ;
    }

    public function __destruct() {}

}// end View

?>
