<?php
/**
 * Plugin Name: Jason Birthday Widget
 * Plugin URI: http://codificando.cl
 * Description: 
 * Version: 1.0.0
 * Author: Jason Matamala Gajardo
 * Author URI: http://codificando.cl
 * Text Domain: jason-birthday
 * License: GPL2
 */
//no direct access allowed
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
//nombre de la tabla donde se gurdan lls miembor s del partido
define( 'JASON_BIRTHDAY_TABLE', 'jason_birthday_table' );
define( 'JASON_BD_WIDGET_NAME', 'Jason Birthday Widget' );
//text domain
define( 'JASON_BD_TD', 'jason-birthday' );

define( 'JASON_BD_TEMPLATES_COUNT', 3 );
define( 'JASON_BD_YES', 1 );
define( 'JASON_BD_NO', 2 );

define( 'JASON_BD_DATE_FORMAT', 'd-m-Y' );

require_once dirname( __FILE__ ) . '/classes/BdaysTable.php';

ini_set( 'memory_limit', '512M' );

//versin de la tabla de la db
global $jason_bd_db_version;
$jason_bd_db_version = '1.0';

/**
 * crea la tabla para guardar miembros del partido
 * @global <type> $wpdb
 * @global string $jason_miembros_db_version
 */
function jason_bd_install( $delete_old_table = false )
{
    global $wpdb;
    global $jason_bd_db_version;

    $table_name = $wpdb->prefix . JASON_BIRTHDAY_TABLE;
    $charset_collate = $wpdb->get_charset_collate();

    if ( $delete_old_table )
    {
        $sql = "DROP TABLE IF_EXISTS $table_name;";
        $e = $wpdb->query( $sql );
    }
    $sql = "CREATE TABLE `$table_name` (
                `user_id` BIGINT(20) UNSIGNED NOT NULL,
                `bday_date` DATE ,
                `day` INTEGER UNSIGNED,
                `month` INTEGER UNSIGNED,
                `year` INTEGER UNSIGNED,
                `show_bday` SMALLINT UNSIGNED               
                PRIMARY KEY (`user_id`)
              )$charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );

    add_option( 'jason_bd_db_version', $jason_bd_db_version );
}

//hooks  para que se llamen las funciones al activar el plugin
register_activation_hook( __FILE__, 'jason_bd_install' );

/**
 * para saber si hay que upgradear la db
 * @global <type> $jal_db_version
 */
function jason_bd_update_db_check()
{
    global $jason_bd_db_version;
    if ( get_site_option( 'jason_miembros_db_version' ) != $jason_bd_db_version )
    {
        jason_bd_install( true );
    }
}

add_action( 'plugins_loaded', 'jason_bd_update_db_check' );

/* * *
 * creo admin page para importar los miembros del partido
 *
 *
 */

add_action( 'admin_menu', 'jason_bd_admin_menu' );

/** Step 1. */
function jason_bd_register_mysettings()
{ // whitelist options
   /* register_setting( 'jason_bd_option-group', 'option1' );
    register_setting( 'jason_bd_option-group', 'option2' );
    register_setting( 'jason_bd_option-group', 'option3' );*/
}

function jason_bd_admin_menu()
{
    $slug = 'jason-birthday-widget-settings';

    add_menu_page( esc_html__( "Settings", JASON_BD_TD ), JASON_BD_WIDGET_NAME, 0, $slug, "jason_bd_admin_options" );

    add_submenu_page( $slug, esc_html__( "Birthdays list", JASON_BD_TD ), esc_html__( "Birthdays list", JASON_BD_TD ), 0, "jason_bd_admin_bday_list", "jason_bd_admin_birthday_list" );

    if ( is_admin() )
    { // admin actions
        //    add_action( 'admin_menu', 'add_mymenu' );
        add_action( 'admin_init', 'jason_bd_register_mysettings' );
    }
    else
    {
        // non-admin enqueues, actions, and filters
    }
}

/** Step 3. */
function jason_bd_admin_options()
{
    require_once dirname( __FILE__ ) . '/config.php';
}

function jason_bd_admin_birthday_list()
{
    require_once dirname( __FILE__ ) . '/listbirthdays.php';
}

/**
 * limipia tabla de miembros
 */
function jason_db_clear_data()
{
    //limpio datos de la tabla
    global $wpdb;
    $table_name = $wpdb->prefix . JASON_BIRTHDAY_TABLE;
    $sql = "TRUNCATE TABLE $table_name;";
    $e = $wpdb->query( $sql );
}

function jason_bd_insert_birthday( $comuna, $rut, $dv, $nombres, $apellidos )
{
    global $wpdb;

    $table_name = $wpdb->prefix . JASON_MIEMBROS_TABLE;

    $res = $wpdb->insert(
            $table_name, array(
        'rut' => $rut,
        'dv' => $dv,
        'nombres' => $nombres,
        'apellidos' => $apellidos,
        'comuna' => $comuna,
            )
    );
    //si es false, entonces hubo un error
    return $res;
}

/**
 * FIN creo admin page para importar miembros del partido
 *
 */

/**
 * shortcodes
 */
//[foobar]
function jason_bd_shortcode( $atts )
{
    require_once dirname( __FILE__ ) . '/shortcode.php';
}

add_shortcode( 'jason_bd', 'jason_bd_shortcode' );

/*
 * widget add
 */

// register Foo_Widget widget
function jason_bd_register_bday_widgets()
{
    require_once dirname( __FILE__ ) . '/widget.php';
    register_widget( 'Jason_Bday_Widget' );
}

add_action( 'widgets_init', 'jason_bd_register_bday_widgets' );

/**
 * add fields to profile user
 */
function jason_bd_custom_user_profile_fields( $user )
{
    ?>
    <h2><?php esc_html_e( 'My birthday', JASON_BD_TD ); ?></h2>
    <table class="form-table">
        <tr>
            <th>
                <label for="bday_user"><?php esc_html_e( 'Birthday', JASON_BD_TD ); ?></label>
            </th>
            <td>
                <input type="text" name="bday_user" id="bday_user" value="<?php echo esc_attr( get_the_author_meta( 'bday_user', $user->ID ) ); ?>" class="regular-text" />
                <br><span class="description"><?php esc_html_e( 'Your birthday date.', JASON_BD_TD ); ?></span>
            </td>
        </tr>
        <tr style="display: none;">
            <th>
                <label for="bday_show_to_others"><?php esc_html_e( 'Show my birthday to other users?', JASON_BD_TD ); ?></label>
            </th>
            <td>
                <input type="text" name="bday_show_to_others" id="bday_show_to_others" value="<?php echo esc_attr( get_the_author_meta( 'bday_show_to_others', $user->ID ) ); ?>" class="regular-text" />
                <br><span class="description"><?php esc_html_e( 'Do you want other users see when is your birthday date?', JASON_BD_TD ); ?></span>

            </td>
        </tr>
    </table>
    <?php
}

add_action( 'show_user_profile', 'jason_bd_custom_user_profile_fields' );
add_action( 'edit_user_profile', 'jason_bd_custom_user_profile_fields' );

/**
 * guardo bday desde el profile en tabla aparte
 */
function jason_bd_update_extra_profile_fields( $user_id )
{
    if ( current_user_can( 'edit_user', $user_id ) )
    {
        $bday = $_POST['bday_user'];
        $bday_show_to_others = $_POST['bday_show_to_others'];
        
        //validation
        

        $bday_table = new BdaysTable();
        try {
            $bday_table->insert_or_update( $user_id, $bday, $bday_show_to_others );
        } catch ( Exception $exc ) {
            echo $exc->getTraceAsString();
        }
        update_user_meta( $user_id, 'bday_user', $bday );
        update_user_meta( $user_id, 'bday_show_to_others', $bday_show_to_others );


        //
    }
}

add_action( 'edit_user_profile_update', 'jason_bd_update_extra_profile_fields' );
add_action( 'personal_options_update', 'jason_bd_update_extra_profile_fields' );

/**
 * agrego jquery ui a edit profile user page
 */
function jason_bd_add_admin_scripts( $hook )
{
    //solo para edir profil
    if ( 'user-edit.php' != $hook && 'edit.php' != $hook && 'profile.php' != $hook )
    {
        return;
    }

    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'jquery-ui-core' );
    wp_enqueue_script( 'jquery-ui-datepicker' );

   // wp_enqueue_script( 'jquery-ui-jason-bd', plugin_dir_url( __FILE__ ) . 'js/jquery-ui/jquery-ui.min.js' );
    wp_enqueue_script( 'admin_jason_bd', plugin_dir_url( __FILE__ ) . 'js/jason_bday_admin.js' );

//    wp_enqueue_style( 'jquery-ui-style-jason-bd', plugin_dir_url( __FILE__ ) . 'js/jquery-ui/jquery-ui.theme.min.css' );
}

add_action( 'admin_enqueue_scripts', 'jason_bd_add_admin_scripts' );

/**
 * css y js para el sitio
 * @param type $hook
 */
function jason_bd_add_site_scripts( $hook )
{
    //   wp_enqueue_script( "jquery" );
    //   wp_enqueue_script( 'jquery-ui-jason-bd', plugin_dir_url( __FILE__ ) . 'js/jquery-ui/jquery-ui.min.js' );
    //  wp_enqueue_script( 'admin_jason_bd', plugin_dir_url( __FILE__ ) . 'js/jason_bday_admin.js' );

    wp_enqueue_style( 'jason-bd-site', plugin_dir_url( __FILE__ ) . 'css/jason_bday_site.css' );
}

add_action( 'wp_enqueue_scripts', 'jason_bd_add_site_scripts' );

/**
 * localization
 */
function jason_bd_load_textdomain()
{
    load_plugin_textdomain( JASON_BD_TD, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

add_action( 'plugins_loaded', 'jason_bd_load_textdomain' );
?>
