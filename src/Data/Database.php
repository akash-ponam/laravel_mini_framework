<?php 


    namespace App\Data;

    use PDO;

    class Database {
        
        private PDO $connection;

        public function __construct(array $config)
        {

            $dsn  = "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4";

            $this->connection = new PDO($dsn,$config['user'],$config['pass'],$config['options']);


        }

      
        public function query($sql,$params=[]){
    

        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);

        return $stmt;
        
    


    }






}










?>
