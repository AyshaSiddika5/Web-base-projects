<?php
require_once "../config/Database.php";
require_once "../repositories/PdoUserRepository.php";
require_once "../services/AuthService.php";

$db = (new Database())->connect();

$userRepo = new PdoUserRepository($db);
$authService = new AuthService($userRepo);

$name  = $_POST['name'];
$email = $_POST['email'];
$pass  = $_POST['password'];
$role  = $_POST['role'];

$authService->register($name, $email, $pass, $role);

header("Location: login.php");
exit();
