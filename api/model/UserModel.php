<?php

require_once "../../config/database.php";

class UserModel
{
  private $conn;
  private $table = "user";

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
}