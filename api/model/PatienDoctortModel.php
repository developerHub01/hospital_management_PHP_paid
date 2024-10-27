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

  public function index()
  {
    $query = "SELECT * FROM $this->table";

    $stmt = $this->conn->prepare($query);

    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function create($payload)
  {
    if ($this->findDoctorPatientById($payload['patient_id'], $payload['doctor_id']))
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

  public function findDoctorPatientById($patientId, $doctorId)
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
    $query = "SELECT 
              doctors.user_id,
              doctors.specialization,
              doctors.id as doctor_id,
              users.name,
              users.email,
              users.dob,
              users.gender

              FROM $this->table 
              INNER JOIN doctors ON 
              $this->table.doctor_id = doctors.id
              INNER JOIN users ON 
              doctors.user_id = users.id
              WHERE doctor_patients.patient_id = :patient_id";

    $stmt = $this->conn->prepare($query);

    $stmt->execute([
      ":patient_id" => $patientId,
    ]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function findPatientsByDoctorId($doctorId)
  {
    $query = "SELECT 
              patients.user_id,
              patients.ward_id,
              patients.id as patient_id,
              users.name,
              users.email,
              users.dob,
              users.gender

              FROM doctor_patients 
              INNER JOIN patients ON 
              doctor_patients.patient_id = patients.id
              INNER JOIN users ON 
              patients.user_id = users.id
              WHERE doctor_patients.doctor_id = :doctor_id";

    $stmt = $this->conn->prepare($query);

    $stmt->execute([
      ":doctor_id" => $doctorId,
    ]);
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function delete($patient_id, $doctor_id)
  {
    $query = "DELETE FROM $this->table WHERE patient_id = :patient_id AND doctor_id = :doctor_id";

    $stmt = $this->conn->prepare($query);

    return $stmt->execute(
      [
        ":patient_id" => $patient_id,
        ":doctor_id" => $doctor_id,
      ]
    );
  }
}