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

  public function index()
  {
    $query = "SELECT id, name, email, dob, gender FROM $this->table";

    $stmt = $this->conn->prepare($query);

    $stmt->execute();


    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function findById($id)
  {
    $query = "SELECT id, name, email, dob, gender FROM $this->table WHERE id = :id";

    $stmt = $this->conn->prepare($query);

    $stmt->execute([
      ":id" => $id,
    ]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
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
              (name, email, password, dob, gender)
              VALUES 
              (:name, :email, :password, :dob, :gender)";

    $stmt = $this->conn->prepare($query);

    return $stmt->execute(
      [
        ":name" => $payload['name'],
        ":email" => $payload['email'],
        ":password" => $payload['password'],
        ":gender" => $payload['gender'],
        ":dob" => $payload['dob']
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

  public function readCurrentUserData()
  {
    $userData = readToken("access_token");

    $userId = $userData['id'];

    $query = "SELECT * FROM $this->table INNER JOIN admins ON $this->table.id = admins.user_id WHERE $this->table.id = :userId";

    $stmt = $this->conn->prepare($query);

    $stmt->execute([
      ":userId" => $userId,
    ]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
  }
}