<?php
namespace monochrome;

/**
* @file
* Define the "PDOconnection class, a wrapper around a PDO connection"
*/


require_once $_SERVER['DOCUMENT_ROOT'] . '/../data/constants.php';

use \PDO;

    
Class PDOconnection
{

    /**
     * @var PDO $conn
     */ 
    private $conn = null;

    // TODO : write phpdoc
    private $result = null;

    /**
     * @var string $lastMsg
     */
    private $lastMsg = null;


    function __construct() 
    {
        try {
            $this->conn = new \PDO(
                ENGINE . ':host=' . HOST . ';dbname=' . BDD . ';',
                USER, MDP 
            );
        }
        catch (\PDOException $e)
        {
            $this->lastMsg = $e->getMessage();
        }

    }

    /**
     * @return string
     */
    function getLastMsg() : string 
    {
        return $this->lastMsg;
    }

    /**
     * @return bool
     */
    function getSuccess() : bool 
    {
        return ( null != $this->conn );
    }


    // TODO : écrire PHPDoc
    function doQuery() 
    {
        // TODO : requếte préparée !
        $this->result = $this->conn->query(
            'SELECT civ.civility, c.lastName, c.firstName
            FROM contact AS c JOIN civility AS civ
            ON c.civility_id = civ.id
            ORDER BY c.lastName ASC' 
        );
    }
    

    /**
     * @return array|NULL
     */
    function fetchAsAssoc() 
    {
        // TODO : requếte préparée !
        return $this->result->fetch(PDO::FETCH_ASSOC);
    }

}
