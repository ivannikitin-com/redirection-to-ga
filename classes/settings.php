<?php
/**
 * Класс установок
 */
namespace R2GA;

class Settings
{
    /**
     * Группа параметров
     */
    const SETTINGS_GROUP = R2GA . '-settings-group';

    /**
     * Название параметров
     */
    const SETTINGS_NAME = R2GA . '-settings';
    
    /**
     * Название параметров
     */
    const SETTINGS_SECTION = R2GA . '-settings-section';
    

	public function __construct() {

		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'init_settings'  ) );
	}

	public function add_admin_menu() {

		add_options_page(
			esc_html__( 'Redirection to Google Analytics', R2GA ),
			esc_html__( 'Redirection to Google Analytics', R2GA ),
			'manage_options',
			'r2ga',
			array( $this, 'page_layout' )
		);
	}

	public function init_settings() {

		register_setting(
			self::SETTINGS_GROUP,
			self::SETTINGS_NAME
		);

		add_settings_section(
			self::SETTINGS_SECTION,
			'',
			false,
			self::SETTINGS_NAME
		);

		add_settings_field(
			'gaId',
			__( 'Google Analytics ID', R2GA ),
			array( $this, 'render_gaId_field' ),
			self::SETTINGS_NAME,
			self::SETTINGS_SECTION
		);
		add_settings_field(
			'sendType',
			__( 'Тип передачи', R2GA ),
			array( $this, 'render_sendType_field' ),
			self::SETTINGS_NAME,
			self::SETTINGS_SECTION
		);
		add_settings_field(
			'eventCategory',
			__( 'Категория события', R2GA ),
			array( $this, 'render_eventCategory_field' ),
			self::SETTINGS_NAME,
			self::SETTINGS_SECTION
		);
		add_settings_field(
			'eventAction',
			__( 'Действие по событию', R2GA ),
			array( $this, 'render_eventAction_field' ),
			self::SETTINGS_NAME,
			self::SETTINGS_SECTION
		);
	}

	public function page_layout() {

		// Check required user capability
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', R2GA ) );
		}

		// Admin Page Layout
		echo '<div class="wrap">' . "\n";
		echo '	<h1>' . get_admin_page_title() . '</h1>' . "\n";
		echo '	<form action="options.php" method="post">' . "\n";

		settings_fields( self::SETTINGS_GROUP );
		do_settings_sections( self::SETTINGS_NAME );
		submit_button();

		echo '	</form>' . "\n";
		echo '</div>' . "\n";
	}

	function render_gaId_field() {

		// Retrieve data from the database.
		$options = get_option( self::SETTINGS_NAME );

		// Set default value.
		$value = isset( $options['gaId'] ) ? $options['gaId'] : '';

		// Field output.
		echo '<input type="text" name="settings_name[gaId]" class="regular-text gaId_field" placeholder="' . esc_attr__( 'UA-XXXXXXXX-X', R2GA ) . '" value="' . esc_attr( $value ) . '">';
		echo '<p class="description">' . __( 'Введите Google Analytics ID', R2GA ) . '</p>';
	}

	function render_sendType_field() {

		// Retrieve data from the database.
		$options = get_option( self::SETTINGS_NAME );

		// Set default value.
		$value = isset( $options['sendType'] ) ? $options['sendType'] : 'pageview';

		// Field output.
		echo '<input type="radio" name="settings_name[sendType]" class="sendType_field" value="' . esc_attr( 'pageview' ) . '" ' . checked( $value, 'pageview', false ) . '> ' . __( 'Просмотр страницы', R2GA ) . '<br>';
		echo '<input type="radio" name="settings_name[sendType]" class="sendType_field" value="' . esc_attr( 'event' ) . '" ' . checked( $value, 'event', false ) . '> ' . __( 'Событие', R2GA ) . '<br>';
		echo '<p class="description">' . __( 'Выберите, что следует передавать', R2GA ) . '</p>';
	}

	function render_eventCategory_field() {

		// Retrieve data from the database.
		$options = get_option( self::SETTINGS_NAME );

		// Set default value.
		$value = isset( $options['eventCategory'] ) ? $options['eventCategory'] : __( 'Служебные', R2GA ) ;

		// Field output.
		echo '<input type="text" name="settings_name[eventCategory]" class="regular-text eventCategory_field" placeholder="' . esc_attr__( 'Служебные', R2GA ) . '" value="' . esc_attr( $value ) . '">';
		echo '<p class="description">' . __( 'Используется, если выбрана передача события', R2GA ) . '</p>';
	}

	function render_eventAction_field() {

		// Retrieve data from the database.
		$options = get_option( self::SETTINGS_NAME );

		// Set default value.
		$value = isset( $options['eventAction'] ) ? $options['eventAction'] : __( 'Переадресация', R2GA ) ;

		// Field output.
		echo '<input type="text" name="settings_name[eventAction]" class="regular-text eventAction_field" placeholder="' . esc_attr__( 'Переадресация', R2GA ) . '" value="' . esc_attr( $value ) . '">';
		echo '<p class="description">' . __( 'Используется, если выбрана передача события', R2GA ) . '</p>';
	}
}