<?php

session_start();

if(!isset($_SESSION['logged_in'])){
    $account_total = 0;
}

class Client{

    private $host = '127.0.0.1';
    private $dbname = 'roomraccoon_db';
    private $username = 'root';
    private $password = ''; 
    protected $con;

    public function execPDOQuery(string $storedProc, array $params = null, array $queryContents = null){
        try{
            $this->con = new PDO('mysql:host='.$this->host.';dbname='.$this->dbname.';charset=utf8', $this->username, $this->password);
            $query = 'call '.$storedProc;
            // check if query has params
            if(is_null($params)){
                $stmt = $this->con->prepare($query);
            }else{
                // set param indexes and prepare query
                $query .= '('. substr(str_repeat('?,', count($params)), 0, -1) . ')';
                $stmt = $this->con->prepare($query);
                // bind Params
                for($i = 0; ++$i <= count($params);){
                    $stmt->bindParam($i, $params[($i-1)]);
                }
            }

            // check if query will have a response 
            if(is_array($queryContents) && !is_null($queryContents)){
                // execute query
                $stmt->execute();

                // check response and return dataset or one value
                if($queryContents['get'] === 'all'){
                    return $stmt;
                }else{
                    $returnObj = $stmt->fetch(PDO::FETCH_ASSOC);
                    return $returnObj[$queryContents['get']];
                }
            }else if($queryContents === null){
                if($stmt->execute()){
                    return true;
                }
            }

            // close connection
            $stmt = null;
            $this->con = null;
        }catch(PDOException $ex){
            return $ex->getMessage();
        }
    }

}