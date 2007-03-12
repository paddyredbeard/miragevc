<?php

/**
 * Model.php
 *
 * @package	MirageVC
 * @subpackage	MirageVC4
 * @author	Patrick Barabe
 * @copyright	Copyright &copy; 2007 Patrick Barabe
 * @license	http://creativecommons.org/licenses/GPL/2.0/ GNU Public License
 * @filesource
 *
 */

	require_once( APPLICATION_BASE_DIR."/lib/MDB2.php" ) ;

	class Model extends MDB2 {

		var $data ;
		var $schema ;
		var $table ;
		var $pkField ;
		var $dbConnection ;

		function Model( $classFile=null, $recno=null ) {
			$this->schema = Model::getSchema( $classFile ) ;

			if( PEAR::isError( $this->schema )) {
				showDebug( $this->schema->getMessage() ) ;
			}

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


		// populate the object's schema variable
		function getSchema( $aClassFile ) {
			$output = false ;
			$schemaFile = str_replace( ".php", ".ini", $aClassFile ) ;

			return file_exists( $schemaFile ) ?
				parse_ini_file( $schemaFile, true ) :
				PEAR::raiseError(
					"No database schema file found for class file '$aClassFile': Tried \"$schemaFile\""
				) ;
		}// end getSchema



		// generic method to create or update the db record
		function save() {
			
			$pkValue = $this->data[$this->pkField] ;
			if( empty( $pkValue )) {
				return $this->create() ;
			} else {
				return $this->update() ;
			}
			
		}// end save



		// create the db record
		function create() {

			$objectData = array() ;
			foreach( $this->schema['fields'] as $nextField ) {
				if( !empty( $this->data[$nextField] )) {

					switch( $this->schema['field_definitions'][$nextField] ) {
					case DB_DATATYPE_STRING_BASIC:
					case DB_DATATYPE_STRING_EMAIL:
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

			return $this->dbConnection->exec( $sql ) ;

		}// end create
		


		// update the db record
		function update() {

			$updateFields = array() ;
			foreach( $this->schema['fields'] as $nextField ) {
				if( !empty( $this->data[$nextField] )) {

					switch( $this->schema['field_definitions'][$nextField] ) {
					case DB_DATATYPE_STRING_BASIC:
					case DB_DATATYPE_STRING_EMAIL:
						$updateFields[] = "$nextField='{$this->data[$nextField]}'" ;
						break ;
						
					default:
						$objectData[] = "$nextField={$this->data[$nextField]}" ;
						break ;
					}// end switch

				}
			}

			$sql  = "UPDATE {$this->table} SET " ;
			$sql .= implode( ", ", $updateFields ) ;
			$sql .= " WHERE {$this->pkField}=".$this->data[$this->pkField] ;

			return $this->dbConnection->exec( $sql ) ;

		}// end update



		// delete the object's record from the db
		function delete() {
			$sql = "DELETE FROM {$this->table} WHERE {$this->pkField}=".$this->data[$this->pkField] ;
			return $this->dbConnection->exec( $sql ) ;
		}



		// static method to create a model object using
		// an array of "fieldname=value" elements
		function objectFactory( $className, $params=null, $strict=false ) {
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

		}// end objFactory


		// static method to create an array of model objects 
		// using an array of "fieldname=value" elements
		function collectionFactory( $className, $params=array() ) {
			$returnArray = array( 'size'=>0, 'schema'=>null, 'objects'=>null ) ;

			if( !empty( $className )) {
				// create a temporary/empty object of the requested class
				$templateObj = new $className() ;

				// assemble sql from input params and do query
				$classTable = $templateObj->schema['dbparams']['table'] ;
				$fieldList = implode( ",", $templateObj->schema['fields'] ) ;
				$whereString = @implode( " AND ", $params ) ;
				$sql = "SELECT $fieldList FROM $classTable" ;

				if( !empty( $whereString )) {
					$sql .= " WHERE $whereString" ;
				}

				$queryResult = $templateObj->dbConnection->query( $sql ) ;

				// set the size
				$returnArray['size'] = PEAR::isError( $queryResult->numRows() ) ? 0 : $queryResult->numRows() ;

				// populate objects
				while( $nextRec = $queryResult->fetchRow( MDB2_FETCHMODE_ASSOC )) {
					$returnArray['objects'][] = new $className( $nextRec[$templateObj->pkField] ) ;
				}

				// set the schema
				$returnArray['schema'] = $templateObj->schema ;
			}

			return $returnArray ;
		}


	}// end Model

?>
