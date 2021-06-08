<?php

    class DbOperations{
        private $connection;
        
        function __construct()
        {
            require_once dirname(__FILE__) . '/DbConnect.php';

            $db = new DbConnect;

            $this->connection = $db->connect();
        }

        public function createUser($username, $email, $firstName, $lastName, $phoneNumber, $address, $password){
            if(!$this->emailExist($email)){
                $stmt = $this->connection->prepare("INSERT INTO user (Username, Email, FirstName, LastName, PhoneNumber, address, password) VALUES (???????)");
                $stmt->bind_param("ssssssss", $username, $email, $firstName, $lastName, $phoneNumber, $address, $password);
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
            return $users;
        }

        public function getUserByEmail($email){
            $stmt = $this->connection->prepare("SELECT Id, Username, Email, FirstName, LastName, PhoneNumber, address FROM user WHERE Email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->bind_result($id, $email, $username, $firstName, $lastName, $PhoneNumber, $address);
            $stmt->fetch();
            $user = array();
            $user['id'] = $id;
            $user['Email'] = $email;
            $user['Username'] = $username;
            $user['firstName'] = $firstName;
            $user['lastName'] = $lastName;
            $user['phoneNumber'] = $PhoneNumber;
            $user['address'] = $address;
            return $user;
        }

        public function updateUser($username, $email, $firstName, $lastName, $phoneNumber, $address, $id){
            $stmt = $this->connection->prepare("UPDATE user SET username = ?, email = ?, firstName = ?, lastName = ?, phoneNumber = ?, address = ?, id = ?");
            $stmt -> bind_param("ssssssi", $username, $email, $firstName, $lastName, $phoneNumber, $address, $id);

            if($stmt->execute())
                return true;
            return false;
        }

        public function updatePassword($currentPassword, $newPassword, $email){
            $hashed_password = $this->getUserPasswordByEmail($email);

            if(password_verify($currentPassword, $hashed_password)){

                $hash_password = password_hash($newPassword, PASSWORD_DEFAULT);
                $stmt = $this->connection->prepare("UPDATE user SET password = ? WHERE email = ?");
                $stmt->bind_param("ss", $hash_password, $email);

                if($stmt->execute())
                    return PASSWORD_CHANGED;
                return PASSWORD_NOT_CHANGED;
            }else{
                return PASSWORD_DO_NOT_MATCH;
            }
        }

        public function deleteUser($id){
            $stmt = $this->connection->prepare("DELETE FROM user WHERE id = ?");
            $stmt->bind_param("i", $id);

            if($stmt->execute())
                return true;
            return false;
        }

        private function emailExist($email){
            $stmt = $this->connection->prepare("SELECT id FROM user WHERE Email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();
            return $stmt->num_rows() > 0;
        }
    }