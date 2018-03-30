<?php
namespace monochrome;

require_once 'constants.php';

use \PDO;

    
Class PDOconnection {

    /**
    * @var PDO
    */ 
    private $conn = NULL;

    /**
    * @var string
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

    #TODO __destruct() {}

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

}
