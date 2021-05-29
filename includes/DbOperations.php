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
            }
            return USER_EXISTS;
        }

        public function userLogin($email, $password){
            if($this->emailExist($email)){
                $hashed_password = $this->getUserPasswordByEmail($email);
                if(password_verify($password, $hashed_password)){
                    return USER_AUTHENTICATED;
                }else{
                    return USER_PASSWORD_DO_NOT_MATCH;
                }
            }else{
                return USER_NOT_FOUND;
            }
        }

        private function getUserPasswordByEmail($email){
            $stmt = $this->connection->prepare("SELECT password FROM user");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->bind_result($password);
            $stmt->fetch();
            return $password;
        }

        public function getAllUsers($email){
            $stmt = $this->connection->prepare("SELECT Id, Username, Email, FirstName, LastName, PhoneNumber, address FROM user WHERE Email = ?");
            $stmt->execute();
            $stmt->bind_result($id, $email, $username, $firstName, $lastName, $PhoneNumber, $address);
            $users = array();
            while($stmt->fetch()){
                $user = array();
                $user['Id'] = $id;
                $user['Email'] = $email;
                $user['Username'] = $username;
                $user['firstName'] = $firstName;
                $user['lastName'] = $lastName;
                $user['phoneNumber'] = $PhoneNumber;
                $user['address'] = $address;
                array_push($users, $user);
            }
        }

        public function getUserByEmail($email){
            $stmt = $this->connection->prepare("SELECT Id, Username, Email, FirstName, LastName, PhoneNumber, address FROM user WHERE Email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->bind_result($id, $email, $username, $firstName, $lastName, $PhoneNumber, $address);
            $stmt->fetch();
            $user = array();
            $user['id'] = $id;
            $user['email'] = $email;
            $user['username'] = $username;
            $user['firstName'] = $firstName;
            $user['lastName'] = $lastName;
            $user['phoneNumber'] = $PhoneNumber;
            $user['address'] = $address;
            return $user;
        }

        private function emailExist($email){
            $stmt = $this->connection->prepare("SELECT id FROM user WHERE Email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();
            return $stmt->num_rows() > 0;
        }
    }