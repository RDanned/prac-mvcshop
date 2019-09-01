<?php

class Cart
{
    
    public static function addProduct($id)
    {
        
        $id = intval($id);
        
        //empty array for goods in cart
        $productsInCart = array();
        
        //If goods already in cart (they contains in session )
        if (isset($_SESSION['products'])) {
            //Than fill our array with goods in
            $productsInCart = $_SESSION['products'];
        }
        
        //If good in the cart, but was added one more time, than increase quantity
        if (array_key_exists($id, $productsInCart)) {
            $productsInCart[$id] ++;
        } else {
            //add new good in the cart
            $productsInCart[$id] = 1;
        }
        
        $_SESSION['products'] = $productsInCart;

        return self::countItems();
    }

    public static function deleteProduct($id)
    {
        $id = intval($id);

        unset($_SESSION['products'][$id]);
        return true;
    }
    
    public static function countItems()
    {
        if(isset($_SESSION['products'])) {
            $count = 0;
            foreach ($_SESSION['products'] as $id => $quantity){
                $count = $count + $quantity;
            }
            return $count;
            
        } else {
            return 0;
        }
    }
    
    public static function getProducts()
    {
        if(isset($_SESSION['products'])) {
            return $_SESSION['products'];
        }
        return false;
    }
    
    public static function getTotalPrice($products)
    {
        $productsInCart = self::getProducts();
        
        $total = 0;
        
        if($productsInCart) {
            foreach ($products as $item) {
                $total += $item['price'] * $productsInCart[$item['id']];
            }
        }
        
        return $total;
    }

    public static function clear()
    {
        if(isset($_SESSION['products'])) {
            unset($_SESSION['products']);
        }
    }
}