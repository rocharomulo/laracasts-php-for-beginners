<?php

use Core\App;
use Core\Database;
use Core\Validator;

$email = $_POST['email'];
$password = $_POST['password'];

//Validation
$errors = [];

if (!Validator::email($email)) {
    $errors['email'] = "Please provide a valid email address.";
}

if (!Validator::string($password, 7, 255)) {
    $errors['password'] = "Please provide a password of at least 7 characters.";
}

if (! empty($errors)) {
    return view('registration/create.view.php', [
        'errors' => $errors
    ]);
}

// verifica se email/usuário já existe no banco de dados

$db = App::resolve(Database::class);

$user = $db->query('select * from users where email = :email', [
    'email' => $email
])->find();

// se já houver um usuário cadastrado, enviar para tela de login
if ($user) {
    header('location: /');
}

// insere novo usuário no banco de dados

$db->query('insert into users(email, password) values (:email, :password)', [
    'email' => $email,
    'password' => password_hash($password, PASSWORD_BCRYPT)
]);

// cria sessão 
session_start();

login($user);

// send to dashboard (usuário já logado)
header('location: /');
exit;
