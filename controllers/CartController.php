<?php

class CartController
{
    
    public function actionAdd($id)
    {
        
        //Add good in cart
        Cart::addProduct($id);
        
        //return user on page
        $referrer = $_SERVER['HTTP_REFERER'];
        header("Location: $referrer");
    }

    public function actionDelete($id)
    {
        //Удалить товар из корзины
        Cart::deleteProduct($id);
        //Возвращаем пользователя на страницу
        header ("Location: /cart/");
    }
    
    public function actionAddAjax($id)
    {
        //add good in cart
        echo Cart::addProduct($id);
        return true;
    }
    
    public function actionIndex()
    {
        $categories     = array();
        $categories     = Category::getCategoriesList();
        
        $productsInCart = false;
        
        //get data from cart
        $productsInCart = Cart::getProducts();
        
        if ($productsInCart) {
            //get full data about goods for list
            $productsIds = array_keys($productsInCart);
            $products    = Product::getProductsByIds($productsIds);
            
            //get general cost of goods
            $totalPrice  = Cart::getTotalPrice($products);
        }
        
        require_once(ROOT . '/views/cart/index.php');
        
        return true;
    }
    
    public function actionCheckout()
    {
        
        //Categories list  for left menu
        $categories = array();
        $categories = Category::getCategoriesList();
        
        //Stature of success order
        $result = false;

        //Is form sended?
        if (isset($_POST['submit'])) {
            //Is form sended? - Yes

            //Read data from form
            $userName    = $_POST['userName'];
            $userPhone   = $_POST['userPhone'];
            $userComment = $_POST['userComment'];

            //fields validation
            $errors = false;
            if (!User::checkName($userName)) 
                $errors = 'Неправильное имя';
            if (!User::checkPhone($userPhone))
                $errors = 'Неправильный телефон';
            //Is form correct?
            if ($errors == false) {
                //Is form correct? - Yes
                //Save order in database
                
                //Pick information about order
                $productsInCart = Cart::getProducts();
                if (User::isGuest()) {
                    $userId = false;
                } else {
                    $userId = User::checkLogged();
                }

                //save order in DB
                $result = Order::save($userName, $userPhone, $userComment, $userId, $productsInCart);

                if ($result) {
                    //Notify admin about new order
                    $adminEmail = 'ruslaneh@mail.ru';
                    $message    = 'http://shopmvc/admin/orders';
                    $subject    = 'New Order';
                    mail($adminEmail, $subject, $message);

                    //Clear cart
                    Cart::clear();
                }
            } else {
                //Is form correct? - No
                
                //Results: general cost, goods quantity
                $productsInCart = Cart::getProducts();
                $productsIds    = array_key($productsInCart);
                $products       = Product::getProductsByIds;
                $totalPrice     = Cart::getTotalPrice($products);
                $totalQuantity  = Cart::countItems();
            }
        } else {
            //Is form correct? - no
            
            //Get data from cart
            $productsInCart = Cart::getProducts();

            //Are goods in cart?
            if ($productsInCart == false) {
                //В корзине есть товары? - нет
                //Отправляем пользователя на главную искать товары
                header("Location: /");
            } else {
                //В корзине есть товары? - Да
                
                //Results:general cost, number of goods
                $productsIds   = array_keys($productsInCart);
                $products      = Product::getProductsByIds($productsIds);
                $totalPrice    = Cart::getTotalPrice($products);
                $totalQuantity = Cart::countItems();
                
                $userName      = false;
                $userPhone     = false;
                $userComment   = false;

                //Пользователь автризован?
                if (User::isGuest()) {
                    //Нет
                    //Значения для формы пустые
                } else {
                    //Да, авторизовался
                    //Получаем информацию о юзере из БД по id
                    $userId = User::checkLogged();
                    $user = User::getUserById($userId);
                    //Подставляем в форму
                    $userName = $user['name'];
                }
            }
        }

        require_once(ROOT . '/views/cart/checkout.php');

        return true;
    }
}