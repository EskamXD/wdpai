<?php

require_once 'AppController.php';
require_once __DIR__.'/../models/User.php';
require_once 'DatabaseController.php';

class SecurityController extends AppController {
    private $databaseController;

    public function __construct() {
        parent::__construct();
        $this->databaseController = new DatabaseController();
    }

    public function login() {
        if (!$this->isPost()) {
            return $this->render('loginPage', ['messages' => ['Błąd logowania!']]);
        }

        $login = $_POST['login-input'];
        $password = $_POST['password-input'];

        // Pobierz użytkownika z bazy danych przy użyciu DatabaseController.
        $user = $this->databaseController->getUserByLogin($login);

        if (!$user) {
            $this->render('loginPage', ['messages' => ['Nieprawidłowy login!']]);
            return;
        }

        // Sprawdź, czy hasło pasuje do hasła w bazie danych.
        if (!password_verify($password, $user->getPassword())) {
            $this->render('loginPage', ['messages' => ['Nieprawidłowe hasło!']]);
            return;
        }

        // Udało się zalogować, ustaw sesję z danymi użytkownika.
        $_SESSION['user'] = $user->getLogin();
        $_SESSION['email'] = $user->getEmail();
        $_SESSION['name'] = $user->getName();
        $_SESSION['surname'] = $user->getSurname();

        return $this->render('home', ['messages' => ['Witaj '.$user->getName().'!</br>', 'Umów się!']]);
    }
}
