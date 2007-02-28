<?php

abstract class Model extends MDB2 {

	public $data ;
	public $schema ;
	protected $table ;
	protected $pkField ;
	protected $dbConnection ;

	public function __construct( $classFile=null, $recno=null ) {
		$this->schema = Model::getSchema( $classFile ) ;
		$this->dbConnection = &MDB2::singleton( APPLICATION_DSN ) ;


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
			$sql = "SELECT " ;
			$i = 1 ;
			foreach( $this->schema['fields'] as $key=>$nextField ) {
				$sql .= $nextField ;

				if( $i < count( $this->schema['fields'] )) {
					$sql .= ", " ;
				}
				$i++ ;
			}
			$sql .= " FROM {$this->table} WHERE {$this->pkField}=$recno" ;

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


	protected function getSchema( $aClassFile ) {
		$output = false ;
                $schemaFile = str_replace( ".php", ".ini", $aClassFile ) ;

                return file_exists( $schemaFile ) ?
	                parse_ini_file( $schemaFile, true ) :
                	PEAR::raiseError(
                                "No database schema file found for class: Tried \"$schemaFile\""
                                ) ;
	}// end getSchema


	public function __destruct() {}


        public function __toString() {
                showDebug( $this->data ) ;
        }


}// end Model

?>
