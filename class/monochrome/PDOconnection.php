<?php
namespace monochrome;

/**
* Define the "PDOconnection class, a wrapper around a PDO connection"
*/

use \PDO;

class PDOconnection
{
    /**
     * @var PDO $conn  store the PDO object
     */
    private $conn = null;

    /**
    * @var $result $PDOstatement
    private $result = null;

    /**
     * @var string $lastMsg Last (error) message returned by PDO
     */
    private $lastMsg = null;


    public function __construct()
    {
        #constants definition are inside this file
        require_once $_SERVER['DOCUMENT_ROOT'] . '/../data/PDOconnectionConstants.php';

        try {
            $this->conn = new \PDO(
                ENGINE . ':host=' . HOST . ';dbname=' . BDD . ';',
                USER,
                MDP
            );
        } catch (\PDOException $e) {
            $this->lastMsg = $e->getMessage();
        }
    }

    /**
     * @return string
     */
    public function getLastMsg() : string
    {
        return $this->lastMsg;
    }

    /**
     * @return bool
     */
    public function getSuccess() : bool
    {
        return ( null != $this->conn );
    }


    public function doQuery()
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
    public function fetchAsAssoc()
    {
        // TODO : requếte préparée !
        return $this->result->fetch(PDO::FETCH_ASSOC);
    }
}
