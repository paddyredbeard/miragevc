<?php

/**
 * View.php
 *
 * @package   MirageVC
 * @author    Patrick Barabe
 * @copyright Copyright &copy; 2007 Patrick Barabe
 * @license   http://creativecommons.org/licenses/GPL/2.0/ GNU Public License
 *
 */


/**
 * View
 * 
 * An abstract class that defines the presentation layer.
 */
abstract class View {

    /**
     * @var array An array of variables passed from the corresponding {@link Controller} class.
     */
    protected $_vars ;

    /**
     * display
     *
     * Abstract class used to output content for the requested URI.
     */
    abstract public function display() ;

    public function __construct() {
        $this->_vars = array() ;
    }

    /**
     * setVars
     *
     * Set the View's $_vars array.
     *
     * @param array $varsArray An associative array of values.
     */
    public function setVars( $varsArray=array() ) {
        $_output = false ;

        if( empty( $this->_vars )) {
            $this->_vars = $varsArray ;
            $_output = true ;
        }

        return $_output ;

    }


    /**
     * __get
     *
     * Get a variable from the object's $_vars array
     *
     * @param string $aKey The field to return a value from.
     * @return mixed The keys's value or PEAR_Error
     */
    public function __get( $aKey ) {
        $_output = false ;

        if( array_key_exists( $aKey, $this->_vars )) {
            $_output = $this->_vars[$aKey] ;
        } else {
            if ( SHOW_DEBUG ) {
                $_output = PEAR::raiseError(
                        "Cannot get the requested property [$aKey] ."
                        ) ;
            }
        }

        return $_output ;
    }// end __get


    /**
     * __set
     *
     * Set a value in the object's $_vars array
     *
     * @param string $aKey The field to set.
     * @param mixed $aValue The value to set.
     */
    public function __set( $aKey, $aValue ) {
        $this->_vars[$aKey] = $aValue ;
    }// end __set


    public function __destruct() {}

}// end View

?>
