<?php

namespace App\Controllers;

class MainController extends \App\Core\Controller
{
    public function home()
    {
        $categoryModel = new \App\Models\CategoryModel($this->getDatabaseConnection());
        $categories = $categoryModel->getAll();
        $this->set('categories', $categories);


        $staraVrednost = $this->getSession()->get('brojac', 0);
        $novaVrednost = $staraVrednost + 1;
        $this->getSession()->put('brojac', $novaVrednost);

        $this->set('podatak', $novaVrednost);
    }

    public function getRegister()
    {
    }

    public function postRegister()
    {
        $email     = \filter_input(INPUT_POST, 'reg_email', FILTER_SANITIZE_EMAIL);
        $forename  = \filter_input(INPUT_POST, 'reg_forename', FILTER_SANITIZE_STRING);
        $surname   = \filter_input(INPUT_POST, 'reg_surname', FILTER_SANITIZE_STRING);
        $username  = \filter_input(INPUT_POST, 'reg_username', FILTER_SANITIZE_STRING);
        $password1 = \filter_input(INPUT_POST, 'reg_password_1', FILTER_SANITIZE_STRING);
        $password2 = \filter_input(INPUT_POST, 'reg_password_2', FILTER_SANITIZE_STRING);

        // print_r([
        //     $email, $forename, $surname, $username, $password1, $password2
        // ]);

        if ($password1 !== $password2) {
            $this->set('message', 'Error occurred: Passwords do not match');
            return;
        }

        $validanPassword = (new \App\Validators\StringValidator())
            ->setMinLength(7)
            ->setMaxLength(120)
            ->isValid($password1);

        if (!$validanPassword) {
            $this->set('message', 'Error occurred: Password is not in correct format.');
            return;
        }

        $userModel = new \App\Models\UserModel($this->getDatabaseConnection());

        $user = $userModel->getByFieldName('email', $email);
        if ($user) {
            $this->set('message', 'Error occurred: Email already exists!');
            return;
        }

        $user = $userModel->getByFieldName('username', $username);
        if ($user) {
            $this->set('message', 'Error occurred: Username already exists!');
            return;
        }

        $passwordHash = \password_hash($password1, PASSWORD_DEFAULT);

        $userId = $userModel->add([
            'username'      => $username,
            'password_hash' => $passwordHash,
            'email'         => $email,
            'forename'      => $forename,
            'surname'       => $surname
        ]);

        if (!$userId) {
            $this->set('message', 'Error occurred: Failed to register new account.');
            return;
        }

        $this->set('message', 'Succesfull registration!');
    }

    public function getLogin()
    {
    }

    public function postLogin()
    {

        $username  = \filter_input(INPUT_POST, 'login_username', FILTER_SANITIZE_STRING);
        $password  = \filter_input(INPUT_POST, 'login_password', FILTER_SANITIZE_STRING);

        $validanPassword = (new \App\Validators\StringValidator())
            ->setMinLength(7)
            ->setMaxLength(120)
            ->isValid($password);

        if (!$validanPassword) {
            $this->set('message', 'Error occurred: Password is not in correct format.');
            return;
        }

        $userModel = new \App\Models\UserModel($this->getDatabaseConnection());

        $user = $userModel->getByFieldName('username', $username);
        if (!$user) {
            $this->set('message', 'Error occurred: Username does not exists!');
            return;
        }

        if (!password_verify($password, $user->password_hash)) {
            sleep(1);
            $this->set('message', 'Error occurred: Password is not correct!');
            return;
        }

        $this->getSession()->put('user_id', $user->user_id);
        $this->getSession()->save();

        $this->redirect(\Configuration::BASE . '/user/profile');
    }
}
