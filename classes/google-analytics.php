<?php
/**
 * Класс работы с Google Analytics
 */
namespace R2GA;

class GoogleAnalytics implements IHandler
{
	/**
	 * Конструктор
	 */
	public function __construct( )
	{
        // Хук
        add_action( 'r2ga_redirect', array( $this, 'handle' ), 10, 2 );
    }    

    /**
     * Функция обработки переадресации
     * @param string $source  Page path запроса
     * @param string $target  URL переадресации 
     * 
     */
    public function handle( $source, $target )
    {
        // TODO отправка данных в GA
    }  
}