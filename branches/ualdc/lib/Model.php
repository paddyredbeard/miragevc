<?php

/**
 * Model.php
 *
 * @package	MirageVC
 * @author	Patrick Barabe
 * @copyright	Copyright &copy; 2007 Patrick Barabe
 * @license	http://creativecommons.org/licenses/GPL/2.0/ GNU Public License
 *
 */


/**
 * Model
 * 
 * A database utility wrapper for MDB2.  A Model class represents one record in a table.
 */
abstract class Model extends MDB2 {

	/**
	 * @var array The DB record's data.
	 */
	public $data ;

	/**
	 * @var array The DB schema for the table.
	 */
	public $schema ;

	/**
	 * @var string The name of the DB table.
	 */
	protected $table ;

	/**
	 * @var string The name of the table's primary key field.
	 */
	protected $pkField ;

	/**
	 * @var object A MDB2 object
	 */
	protected $dbConnection ;

	/**
	 * __construct
	 *
	 * The class constructor.
	 *
	 * @param string $classFile The filesystem path to the instantiated Model class.
	 * @param integer $recno The primary key value of a record to instantiate.
	 */
	public function __construct( $classFile=null, $recno=null ) {
		$this->schema = Model::getSchema( $classFile ) ;
		$this->dbConnection = &MDB2::singleton( APPLICATION_DSN ) ;
		$this->data = array() ;

		//add fields as data's array keys
		foreach( $this->schema['fields'] as $nextField ) {
			$this->data[$nextField] = null ;
		}


		// some error checking on the connection
		if( PEAR::isError( $this->dbConnection )) {
			showDebug( array( 'Connection Error' => $this->dbConnection->getMessage() )) ;
		}


		// some error checking on the schema
		if( PEAR::isError( $this->schema )) {
			showDebug( array( 'Schema Error' => $this->schema->getMessage() )) ;
		}


		$this->table = $this->schema['dbparams']['table'] ;
		$this->pkField = $this->schema['dbparams']['pkfield'] ;


		// populate $this->data if a record number was passed
		if( !empty( $recno )) {
			$sql = "SELECT " . 
				implode( ",", $this->schema['fields'] ) . 
				" FROM {$this->table} WHERE {$this->pkField}=$recno" ;

			$queryResult = $this->dbConnection->query( $sql ) ;

			if( PEAR::isError( $queryResult )) {
				showDebug( array( 'Query Error' => $queryResult->getMessage() )) ;
			} else {
				while( $row = $queryResult->fetchRow( MDB2_FETCHMODE_ASSOC )) {
					foreach( $row as $field=>$value ) {
						$this->data[$field] = $value ;
					}
				} 
			}
		}

	}// end constructor


	/**
	 * getSchema	
	 * 
	 * A utility method to populate the object's schema variable
	 * 
	 * @param string $aClassFile The filesystem path to the instantiated Model class.
	 * @return mixed True or PEAR_Error
	 */
	protected function getSchema( $aClassFile ) {
		$output = false ;
		$schemaFile = str_replace( ".php", ".ini", $aClassFile ) ;

		return file_exists( $schemaFile ) ?
			parse_ini_file( $schemaFile, true ) :
				PEAR::raiseError(
						"No database schema file found for class: Tried \"$schemaFile\""
						) ;
	}// end getSchema


	/**
	 * save
	 *
	 * Generic method to create or update the db record.  
	 * Calls either create or update depending on whether the 
	 * primary key already has a value or not.
	 *
	 * @return mixed True or PEAR_Error
	 */
	public function save() {

		//$isValid = $this->isValid() ;

		//if( !PEAR::isError( $isValid )) {

			$pkValue = $this->data[$this->pkField] ;
			if( empty( $pkValue )) {
				return $this->create() ;
			} else {
				return $this->update() ;
			}

		//} else {
		//	return $isValid ;
		//}

	}// end save



	/**
	 * create
	 *
	 * Create a new DB record.
	 *
	 * @return mixed True or PEAR_Error
	 */
	public function create() {

		$objectData = array() ;
		foreach( $this->schema['fields'] as $nextField ) {
			if( !self::emptyNotZero( $this->data[$nextField] )) {

				switch( $this->schema['field_definitions'][$nextField] ) {
					case DB_DATATYPE_STRING_BASIC:
					case DB_DATATYPE_STRING_EMAIL:
					case DB_DATATYPE_DATE:
						$objectData[$nextField] = "'{$this->data[$nextField]}'" ;
						break ;

					default:
						$objectData[$nextField] = $this->data[$nextField] ;
						break ;
				}// end switch

			}
		}

		$sql  = "INSERT INTO {$this->table} ( " ;
		$sql .= implode( ", ", array_keys( $objectData )) ;
		$sql .= " ) VALUES ( " . implode( ", ", $objectData ) . " )" ;

		$saved = $this->dbConnection->exec( $sql ) ;
		if( !PEAR::isError( $saved )) {
			$this->{$this->pkField} = $this->dbConnection->lastInsertID( $this->table ) ;
		}

		return $saved ;

	}// end create



	/**
	 * update
	 *
	 * Update the object's DB record.
	 *
	 * @return True or PEAR_Error
	 */
	public function update() {

		$updateFields = array() ;
		foreach( $this->schema['fields'] as $nextField ) {
			if( !self::emptyNotZero( $this->data[$nextField] )) {

				switch( $this->schema['field_definitions'][$nextField] ) {
					case DB_DATATYPE_STRING_BASIC:
					case DB_DATATYPE_STRING_EMAIL:
					case DB_DATATYPE_DATE:
						$updateFields[] = "$nextField='{$this->data[$nextField]}'" ;
						break ;

					default:
						$updateFields[] = "$nextField={$this->data[$nextField]}" ;
						break ;
				}// end switch

			}
		}

		$sql  = "UPDATE {$this->table} SET " ;
		$sql .= implode( ", ", $updateFields ) ;
		$sql .= " WHERE {$this->pkField}=".$this->data[$this->pkField] ;

		return $this->dbConnection->exec( $sql ) ;

	}// end update


	/**
	 * delete
	 *
	 * Delete the object's record from the DB.
	 * 
	 * @return mixed The result of MDB2::exec()
	 */
	public function delete() {
		$sql = "DELETE FROM {$this->table} WHERE {$this->pkField}=".$this->data[$this->pkField] ;
		return $this->dbConnection->exec( $sql ) ;
	}



	public function __destruct() {}


	public function __toString() {
		showDebug( $this->data ) ;
	}


	/**
	 * __get
	 *
	 * Get a field's value from the object's $data array
	 *
	 * @param string $anAttribute The field to return a value from.
	 * @return mixed The field's value or PEAR_Error
	 */
	public function __get( $anAttribute ) {
		$_output = null ;

		if( array_key_exists( $anAttribute, $this->data )) {
			$_output = html_entity_decode( $this->data[$anAttribute], ENT_QUOTES ) ;
		} else {
			if ( SHOW_DEBUG ) {
				$_output = PEAR::raiseError(
						"Cannot get the requested property [$anAttribute] ."
						) ;
			}
		}

		return $_output ;
	}// end __get


	/**
	 * __set
	 *
	 * Set a field's value in the object's $data array
	 *
	 * @param string $anAttribute The field to set.
	 * @param mixed $aValue The value to set.
	 * @return mixed True or PEAR_Error
	 */
	public function __set( $anAttribute, $aValue ) {
		$_output = false ;

		if( array_key_exists( $anAttribute, $this->data )) {
			$this->data[$anAttribute] = htmlentities( $aValue, ENT_QUOTES ) ;
			$_output = true ;
		} else {
			if ( SHOW_DEBUG ) {
				$_output = PEAR::raiseError(
						"Cannot set the requested property [$anAttribute] ."
						) ;
			}
		}

		return $_output ;

	}// end __set


	/**
	 * objectFactory
	 *
	 * Static method to create a Model object determined
	 * from an array of &quot;fieldname=value&quot; elements.
	 *
	 * @param string $className The type of Model object to instantiate.
	 * @param array $params An array of &quot;fieldname=value&quot; elements.
	 * @param bool $strict Whether to return a PEAR_Error or an empty object if no records match $params.
	 * @return object Model or PEAR_Error object.
	 */
	public static function objectFactory( $className, $params=null, $strict=false ) {
		$returnObj = null ;

		if( !empty( $className ) && !empty( $params )) {
			$theCollection = Model::collectionFactory( $className, $params ) ;

			// to return a single object, only one record should match the query
			if( $theCollection['size'] < 1 ) {
				if( $strict ) {
					$returnObj = PEAR::raiseError( "No records match the input parameters." ) ;
				} else {
					$returnObj = new $className() ;
				}
			} elseif( $theCollection['size'] > 1 ) {
				$returnObj = PEAR::raiseError( "Multiple records match the input parameters." ) ;
			} else {
				$returnObj = $theCollection['objects'][0] ;
			}
		}

		return $returnObj ;

	}// end objectFactory


	/**
	 * collectionFactory
	 *
	 * Static method to create an array of Model objects determined
	 * from an array of &quot;fieldname=value&quot; elements.
	 *
	 * @param string $className The type of Model object to instantiate.
	 * @param array $params An array of &quot;fieldname=value&quot; elements.
	 * @param string $operator The SQL operator with which to bind the $params elements.
	 * @return array An array with the following keys: size (integer), schema (array), objects (array)
	 */
	public static function collectionFactory( $className, $params=array(), $operator="AND" ) {
		$returnArray = array( 'size'=>0, 'schema'=>null, 'objects'=>array() ) ;

		if( !empty( $className )) {

			// create a temporary/empty object of the requested class
			$templateObj = new $className() ;

			// assemble sql from input params and do query
			$classTable = $templateObj->schema['dbparams']['table'] ;
			$fieldList = implode( ",", $templateObj->schema['fields'] ) ;
			$whereString = implode( " $operator ", $params ) ;
			$sortField = !empty( $templateObj->schema['dbparams']['sortField'] ) ?
				$templateObj->schema['dbparams']['sortField'] :
				$templateObj->pkField ;
			$sortDir = !empty( $templateObj->schema['dbparams']['sortDir'] ) ?
				$templateObj->schema['dbparams']['sortDir'] :
				"" ;

			$sql = "SELECT $fieldList FROM $classTable" ;
			if( !empty( $whereString )) { $sql .= " WHERE $whereString" ; }
			$sql .= " ORDER BY $sortField $sortDir" ;

			$queryResult = $templateObj->dbConnection->query( $sql ) ;

			if( !PEAR::isError( $queryResult )) {
			    // set the size
			    $returnArray['size'] = PEAR::isError( $queryResult->numRows() ) ? 0 : $queryResult->numRows() ;

			    // populate objects
			    while( $nextRec = $queryResult->fetchRow( MDB2_FETCHMODE_ASSOC )) {
				$returnArray['objects'][] = new $className( $nextRec[$templateObj->pkField] ) ;
			    }
			} else {
			    $returnArray['size'] = 0 ;
			    $returnArray['errors'] = $queryResult ;
			}

			// set the schema
			$returnArray['schema'] = $templateObj->schema ;
		}

		return $returnArray ;

	}// end collectionFactory




	/**
	 * getLookupArray
	 *
	 * Static method to return an array of lookup values from a table's records
	 *
	 * @param $className The name of the Model object to use.
	 * @param $lookupField The name of the field to use as a lookup column.
	 * @return array An array of lookup values using primary key values as the array's index values.
	 *
	 */
	public static function getLookupArray( $className, $lookupField ) {

		$returnArray = array() ;
		$templateObj = new $className() ;

		$table = $templateObj->schema['dbparams']['table'] ;
		$pkField = $templateObj->schema['dbparams']['pkfield'] ;
		$sortField = !empty( $templateObj->schema['dbparams']['sortField'] ) ?
			$templateObj->schema['dbparams']['sortField'] :
			$templateObj->pkField ;
		$sortDir = !empty( $templateObj->schema['dbparams']['sortDir'] ) ?
			$templateObj->schema['dbparams']['sortDir'] :
			"" ;

		$sql = "SELECT $pkField, $lookupField FROM $table" ;
		$sql .= " ORDER BY $sortField $sortDir" ;
		$queryResult = $templateObj->dbConnection->query( $sql ) ;

		while( $nextRec = $queryResult->fetchRow( MDB2_FETCHMODE_ASSOC )) {
			$returnArray[$nextRec[$pkField]] = $nextRec[$lookupField] ;
		}

		return $returnArray ;

	}// end getLookupArray



	/**
	 * validateField
	 *
	 * Validate the specified fields's data.
	 *
	 */
	private function validateField( $aField ) {
		$isValid = false ;
		$isValid = true ;
		return $isValid ;
	}


	/**
	 * isValid
	 *
	 * Validate the Model object's data.
	 *
	 */
	private function isValid() {
		$isValid = false ;

		foreach( $this->schema['fields'] as $nextField ) {
			$this->validateField( $nextField ) ;
		}

		return $isValid ;
	}


	/**
	 * emptyNotZero
	 * 
	 * Determine whether a value is empty or numeric zero (0)
	 *
	 */ 
	private static function emptyNotZero( $aValue ) {
		if( empty( $aValue )) {
			return !is_numeric( $aValue ) ;
		} else {
			return false ;
		}
	}


}// end Model

?>
