<?php

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
