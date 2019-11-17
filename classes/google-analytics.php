<?php
/**
 * Класс работы с Google Analytics
 */
namespace R2GA;

class GoogleAnalytics implements IHandler
{
    /**
     * CID Cookie
     */
    const CID_COOKIE = '_ga';

    /**
     * Google Analytics ID
     */
    private $id;

    /**
     * Google Analytics CID
     */
    private $cid;

    /**
     * Тип передачи
     */
    private $sendType;

    /**
     * Имя хоста
     */
    private $host;

    /**
     * Категория события
     */
    private $eventCategory;    

    /**
     * Действие события
     */
    private $eventAction;   

    /**
     * Метка события
     */
    private $eventLabel;    

	/**
	 * Конструктор
	 */
	public function __construct()
	{
        // Чтение параметров
        $options = get_option( Settings::SETTINGS_NAME );
        $this->id = isset( $options['gaId'] ) ? $options['gaId'] : '';                                                      // GA ID
        $this->sendType = isset( $options['sendType'] ) ? $options['sendType'] : 'pageview';                                // Тип передачи
        $this->eventCategory = isset( $options['eventCategory'] ) ? $options['eventCategory'] : __( 'Служебные', R2GA ) ;   // Категория события
        $this->eventAction = isset( $options['eventAction'] ) ? $options['eventAction'] : __( 'Переадресация', R2GA )  ;    // Действие события

        if ( !empty( $this->id ) )
        {
            // Инициализация свойств
            $this->cid = $this->getCID();               // CID
            $this->host = $this->getHost();             // Имя хоста

            // Хук
            add_action( 'r2ga_redirect', array( $this, 'handle' ), 10, 2 );            
        }
        else
        {
            // Предупреждение
			add_action( 'admin_notices', array( $this, 'showNoticeNoGAID' ) );
			return;            
        }

    }

    /**
     * Функция возвращает или генерирует CID
     * @return string
     */
    private function getCID()
    {
        if ( isset( $_COOKIE[ self::CID_COOKIE ] ) ) 
        {
            list( $version, $domainDepth, $cid1, $cid2 ) = split('[\.]', $_COOKIE[ self::CID_COOKIE ], 4 );
            return $cid1 . '.' . $cid2;
        }
        else
        {
            // Генерация нового CID
        }

    }

    /**
     * Функция возвращает имя хоста
     * @return string
     */
    private function getHost()
    {

    }  

    /**
     * Функция возвращает имя хоста
     * @param string $source  Page path запроса
     * @param string $target  URL переадресации 
     * @return string
     */
    private function getRedirectionString( $source, $target )
    {
        return $source . ' -> ' .  $target;
    } 

    /**
     * Функция обработки переадресации
     * @param string $source  Page path запроса
     * @param string $target  URL переадресации 
     * 
     */
    public function handle( $source, $target )
    {
        $measurementProtocol = new MeasurementProtocol( $this->id, $this->cid );
        if ( $this->sendType == 'pageview' )
        {
            $title = $this->eventAction . ' ' . $this->getRedirectionString();
            $measurementProtocol->sendPageView( $source, $title );
        }
        else
        {
            $eventLabel = $this->getRedirectionString();
            $measurementProtocol->sendEvent( $this->eventCategory, $this->eventAction,  $eventLabel );
        }
    }
    
	/**
	 * Предупреждение об отсутствии GA ID
	 */
	public function showNoticeNoGAID()
	{
		echo '<div class="notice notice-warning no-redirection"><p>';
		printf( 
			esc_html__( 'Для работы плагина "%s" требуется указать Google Analytics ID на странице настроек. ', R2GA ), 
			Plugin::get()->name  
		);
		_e( 'В настоящий момент функция передачи данных в Google Analytics деактивирована.', R2GA );
		echo '</p></div>';
	}    
}