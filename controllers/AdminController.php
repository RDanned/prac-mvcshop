<?php
/**
 * Created by PhpStorm.
 * User: Ruslan
 * Date: 16.01.2019
 * Time: 16:18
 */

class AdminController extends AdminBase
{
    /**
     * Action для стартовой страницы "Панель администратора"
     */
    public function actionIndex()
    {
        //Проверка доступа
        self::checkAdmin();

        //Подключение вида
        require_once (ROOT . '/views/admin/index.php');
        return true;
    }
}