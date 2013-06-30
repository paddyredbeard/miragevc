<?php

/**
 * HttpRequestVars.php
 *
 * @package    MirageVC
 * @subpackage MirageVC4
 * @deprecated
 * @author     Patrick Barabe
 * @copyright  Copyright &copy; 2007 Patrick Barabe
 * @license    http://creativecommons.org/licenses/GPL/2.0/ GNU Public License
 *
 */


class HttpRequestVars {

    var $_request ;

    function HttpRequestVars() {
        $this->_request = null ;
    }

    function populate() {
        if( !empty( $this->_request )) {
            foreach( $this->_request as $key=>$val ) {
                $this->{$key} = $val ;
            }
        }
    }

}// end HttpRequestVars

?>
