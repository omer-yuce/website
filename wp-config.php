<?php
/**
 * WordPress için taban ayar dosyası.
 *
 * Bu dosya şu ayarları içerir: MySQL ayarları, tablo öneki,
 * gizli anahtaralr ve ABSPATH. Daha fazla bilgi için
 * {@link https://codex.wordpress.org/Editing_wp-config.php wp-config.php düzenleme}
 * yardım sayfasına göz atabilirsiniz. MySQL ayarlarınızı servis sağlayıcınızdan edinebilirsiniz.
 *
 * Bu dosya kurulum sırasında wp-config.php dosyasının oluşturulabilmesi için
 * kullanılır. İsterseniz bu dosyayı kopyalayıp, ismini "wp-config.php" olarak değiştirip,
 * değerleri girerek de kullanabilirsiniz.
 *
 * @package WordPress
 */

// ** MySQL ayarları - Bu bilgileri sunucunuzdan alabilirsiniz ** //
/** WordPress için kullanılacak veritabanının adı */
define('WP_HOME','http://localhost:8080/website');
define('WP_SITEURL','http://localhost:8080/website');


define( 'DB_NAME', 'website_db' );

/** MySQL veritabanı kullanıcısı */
define( 'DB_USER', 'root' );

/** MySQL veritabanı parolası */
define( 'DB_PASSWORD', '' );

/** MySQL sunucusu */
define( 'DB_HOST', 'localhost' );

/** Yaratılacak tablolar için veritabanı karakter seti. */
define( 'DB_CHARSET', 'utf8mb4' );

/** Veritabanı karşılaştırma tipi. Herhangi bir şüpheniz varsa bu değeri değiştirmeyin. */
define('DB_COLLATE', '');

/**#@+
 * Eşsiz doğrulama anahtarları.
 *
 * Her anahtar farklı bir karakter kümesi olmalı!
 * {@link http://api.wordpress.org/secret-key/1.1/salt WordPress.org secret-key service} servisini kullanarak yaratabilirsiniz.
 * Çerezleri geçersiz kılmak için istediğiniz zaman bu değerleri değiştirebilirsiniz. Bu tüm kullanıcıların tekrar giriş yapmasını gerektirecektir.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'cVmq-v-^v{(;@D~b#IjjxG?K7*VcP PZ8P>D ;V:uMNSOS}`9/{#zor 4q-0vz{&' );
define( 'SECURE_AUTH_KEY',  ']N2%pG.wC([8-.RVhqdd4tk_&6K-ala^b|%1d)H?t>Q:{%i5NQo6Zd4ILv@}J16e' );
define( 'LOGGED_IN_KEY',    '<8Qb.Um7*G_>Gea!|0=r$XZ,G`[5c,*y%q]!&^2S6JFf/@x.y7c7a?8D[-?qW5rC' );
define( 'NONCE_KEY',        '2vZ)<~ W(Blg:a}86#kv0J!A>`*|[;)?o?=SRcMLilx)J,<l WaZ4/y>([@62,K<' );
define( 'AUTH_SALT',        '<hdma`^ZcplH|hKY>uEf<NG|##%2s!MJD0VVa@12MREFIVxuwt`Nz&+|JMq1&SD8' );
define( 'SECURE_AUTH_SALT', 'LULiZN_pS_q)+wsv`iIwq*e-+QX/V>k:eT]X^J,OZ4#BsZKt]!8h;dqNRy{J1e2d' );
define( 'LOGGED_IN_SALT',   'vG#m8PC+?w0;7kjvdL ~mPn4}!8=G+`~)rtU(%[QhGgHX;$ Zn:>FzAuSDu?NTxt' );
define( 'NONCE_SALT',       'Lx]I`<6@}c^t6JPc1l0=$!?]MnKVd-w~NkY5^2n:,b %4[[A7-K;F%.+>`cZ%L[i' );
/**#@-*/

/**
 * WordPress veritabanı tablo ön eki.
 *
 * Tüm kurulumlara ayrı bir önek vererek bir veritabanına birden fazla kurulum yapabilirsiniz.
 * Sadece rakamlar, harfler ve alt çizgi lütfen.
 */
$table_prefix = 'wp_';

/**
 * Geliştiriciler için: WordPress hata ayıklama modu.
 *
 * Bu değeri "true" yaparak geliştirme sırasında hataların ekrana basılmasını sağlayabilirsiniz.
 * Tema ve eklenti geliştiricilerinin geliştirme aşamasında WP_DEBUG
 * kullanmalarını önemle tavsiye ederiz.
 */
define('WP_DEBUG', false);

/* Hepsi bu kadar. Mutlu bloglamalar! */

/** WordPress dizini için mutlak yol. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** WordPress değişkenlerini ve yollarını kurar. */
require_once(ABSPATH . 'wp-settings.php');
