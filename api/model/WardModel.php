<?php

require_once "../../config/database.php";

class WardModel
{
  private $conn;
  private $table = "wards";

  public function __construct()
  {
    $database = new Database();
    $this->conn = $database->getConnection();
  }

  public function create($capacity)
  {
    $query = "INSERT INTO $this->table
              (capacity)
              VALUES 
              (:capacity)";

    $stmt = $this->conn->prepare($query);

    return $stmt->execute(
      [
        ":capacity" => $capacity,
      ]
    );
  }

  public function findWardByWardId($wardId)
  {
    $query = "SELECT * FROM $this->table WHERE id = :id";

    $stmt = $this->conn->prepare($query);

    $stmt->execute([
      ":id" => $wardId,
    ]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function addPatient($wardId)
  {
    $query = "UPDATE $this->table SET current_patients_count = current_patients_count + 1 WHERE id = :id";

    $stmt = $this->conn->prepare($query);

    $stmt->execute([
      ":id" => $wardId,
    ]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function removePatient($wardId)
  {
    $query = "UPDATE $this->table SET current_patients_count = current_patients_count - 1 WHERE id = :id";

    $stmt = $this->conn->prepare($query);

    $stmt->execute([
      ":id" => $wardId,
    ]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function update($wardId, $capacity)
  {
    $query = "UPDATE $this->table SET capacity = :capacity WHERE id = :id";

    $stmt = $this->conn->prepare($query);

    return $stmt->execute(
      [
        ":id" => $wardId,
        ":capacity" => $capacity
      ]
    );
  }

  public function delete($wardId)
  {
    $query = "DELETE FROM $this->table WHERE id = :id";

    $stmt = $this->conn->prepare($query);

    return $stmt->execute(
      [
        ":id" => $wardId,
      ]
    );
  }
}