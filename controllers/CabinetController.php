<?php

class CabinetController
{
    
    public function actionIndex()
    {
        //get user's id from session
        $userId = User::checkLogged();
        
        //get user's data from DB
        $user = User::getUserById($userId);
        
        require_once(ROOT . '/views/cabinet/index.php');
        
        return true;
    }
    
    public function actionEdit()
    {
        //Get user's id from session
        $userId = User::checkLogged();
        
        //get users data from DB
        $user = User::getUserById($userId);
        
        $name = $user['name'];
        $password = $user['password'];
        
        $result = false;
        
        if (isset($_POST['submit'])) {
            
            $name = $_POST['name'];
            $password = $_POST['password'];
            
            $errors = false;
            
            if (!User::checkName($name)) {
                $errors[] = 'Имя не должно быть короче 2-х символов';
            }
            
            if (!User::checkPassword($password)) {
                $errors[] = 'Пароль не должен быть короче 6-ти символов';
            }
            
            if ($errors == false) {
                $result = User::edit($userId, $name, $password);
            }
            
        }
        
        
        require_once(ROOT . '/views/cabinet/edit.php');
        
        return true;
    }
}