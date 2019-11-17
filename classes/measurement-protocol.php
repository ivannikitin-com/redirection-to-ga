<?php
/**
 * Класс реализует передачу данных по Мeasurement Protocol
 */
namespace R2GA;

class MeasurementProtocol
{
    /**
     * Конструктор класса
     * @param string $gaId  ID Google Analytics
     * @param string $cid   CID пользователя
     */
    public function __construct( $gaId, $cid )
    {

    }

    /**
     * Отправка просмотра страницы
     * @param string $pagePath   Путь к странице
     * @param string $pageTitle  Название страницы
     */
    public function sendPageView( $pagePath, $pageTitle )
    {

    }

    /**
     * Отправка просмотра страницы
     * @param string $eventCategory   Категория события
     * @param string $eventAction     Действие события
     * @param string $eventLabel      Ярлык события
     */
    public function sendEvent( $eventCategory, $eventAction,  $eventLabel )
    {

    }   
}