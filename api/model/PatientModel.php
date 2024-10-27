<?php

require_once "../../config/database.php";

class PatientModel
{
  private $conn;
  private $table = "patients";

  public function __construct()
  {
    $database = new Database();
    $this->conn = $database->getConnection();
  }

  public function index()
  {
    $query = "SELECT
              users.id as user_id,
              $this->table.id as patient_id,
              $this->table.ward_id,
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
    if ($this->findPatientByUserId($payload['user_id']))
      return false;

    $query = "INSERT INTO $this->table
              (user_id, ward_id)
              VALUES 
              (:user_id, :ward_id)";

    $stmt = $this->conn->prepare($query);

    $createResponse = $stmt->execute(
      [
        ":user_id" => $payload['user_id'],
        ":ward_id" => $payload['ward_id'],
      ]
    );

    $wardModel = new WardModel();
    $wardModel->addPatient($payload['ward_id']);

    return $createResponse;
  }

  public function findPatientByUserId($userId)
  {
    $query = "SELECT * FROM $this->table WHERE user_id = :user_id";

    $stmt = $this->conn->prepare($query);

    $stmt->execute([
      ":user_id" => $userId,
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

  public function update($patientId, $wardId)
  {
    $patientData = $this->findPatientById($patientId);
    $oldWardId = $patientData['ward_id'];

    $query = "UPDATE $this->table SET ward_id = :ward_id WHERE id = :id";

    $stmt = $this->conn->prepare($query);

    $createResponse = $stmt->execute(
      [
        ":id" => $patientId,
        ":ward_id" => $wardId
      ]
    );

    $wardModel = new WardModel();
    $wardModel->removePatient($oldWardId);
    $wardModel->addPatient($wardId);

    return $createResponse;
  }

  public function delete($patientId)
  {
    $patientData = $this->findPatientById($patientId);
    $wardId = $patientData['ward_id'];

    $query = "DELETE FROM $this->table WHERE id = :id";

    $stmt = $this->conn->prepare($query);

    $createResponse = $stmt->execute(
      [
        ":id" => $patientId,
      ]
    );

    $wardModel = new WardModel();
    $wardModel->removePatient($wardId);

    return $createResponse;
  }
}