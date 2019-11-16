<?php
/**
 * Класс RedirectionExtension
 * Расширение плашина redirection. 
 * Использует хуки этого плагина для перехвата переадресации 
 */
namespace R2GA;

class RedirectionExtension
{
    /**
     * URL запроса
     */
    private $url = '';

	/**
	 * Конструктор
	 */
	public function __construct( )
	{
        // Хуки
        add_filter( 'redirection_url_source', array( $this, 'saveSource') );        // The original URL used before matching a request.
        add_filter( 'redirection_url_target', array( $this, 'handleRedirect') );   // The target URL after a request has been matched (and after any regular expression captures have been replaced).
    }
    
    /**
     * Функция сохраняет и обрабатывает URL для переадресации
     * @param string $url   URL запроса
     * @return bool         Return false to stop any redirection
     */
    public function saveSource( $url )
    {
        $this->url = $url;
        return $url;
    }

    /**
     * Функция вызывается, когда правило переадресации найдено
     * @param string $target    URL переадресации
     * @param string $url       URL запроса
     * @return bool             Return false to stop any redirection
     */
    public function handleRedirect( $target )
    {
        do_action( 'r2ga_redirect', $this->url, $target ); 
        return $target;
    }    

}