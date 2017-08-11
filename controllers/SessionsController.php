<?php

namespace Controllers;

class SessionsController extends BaseController {

    public function create() {
        if (isset($_POST['password']) && isset($_POST['username'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $stmt     = \DB::connect()->prepare('SELECT * FROM users WHERE (username = :username) AND (password = PASSWORD(:password))');
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $password);
            $stmt->execute();
            $user = $stmt->fetchObject('\Models\User');
            if ($user) {
                $_SESSION['user'] = $user;
            } else {
                $_SESSION['error'] = 'Invalid username or password';
            }
        } else {
            $_SESSION['error'] = 'Username and password are required';
        }

        // redirect to the previous page
        $this->redirect_to($_SERVER['HTTP_REFERER']);
    }

    public function destroy() {
        session_destroy();
        $this->redirect_to('/');
    }
}
