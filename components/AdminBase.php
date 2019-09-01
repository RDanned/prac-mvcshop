<?php
/**
 * Abstract class AdminBase содержит общую логику для контроллеров,
 * которые используются в панели администратора
 */

abstract class AdminBase
{

    /**
     * Метод который проверяет юзера на то, является ли он администратором
     * @return boolean
     */
    public static function checkAdmin()
    {
        //Проверяем авторизован ли юзер. Если нет, он будет переадресован
        $userId = User::checkLogged();

        //Получаем информацию о текущем юзере
        $user = User::getUserById($userId);

        //Если роль текущего пользователя admin, пускаем его в админпанель
        if($user['role'] == 'admin') {
            return true;
        }

        //Иначе завершаем работу с сообщением о закрытом доступе
        die('Access denied');
    }
}