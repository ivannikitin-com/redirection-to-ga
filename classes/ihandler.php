<?php
/**
 * Интерфейс обработчика переадресации
 */
namespace R2GA;

Interface IHandler
{
    /**
     * Функция обработки переадресации
     * @param string $source  Page path запроса
     * @param string $target  URL переадресации 
     * 
     */
    public function handle( $source, $target );
}