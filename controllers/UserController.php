<?php

class UserController
{
    
    public function actionRegister()
    {
        $name = '';
        $email = '';
        $password = '';
        
        if (isset($_POST['submit'])) {
            
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            
            $errors = false;
            
            if (!User::checkName($name)) {
                $errors[] = 'Имя не должно быть короче 2-х символов';
            }
            
            if (!User::checkEmail($email)) {
                $errors[] = 'Неправильный email';
            }
            
            if (!User::checkPassword($password)) {
                $errors[] = 'Пароль не должен быть короче 6-ти символов';
            }
            
            if (User::checkEmailExists($email)) {
                $errors[] = 'Такой email уже используется';
            }
            
            if ($errors == false) {
                $result = User::register($name, $email, $password);
            }
            
        }
        
        
        require_once(ROOT . '/views/user/register.php');
        
        return true;
    }
    
    public function actionLogin()
    {
        $email = '';
        $password = '';
        
        if (isset($_POST['submit'])) {
            $email = $_POST['email'];
            $password = $_POST['password'];
            
            $errors = false;
            
            //parols validation
            if (!User::checkEmail($email)) {
                $errors[] = 'Неправильный пароль';
            }
            if (!User::checkPassword($password)) {
                $errors[] = 'Пароль не должен быть короче 6-ти символов';
            }
            
            //checking if user exist
            $userId = User::checkUserData($email, $password);
            //var_dump($userId);
            if($userId == false) {
                //If data is wrong - show error
                $errors[] = 'Неправильные данные для входа на сайт'; 
            } else {
                //Если данные правильные, запоминаем пользователя
                User::auth($userId);
                
                //Redirect user in closed side - cabinet
                header("Location: /cabinet/");
            }
        }
        
        require_once(ROOT . '/views/user/login.php');
        
        return true;
    }
    
    /**
    *Remove user's data from session
    */
    public function actionLogout()
    {
        unset($_SESSION['user']);
        header("Location: /");
        //header("Location: $_SERVER['REQUEST_URI']");
    }
}