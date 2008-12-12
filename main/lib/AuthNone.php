<?php

/**
 * AuthNone.php
 *
 * @package   MirageVC
 * @author    Patrick Barabe
 * @copyright Copyright &copy; 2007 Patrick Barabe
 * @license   http://creativecommons.org/licenses/GPL/2.0/ GNU Public License
 *
 */

/**
 * AuthNone
 *
 * An abstract {@link Controller} class that doesn't require an
 * authenticated user in order to be instantiated.
 */ 
abstract class AuthNone extends Controller {

    public function __construct() {
        parent::__construct() ;
    }

    public function __destruct() {
        parent::__destruct() ;
    }

    /**
     * authenticated
     *
     * Method used to determine whether a user may access the requested URI.
     *
     * @param string $authUserKey The SESSION key that stores a userid.
     * @return bool Always returns true.
     */
    public function authenticated( $authUserKey ) {
        return true ;
    }

}// end AuthNone

?>
