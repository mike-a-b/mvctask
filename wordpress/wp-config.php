<?php
/**
 * Основные параметры WordPress.
 *
 * Скрипт для создания wp-config.php использует этот файл в процессе установки.
 * Необязательно использовать веб-интерфейс, можно скопировать файл в "wp-config.php"
 * и заполнить значения вручную.
 *
 * Этот файл содержит следующие параметры:
 *
 * * Настройки MySQL
 * * Секретные ключи
 * * Префикс таблиц базы данных
 * * ABSPATH
 *
 * @link https://ru.wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Параметры базы данных: Эту информацию можно получить у вашего хостинг-провайдера ** //
/** Имя базы данных для WordPress */
define( 'DB_NAME', 'u1598692_wp590' );

/** Имя пользователя базы данных */
define( 'DB_USER', 'root' );

/** Пароль к базе данных */
define( 'DB_PASSWORD', 'm1k31tm1k31t' );

/** Имя сервера базы данных */
define( 'DB_HOST', 'localhost' );

/** Кодировка базы данных для создания таблиц. */
define( 'DB_CHARSET', 'utf8mb4' );

/** Схема сопоставления. Не меняйте, если не уверены. */
define( 'DB_COLLATE', '' );

/**#@+
 * Уникальные ключи и соли для аутентификации.
 *
 * Смените значение каждой константы на уникальную фразу. Можно сгенерировать их с помощью
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ сервиса ключей на WordPress.org}.
 *
 * Можно изменить их, чтобы сделать существующие файлы cookies недействительными.
 * Пользователям потребуется авторизоваться снова.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '(}8n3Hz&$}mU_WHwJ6PH>O+b +q2 eyzuc24 gSNbXuK/Z&9vNwuQYa0{Okuemo)' );
define( 'SECURE_AUTH_KEY',  '?n!rN-hKt!J(94Ewxy]IjhlpC|AL3 f`*ZY9D3dl~0s$RR(}O> UQI I7~VEXL7:' );
define( 'LOGGED_IN_KEY',    '>lJjd|7!W#>9d5D{T<1MSI#UQaBp*`M4DS!{oLX^/03I:<EanuT~nmXAh%-`W3D1' );
define( 'NONCE_KEY',        ';w4RI{4bJn,1FLa;Rk77NcGpgwZ=b.87Vp%X)zpu`3cdD]df<#7i*-1,,8(9I>He' );
define( 'AUTH_SALT',        '%4}GQM~p }E?ER6BQnmNhf{ai581 N<w?ccc*|djrtHhwO$y*&3v+Kd@][me=6eb' );
define( 'SECURE_AUTH_SALT', '~@kB4IA8S@Ww3A+g1Z{AaT7+BvDxt$[j`FpOHy3&1:qku{*w4Fx!ZrB3bfg;w) s' );
define( 'LOGGED_IN_SALT',   'EY$+|@^:%hT$Z=rt~aMUxoUi_[#5IHH{}]Z3-D7Oy  4XM:Pf{?x4*UD^m(k)5T}' );
define( 'NONCE_SALT',       'jG&%Xa:d[FJi.^Y-q.y$Mi.7/golbzW^@M,~6/G%9:dz-ffcOHmjr<|9wZG5z~&8' );

/**#@-*/

/**
 * Префикс таблиц в базе данных WordPress.
 *
 * Можно установить несколько сайтов в одну базу данных, если использовать
 * разные префиксы. Пожалуйста, указывайте только цифры, буквы и знак подчеркивания.
 */
$table_prefix = 'wpsc_';

/**
 * Для разработчиков: Режим отладки WordPress.
 *
 * Измените это значение на true, чтобы включить отображение уведомлений при разработке.
 * Разработчикам плагинов и тем настоятельно рекомендуется использовать WP_DEBUG
 * в своём рабочем окружении.
 *
 * Информацию о других отладочных константах можно найти в документации.
 *
 * @link https://ru.wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Произвольные значения добавляйте между этой строкой и надписью "дальше не редактируем". */



/* Это всё, дальше не редактируем. Успехов! */

/** Абсолютный путь к директории WordPress. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Инициализирует переменные WordPress и подключает файлы. */
require_once ABSPATH . 'wp-settings.php';
define("FS_METHOD", "direct");