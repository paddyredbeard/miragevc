<?php

class View {

    var $_vars ;

    function View() {
	$this->_vars = array() ;
    }

    function display() {}

    function setVars( $varsArray=array() ) {
	$this->_vars = $varsArray ;
    }

}// end View

?>
