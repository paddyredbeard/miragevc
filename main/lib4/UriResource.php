<?php

class UriResource {

	function getRequestedResources() {

		$requestedResources = array( 'include'=>'', 'class'=>'', 'itemNumber'=>'' ) ;
		$lastArg = null ;

		if( APPLICATION_URI == $_SERVER['REQUEST_URI'] ) {
			$requestedResources['include'] = DEFAULT_URI_RESOURCE . ".php" ;
			$theResource = DEFAULT_URI_RESOURCE ;
		} elseif( $_SERVER['REQUEST_URI'] == APPLICATION_BASE_URI."/" ) {
			$requestedResources['include'] = DEFAULT_URI_RESOURCE . ".php" ;
			$theResource = DEFAULT_URI_RESOURCE ;
		} else {
			$theResource = str_replace( APPLICATION_URI.'/', '', $_SERVER['REQUEST_URI'] ) ;
			$requestedResources['include'] = $theResource . ".php" ;
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
