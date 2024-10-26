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

  public function create($payload)
  {
    if ($this->findDoctorByUserId($payload['user_id']))
      return false;

    print_r($payload);

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

  public function update($id, $specialization)
  {
    $query = "UPDATE $this->table SET specialization = :specialization WHERE id = :id";

    $stmt = $this->conn->prepare($query);

    return $stmt->execute(
      [
        ":id" => $id,
        ":specialization" => $specialization
      ]
    );
  }

  public function delete($id)
  {
    $query = "DELETE FROM $this->table WHERE id = :id";

    $stmt = $this->conn->prepare($query);

    return $stmt->execute(
      [
        ":id" => $id,
      ]
    );
  }

  public function readCurrentUserData()
  {
    $userData = readToken("access_token");

    $userId = $userData['id'];

    $query = "SELECT * FROM $this->table INNER JOIN admin ON $this->table.id = admin.user_id WHERE $this->table.id = :userId";

    $stmt = $this->conn->prepare($query);

    $stmt->execute([
      ":userId" => $userId,
    ]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
  }
}