<?php

/**
 * UriResource.php
 *
 * @package    MirageVC
 * @subpackage MirageVC4
 * @deprecated
 * @author     Patrick Barabe
 * @copyright  Copyright &copy; 2007 Patrick Barabe
 * @license    http://creativecommons.org/licenses/GPL/2.0/ GNU Public License
 *
 */


class UriResource {

    function getRequestedResources() {

        $includeFile = null ;
        $theResource = null ;
        $lastArg = null ;
        $requestedResources = array( 'include'=>'', 'page'=>'', 'itemNumber'=>'' ) ;

        if( APPLICATION_URI == $_SERVER['REQUEST_URI'] ) {
            $includeFile = DEFAULT_URI_RESOURCE . ".php" ;
            $theResource = DEFAULT_URI_RESOURCE ;
        } elseif( $_SERVER['REQUEST_URI'] == APPLICATION_BASE_URI."/" ) {
            $includeFile = DEFAULT_URI_RESOURCE . ".php" ;
            $theResource = DEFAULT_URI_RESOURCE ;
        } else {
            $theResource = str_replace( APPLICATION_URI.'/', '', $_SERVER['REQUEST_URI'] ) ;
            $requestArgs = explode( "/", $theResource ) ;

            for( $i=count($requestArgs)-1; $i>=0; $i-- ) {
                $lastArg = $requestArgs[$i] ;
                if( !empty( $lastArg )) {
                    break ;
                }
            }

            if( is_numeric( $lastArg )) {
                $includeFile = str_replace( "/$lastArg/", '', $theResource ) . ".php" ;
                $includeFile = str_replace( "/$lastArg", '', $includeFile ) ;
                $theResource = str_replace( "/$lastArg/", '', $theResource ) ;
                $theResource = str_replace( "/$lastArg", '', $theResource ) ;
            } else {
                $theResource = str_replace( "$lastArg/", "$lastArg", $theResource ) ;
                $includeFile = $theResource . ".php" ;
            }

            $includeFile = str_replace( '?'.$_SERVER['QUERY_STRING'], '', $includeFile ) ;
            $theResource = str_replace( '?'.$_SERVER['QUERY_STRING'], '', $theResource ) ;
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
        $requestedResources['include'] = $includeFile ;

        return $requestedResources ; 
    }

}// end UriResource

?>
