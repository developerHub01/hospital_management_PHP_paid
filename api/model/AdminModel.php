<?php

require_once "../../config/database.php";

class AdminModel
{
  private $conn;
  private $table = "admins";

  public function __construct()
  {
    $database = new Database();
    $this->conn = $database->getConnection();
  }

  public function index()
  {
    $query = "SELECT
              $this->table.id,
              users.id as user_id,
              $this->table.role,
              users.name,
              users.email,
              users.dob,
              users.gender 
              FROM $this->table 
              INNER JOIN users ON 
              users.id = $this->table.user_id";

    $stmt = $this->conn->prepare($query);

    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function create($userId)
  {
    if ($this->findAdminByUserId($userId))
      return false;

    $query = "INSERT INTO $this->table
              (user_id)
              VALUES 
              (:user_id)";

    $stmt = $this->conn->prepare($query);

    return $stmt->execute(
      [
        ":user_id" => $userId,
      ]
    );
  }

  public function findAdminByUserId($userId)
  {
    $query = "SELECT * FROM $this->table WHERE user_id = :user_id";

    $stmt = $this->conn->prepare($query);

    $stmt->execute([
      ":user_id" => $userId,
    ]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function delete($userId)
  {
    $query = "DELETE FROM $this->table WHERE user_id = :user_id";

    $stmt = $this->conn->prepare($query);

    return $stmt->execute(
      [
        ":user_id" => $userId,
      ]
    );
  }
}