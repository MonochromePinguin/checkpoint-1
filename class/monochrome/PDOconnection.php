<?php
namespace monochrome;

require_once 'constants.php';

use \PDO;

    
Class PDOconnection {

    /**
    * @var PDO $conn
    */ 
    private $conn = NULL;

    #TODO : write phpdoc
    private $result = NULL;

    /**
    * @var string $lastMsg
    */
    private $lastMsg = NULL;


    function __construct() {
        try {
            $this->conn = new \PDO(
                    ENGINE . ':host=' . HOST . ';dbname=' . BDD . ';',
                    USER, MDP );
        }
        catch (\PDOException $e)
        {
           $this->lastMsg = $e->getMessage();
        }

	}

    /**
    * @return string
    */
    function getLastMsg() : string {
        return $this->lastMsg;
    }

   /**
    * @return bool
    */
    function getSuccess() : bool {
        return ( NULL != $this->conn );
    }


    #TODO : écrire PHPDoc
    function doQuery() {
        #TODO : requếte préparée !
        $this->result = $this->conn->query(
            'SELECT civ.civility, c.lastName, c.firstName
            FROM contact AS c JOIN civility AS civ
            ON c.civility_id = civ.id
            ORDER BY c.lastName ASC' );
    }
    

    /**
    * @return array|NULL
    */
    function fetchAsAssoc() {
        #TODO : requếte préparée !
        return $this->result->fetch( PDO::FETCH_ASSOC );
    }

}
