<?php

class UriResource {

    public static function getRequestedResource() {

	if( APPLICATION_URI == $_SERVER['REQUEST_URI'] ) {
	    $theResource = DEFAULT_URI_RESOURCE ;
	} elseif( $_SERVER['REQUEST_URI'] == APPLICATION_BASE_URI."/" ) {
	    $theResource = DEFAULT_URI_RESOURCE ;
	} else {
	    $theResource = str_replace( APPLICATION_URI.'/', '', $_SERVER['REQUEST_URI'] ) ;
	    $theResource = str_replace( '?'.$_SERVER['QUERY_STRING'], '', $theResource ) ;
	    $theResource = str_replace( '/', '_', $theResource ) ;
	}

	if( empty( $theResource )) {
	    $theResource = DEFAULT_URI_RESOURCE ;
	}

	if( strpos( $theResource, '.php' ) !== false ) {
	    $theResource = DEFAULT_URI_RESOURCE ;
	}

	return $theResource ; 
    }

}// end UriResource

?>
