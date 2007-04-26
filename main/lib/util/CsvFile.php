<?php


/* 
 * class to hold values from a comma-separated values ascii file
 */
class util_CsvFile {

	var $_file ;			// the file containing csv values
	var $_errors ;			// array to store error messages
	var $_headers ;			// array to store header names
	var $_data ;			// array to story file data
	var $_recordCount ;		// the number of records
	var $_columnCount ;		// the number of columns
	
	function __construct( $aFilename, $hasHeaderRow=false ) {

		if( $fhandle = fopen( $aFilename, "r" )) {
			$this->_file = $aFilename ;
			$this->_data = array() ;
			$this->_errors = array() ;
			$this->_recordCount = 0 ;
			$this->_columnCount = 0 ;

			// get header names
			if( $hasHeaderRow ) {
				$headers = fgetcsv( $fhandle, 1000 ) ;
				if( !empty( $headers ) && !empty( $headers[0] )) { 
					$this->_headers = $headers ; 
					$this->_columnCount = count( $this->_headers ) ;
				}
			}

			if( $hasHeaderRow && empty( $this->_headers )) {
				$msg = "Header row expected, but none found" ;
				$this->_errors[] = PEAR::raiseError( $msg ) ;
			} else {
				// parse file
				$i = 0 ;
				while(( $data = fgetcsv( $fhandle, 1000 )) !== FALSE) {
					if( $this->_columnCount == null ) { $this->_columnCount = count( $data ); }
	
					if( $hasHeaderRow ) {
						$tmpArray = array() ;
						foreach( $data as $index=>$value ) {
							$tmpArray[$this->_headers[$index]] = $value ;
						}
						$this->_data[] = $tmpArray ;
					} else {
						$this->_data[] = $data ;
					}
					$i++ ;
				}
			}

			fclose( $fhandle ) ;
			$this->_recordCount = count( $this->_data ) ;

		} else { 
			$msg = "Unable to read file $aFileName" ;
			$this->_errors[] = PEAR::raiseError( $msg ) ;
		}
		
	}// end constructor


	// return a value given its row and column indexes
	function getValue( $aRecord, $aColumn ) {
		$theValue = null ;

		if( is_numeric( $aColumn )) { // get the header index
			$theValue = $aRecord[$aColumn] ;
		} else {
			$headerIndex = array_search( $aColumn, $this->_headers ) ;
				
			if( $headerIndex > -1 ) {
				$theValue = $aRecord[$headerIndex] ;
			}
		}

		return $theValue ;

	}// end getValue

	
	// return the header at a given index
	function getHeader( $aColumnIndex ) {
		return !empty( $this->_headers[$aColumnIndex] ) ? $this->_headers[$aColumnIndex] : null ;
	}


	// return the index of the requested header
	function getHeaderIndex( $aHeader=null ) {}


	function __destruct() {}

}// end class CsvFile


?>
