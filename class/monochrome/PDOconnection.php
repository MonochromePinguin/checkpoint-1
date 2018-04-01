<?php
namespace monochrome;

/**
* Define the "PDOconnection class, a wrapper around a PDO connection"
*/

use \PDO as PDO;

class PDOconnection
{
    /**
     * @var PDO $conn  store the PDO object
     */
    private $conn = null;

    /**
    * @var $result $PDOstatement
    */
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


    /**
    * the return value is FALSE in case of error ; in this case, $this->lastMsg is set.
    * @return boolean
    */
    public function storeRecord(
        string $civility,
        string $firstName,
        string $lastName
    ) {
/*TODO : when we are able to send several records in one INSERT statement,
use a prepared query for the several genders */

        # get the record ID into the table "civility"
        # corresponding to the $civility string
        $prep = $this->conn->prepare(
            'SELECT id FROM civility WHERE civility = :civ'
        );
        $prep->bindValue(':civ', $civility, PDO::PARAM_STR);
        
        if (false == $prep->execute()) {
            $this->lastMsg = $prep->errorInfo();
            return false;
        }

        #parse the returned value – as long as the table structure is
        # unchanged, there can be only one entry corresponding to that string
        $civID = $prep->fetch(PDO::FETCH_NUM)[0];
/*TODO : add the ability to care about unknown civilities ... */


        #do the query
        $prep = $this->conn->prepare(
            'INSERT INTO contact (firstName, lastName, civility_id)
             VALUES (:fn, :ln, :civID)'
        );

        $prep->bindValue(':fn', $firstName, PDO::PARAM_STR);
        $prep->bindValue(':ln', $lastName, PDO::PARAM_STR);
        $prep->bindValue(':civID', $civID, PDO::PARAM_INT);

        if ($prep->execute()) {
            return true;
        } else {
            $err = $prep->errorInfo();
            $this->lastMsg = 'Erreur n°' . $err[0] . ' : ' . $err[2];
            return false;
        }
    }
}
