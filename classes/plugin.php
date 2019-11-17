<?php
/**
 * Класс Plugin
 * Основной класс плагина. 
 * Является singleton, то есть обращение из любого места должно быть таким Plugin::get()
 */
namespace R2GA;

// Необходимо доя функции is_plugin_active()
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

class Plugin
{
	/**
	 * Путь к папке плагина
	 */
	public $path;
	
	/**
	 * URL к папке плагина
	 */
	public $url;
	
	/**
	 * Название плагина
	 */
	public $name;
	
	/**
	 * Версия плагина
	 */
	public $version;

	/**
     * @var Plugin
     */
    private static $instance = null;

	/**
     * @var RedirectionExtension
     */
    private $settings = null;

	/**
     * @var RedirectionExtension
     */
    private $redirectionExtension = null;

	/**
     * @var GoogleAnalytics
     */
    private $googleAnalytics = null;

	/**
     * @var Logger
     */
    private  $logger = null;

	/**
	 * Иницмиализация плагина
	 * Должна вызываться только один раз. 
	 * @param string	$path	Путь к папке плагина
	 * @param string	$url	URL к папке плагина
	 * @param string	$meta	Мета-данные плагина
	 */
	public static function init( $path, $url, $meta )
	{
		if ( static::$instance !== null )
			throw new \Exception( __('Объект Plugin уже инициализирован!', R2GA) );
		
		static::$instance = new static( $path, $url, $meta );
	}
	/**
	 * Возвращает объект плагина
	 * @return	Plugin
	 */
	public static function get()
	{
		if ( static::$instance === null )
			throw new \Exception( __('Объект Plugin не инициализирован!', R2GA) ); 
			
		return static::$instance;
	}
	
	/**
	 * Конструктор плагина
	 * @param string	$path	Путь к папке плагина
	 * @param string	$url	URL к папке плагина
	 * @param string	$meta	Мета-данные плагина
	 */
	private function __construct( $path, $url, $meta )
	{
		// Инициализация свойств
		$this->path 	= $path;
		$this->url 		= $url;
		$this->name 	= $meta[ 'Name' ];
		$this->version 	= $meta[ 'Version' ];
		
		// Хуки
		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );
		add_action( 'init', array( $this, 'wp_init' ) );
	}
	
	/**
	 * Плагины загружены
	 */
	public function plugins_loaded()
	{
		// Локализация
		load_plugin_textdomain( R2GA, false, basename( dirname( __FILE__ ) ) . '/lang' );
	}
	
	/**
	 * Хук init
	 */
	public function wp_init()
	{
		// Проверка наличия WC		
		if ( is_plugin_active( 'redirection/redirection.php' ) ) 
        {
			// Активация компонентов плагина
			$this->settings = new Settings();
			$this->redirectionExtension = new RedirectionExtension();
			$this->googleAnalytics = new GoogleAnalytics();
			if ( WP_DEBUG ) $this->logger = new Logger();
			
        }
        else
        {
			add_action( 'admin_notices', array( $this, 'showNoticeNoRedirection' ) );
			return;
		}
	}
	
	/**
	 * Предупреждение об отсутствии Redirection
	 */
	public function showNoticeNoRedirection()
	{
		echo '<div class="notice notice-warning no-redirection"><p>';
		printf( 
			esc_html__( 'Для работы плагина "%s" требуется установить и активировать плагин "Redirection".', R2GA ), 
			$this->name . ' ' . $this->version  
		);
		_e( 'В настоящий момент все функции плагина деактивированы.', R2GA );
		echo '</p></div>';
	}
}