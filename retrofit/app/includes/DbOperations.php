<?php

    class DbOperations{
        private $connection;
        
        function __construct()
        {
            require_once dirname(__FILE__) . '/DbConnect.php';

            $db = new DbConnect;

            $this->connection = $db->connect();
        }

        public function createUser($username, $email, $firstName, $lastName, $phoneNumber, $address, $password, $rePassword){
            if(!$this->emailExist($email)){
                $stmt = $this->connection->prepare("INSERT INTO user (Username, Email, FirstName, LastName, PhoneNumber, address, password, rePassword) VALUES (????????)");
                $stmt->bind_param("ssssssss", $username, $email, $firstName, $lastName, $phoneNumber, $address, $password, $rePassword);
                if($stmt->execute()){
                    return USER_CREATED;
                }else{
                    return USER_FAILURE;
                }
                return USER_EXISTS;
            }
        }
        private function emailExist($email){
            $stmt = $this->connection->prepare("SELECT id FROM user WHERE Email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();
            return $stmt->num_rows() > 0;
        }
    }