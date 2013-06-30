<?php

/**
 * View.php
 *
 * @package    MirageVC
 * @subpackage MirageVC4
 * @deprecated
 * @author     Patrick Barabe
 * @copyright  Copyright &copy; 2007 Patrick Barabe
 * @license    http://creativecommons.org/licenses/GPL/2.0/ GNU Public License
 *
 */


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
