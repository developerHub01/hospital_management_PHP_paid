<?php

require_once "../../config/database.php";

class DoctorModel
{
  private $conn;
  private $table = "doctors";

  public function __construct()
  {
    $database = new Database();
    $this->conn = $database->getConnection();
  }

  public function index()
  {
    $query = "SELECT  
              users.id as user_id,
              $this->table.id as doctor_id,
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
  public function create($payload)
  {
    if ($this->findDoctorByUserId($payload['user_id']))
      return false;

    $query = "INSERT INTO $this->table
              (user_id, specialization)
              VALUES 
              (:user_id, :specialization)";

    $stmt = $this->conn->prepare($query);

    return $stmt->execute(
      [
        ":user_id" => $payload['user_id'],
        ":specialization" => $payload['specialization'],
      ]
    );
  }

  public function findDoctorByUserId($userId)
  {
    $query = "SELECT * FROM $this->table WHERE user_id = :user_id";

    $stmt = $this->conn->prepare($query);

    $stmt->execute([
      ":user_id" => $userId,
    ]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function update($userId, $specialization)
  {
    $query = "UPDATE $this->table SET specialization = :specialization WHERE id = :id";

    $stmt = $this->conn->prepare($query);

    return $stmt->execute(
      [
        ":id" => $userId,
        ":specialization" => $specialization
      ]
    );
  }

  public function delete($userId)
  {
    $query = "DELETE FROM $this->table WHERE id = :id";

    $stmt = $this->conn->prepare($query);

    return $stmt->execute(
      [
        ":id" => $userId,
      ]
    );
  }
}