<?php

require_once "../../config/database.php";

class UserModel
{
  private $conn;
  private $table = "users";

  public function __construct()
  {
    $database = new Database();
    $this->conn = $database->getConnection();
  }

  public function findUserByEmail($email)
  {
    $query = "SELECT * FROM $this->table WHERE email = :email";

    $stmt = $this->conn->prepare($query);

    $stmt->execute([
      ":email" => $email,
    ]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function isEmailExist($email)
  {
    return (bool) ($this->findUserByEmail($email));
  }

  public function createUser($payload)
  {
    if ($this->findUserByEmail($payload['email']))
      return false;

    $query = "INSERT INTO $this->table
              (name, email, password)
              VALUES 
              (:name, :email, :password)";

    $stmt = $this->conn->prepare($query);

    return $stmt->execute(
      [
        ":name" => $payload['name'],
        ":email" => $payload['email'],
        ":password" => $payload['password']
      ]
    );
  }

  public function updateUser($id, $fieldToUpdate, $params)
  {
    $fieldToUpdate = implode(', ', $fieldToUpdate);

    $query = "UPDATE $this->table SET $fieldToUpdate WHERE id= $id ";

    echo $query;

    $stmt = $this->conn->prepare($query);

    return $stmt->execute(
      $params
    );
  }

  public function isCredentialsMatched($payload)
  {
    $user = $this->findUserByEmail($payload['email']);

    if ($user && password_verify($payload['password'], $user['password']))
      return $user;

    return false;
  }
}