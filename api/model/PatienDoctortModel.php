<?php

require_once "../../config/database.php";

class PatienDoctortModel
{
  private $conn;
  private $table = "doctor_patients";

  public function __construct()
  {
    $database = new Database();
    $this->conn = $database->getConnection();
  }

  public function create($payload)
  {
    if ($this->findDoctorsPatientById($payload['patient_id'], $payload['doctor_id']))
      return false;

    $query = "INSERT INTO $this->table
              (patient_id, doctor_id)
              VALUES 
              (:patient_id, :doctor_id)";

    $stmt = $this->conn->prepare($query);

    return $stmt->execute(
      [
        ":patient_id" => $payload['patient_id'],
        ":doctor_id" => $payload['doctor_id'],
      ]
    );
  }

  public function findDoctorsPatientById($patientId, $doctorId)
  {
    $query = "SELECT * FROM $this->table
    WHERE patient_id = :patient_id AND
    doctor_id = :doctor_id
    ";

    $stmt = $this->conn->prepare($query);

    $stmt->execute([
      ":patient_id" => $patientId,
      ":doctor_id" => $doctorId,
    ]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function findDoctorsByPatientId($patientId)
  {
    $query = "SELECT * FROM $this->table 
    INNER JOIN doctors ON 
      $this->table.patient_id = doctors.id 
    INNER JOIN users ON 
      $this->table.doctor_id = users.id 
    WHERE patient_id = :patient_id";

    $stmt = $this->conn->prepare($query);

    $stmt->execute([
      ":patient_id" => $patientId,
    ]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function findPatientsByDoctorId($doctorId)
  {
    $query = "SELECT * FROM $this->table 
    INNER JOIN patients ON 
      $this->table.patient_id = patients.id 
    INNER JOIN users ON 
      $this->table.patient_id = users.id 
    WHERE patient_id = :patient_id";

    $stmt = $this->conn->prepare($query);

    $stmt->execute([
      ":doctor_id" => $doctorId,
    ]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
  }


  public function findPatientById($id)
  {
    $query = "SELECT * FROM $this->table WHERE id = :id";

    $stmt = $this->conn->prepare($query);

    $stmt->execute([
      ":id" => $id,
    ]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function findPatientByWardId($wardId)
  {
    $query = "SELECT * FROM $this->table WHERE ward_id = :ward_id";

    $stmt = $this->conn->prepare($query);

    $stmt->execute([
      ":ward_id" => $wardId,
    ]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function update($userId, $wardId)
  {
    $query = "UPDATE $this->table SET ward_id = :ward_id WHERE id = :id";

    $stmt = $this->conn->prepare($query);

    return $stmt->execute(
      [
        ":id" => $userId,
        ":ward_id" => $wardId
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
}