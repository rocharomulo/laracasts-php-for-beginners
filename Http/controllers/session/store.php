<?php

use Core\Authenticator;
use Core\Session;
use Core\ValidationException;
use Http\Forms\LoginForm;

$email = $_POST['email'];
$password = $_POST['password'];

try {
    $form = LoginForm::validate([
        'email' => $email,
        'password' => $password
    ]);
} catch (ValidationException $exception) {
    Session::flash('errors', $form->errors());
    Session::flash('old', [
        'email' => $attributes['email']
    ]);
    return redirect('/login');
}

if ((new Authenticator)->attempt($email, $password)) {
    redirect('/');
}

$form->error('email', 'No matching account found for that email address and password.');
