<?php

class UriResource {

	public static function getRequestedResources() {

		$requestedResources = array( 'page'=>'', 'itemNumber'=>'' ) ;
		$lastArg = null ;

		if( APPLICATION_URI == $_SERVER['REQUEST_URI'] ) {
			$theResource = DEFAULT_URI_RESOURCE ;
		} elseif( $_SERVER['REQUEST_URI'] == APPLICATION_BASE_URI."/" ) {
			$theResource = DEFAULT_URI_RESOURCE ;
		} else {
			$theResource = str_replace( APPLICATION_URI.'/', '', $_SERVER['REQUEST_URI'] ) ;
			$requestArgs = explode( "/", $theResource ) ;

			for( $i=count($requestArgs)-1; $i>=1; $i-- ) {
				$lastArg = $requestArgs[$i] ;
				if( !empty( $lastArg )) {
					break ;
				}
			}

			$theResource = str_replace( "/$lastArg/", '', $theResource ) ;
			$theResource = str_replace( "/$lastArg", '', $theResource ) ;
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

		return $requestedResources ; 
	}

}// end UriResource

?>
