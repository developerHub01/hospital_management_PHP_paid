<?php

class UserController
{
  // Get all users
  public function getUsers()
  {
    $userModel = new UserModel();
    $users = $userModel->fetchUsers();
    echo json_encode($users);
  }

  // Create a new user
  public function createUser()
  {
    $data = json_decode(file_get_contents("php://input"), true);

    // print_r($data);

    // $userModel = new UserModel();
    // $result = $userModel->addUser($data['name'], $data['email']);
    // echo json_encode($result ? ['message' => 'User created'] : ['message' => 'Failed to create user']);
  }
}