
<?php
/**
 * Plugin Name:       Redirection to GA
 * Plugin URI:        https://github.com/ivannikitin-com/in-faq
 * Description:       Фиксация переадресаций плагина Redirection в Google Analytics
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.0
 * Author:            Иван Никитин и партнеры
 * Author URI:        https://ivannikitin.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       r2ga
 * Domain Path:       /lang
 */
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

define( 'R2GA', 'redirection-to-ga' );

/* Файлы плагина */
require 'classes/plugin.php';
require 'classes/settings.php';
require 'classes/redirection-extension.php';
require 'classes/ihandler.php';
require 'classes/measurement-protocol.php';
require 'classes/logger.php';
require 'classes/google-analytics.php';


/* Запуск плагина */
R2GA\Plugin::init( 
	plugin_dir_path( __FILE__ ), 			// Путь к папке плагина
	plugin_dir_url( __FILE__ ), 			// URL к папке плагина
	get_file_data( __FILE__, array(			// Мета-данные из заголовка плагина
			'Name' 		=> 'Plugin Name',	// Название Пдагина
			'Version' 	=> 'Version',		// Версия плагина
        ) ) );
