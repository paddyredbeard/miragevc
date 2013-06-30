<?php

/**
 * UriResource.php
 *
 * @package   MirageVC
 * @author    Patrick Barabe
 * @copyright Copyright &copy; 2007 Patrick Barabe
 * @license   http://creativecommons.org/licenses/GPL/2.0/ GNU Public License
 *
 */


/**
 * UriResource
 *
 * A utility class to determine what {@link Controller} class 
 * was requested from the REQUEST_URI
 *
 * @return array An array with the following keys: 'page' (string), 'itemNumber' (integer).
 */
class UriResource {


    // extract the name of the controller class based on REQUEST_URI
    public static function getRequestedResources() {

        $requestedResources = array( 'page'=>'', 'itemNumber'=>'' ) ;
        $lastArg = null ;


        // serve the default controller
        if( APPLICATION_URI == $_SERVER['REQUEST_URI'] ) {
            $theResource = DEFAULT_URI_RESOURCE ;

            // serve the default controller
        } elseif( $_SERVER['REQUEST_URI'] == APPLICATION_BASE_URI."/" ) {
            $theResource = DEFAULT_URI_RESOURCE ;

            // parse the class name
        } else {
            $theResource = str_replace( APPLICATION_URI.'/', '', $_SERVER['REQUEST_URI'] ) ;
            $theResource = str_replace( '?'.$_SERVER['QUERY_STRING'], '', $theResource ) ;
            $requestArgs = explode( "/", $theResource ) ;

            for( $i=count($requestArgs)-1; $i>=0; $i-- ) {
                $lastArg = $requestArgs[$i] ;
                if( !empty( $lastArg )) {
                    break ;
                }
            }

            if( is_numeric( $lastArg )) {
                $theResource = str_replace( "/$lastArg/", '', $theResource ) ;
                $theResource = str_replace( "/$lastArg", '', $theResource ) ;
            } else {
                $theResource = str_replace( "$lastArg/", "$lastArg", $theResource ) ;
            }

            //$theResource = str_replace( '?'.$_SERVER['QUERY_STRING'], '', $theResource ) ;
            $theResource = str_replace( '/', '_', $theResource ) ;
        }

        if( empty( $theResource )) {
            $theResource = DEFAULT_URI_RESOURCE ;
        }

        if( strpos( $theResource, '.php' ) !== false ) {
            $theResource = DEFAULT_URI_RESOURCE ;
        }

        $requestedResources['page'] = $theResource ;
        $requestedResources['itemNumber'] = is_numeric( $lastArg ) ? $lastArg : null ;

        return $requestedResources ; 
    }

}// end UriResource

?>
