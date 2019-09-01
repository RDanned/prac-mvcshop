<?php 

class User 
{
    public static function register($name, $email, $password) {
        
        $db = Db::getConnection();
        
        $sql = "INSERT INTO user (name, email, password) VALUES (:name, :email, :password)";
        
        $result = $db->prepare($sql);
        $result->bindParam(':name', $name, PDO::PARAM_STR);
        $result->bindParam(':email', $email, PDO::PARAM_STR);
        $result->bindParam(':password', $password, PDO::PARAM_STR);

        return $result->execute();

    }
      /**
       * Edit user's data
       * @param  [[Type]] $id       [[Description]]
       * @param  [[Type]] $name     [[Description]]
       * @param  [[Type]] $password [[Description]]
       * @return [[Type]] [[Description]]
       */
      public static function edit($id, $name, $password) {
        
        $db = Db::getConnection();
        
        $sql = "UPDATE user SET name = :name, password = :password WHERE id = :id";
        
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->bindParam(':name', $name, PDO::PARAM_STR);
        $result->bindParam(':password', $password, PDO::PARAM_STR);

        return $result->execute();

    }
    
    /**
     * Check if exist user with defined $email and $password
     * @param  string $email    
     * @param  string $password 
     * @return mixed : ingeger user id or false
     */
    public static function checkUserData($email, $password)
    {
        $db = Db::getConnection();
        
        $sql = 'SELECT * FROM user WHERE email = :email AND password = :password';
        
        $result = $db->prepare($sql);
        $result->bindParam(':email', $email, PDO::PARAM_STR);
        $result->bindParam(':password', $password, PDO::PARAM_STR);
        $result->execute();
        
        $user = $result->fetch();
        if ($user) {
            return $user['id'];
        }
        
        return false;
    }
    
    /**
     * Write in username
     * @param [[Type]] $userId [[Description]]
     */
    public static function auth($userId) 
    {
        $_SESSION['user'] = $userId;
    }
    
    /**
    *Checks name: doesn't less than 2 symbols
    */
    public static function checkName($name) {
        if (strlen($name)>=2) {
            return true;
        }
        return false;
    }
    
    public static function checkLogged()
    {
        //If session is, return user's id
        if (isset($_SESSION['user'])) {
            return $_SESSION['user'];
        }
        
        header("Location: /user/login");
    }
    
    public static function isGuest()
    {
        if (isset($_SESSION['user'])) {
            return false;
        }
        return true;
        
    }
    /**
    *Checks password: doesn't less than 6 symbols
    */
    public static function checkPassword($password) {
        if (strlen($password) >=6) {
            return true;
        }
        return false;
        
    }
    
    /**
    *Checks email
    */
    public static function checkEmail($email) {
        if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        return false;
    }
    
    public static function checkEmailExists($email) {
        
        $db = Db::getConnection();
        
        $sql = 'SELECT COUNT(*) FROM user WHERE email = :email ';
        
        $result = $db->prepare($sql);
        $result->bindParam(':email', $email, PDO::PARAM_STR);
        $result->execute();
        
        if($result->fetchColumn())
            return true;
        return false;
    }

    public  static function checkPhone($phone)
    {
        if (strlen($phone) >= 10) {
            return true;
        }
        return false;
    }
     /**
    *Returns user's data by id
    *$param integer $id
    */
    public static function getUserById($id)
    {
        
        if($id) {
            $db = Db::getConnection();
            $sql = 'SELECT * FROM user WHERE id = :id';
            
            $result = $db->prepare($sql);
            $result->bindParam(':id', $id, PDO::PARAM_INT);
            
            //show what we want to get the data as array
            $result->setFetchMode(PDO::FETCH_ASSOC);
            $result->execute();
            
            return $result->fetch();
        }
    }
}