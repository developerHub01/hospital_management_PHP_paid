<?php

require_once "../../config/database.php";

class UserModel
{
  private $conn;

  public function __construct()
  {
    $database = new Database();
    $this->conn = $database->getConnection();
  }

  // Fetch users from the database
  public function fetchUsers()
  {
    $query = "SELECT * FROM users";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  // Add a user to the database
  public function addUser($name, $email)
  {
    $query = "INSERT INTO users (name, email) VALUES (:name, :email)";
    $stmt = $this->conn->prepare($query);
    return $stmt->execute([':name' => $name, ':email' => $email]);
  }
}