<?php
require_once dirname( __FILE__ ) . '/classes/JasonUtils.php';
//manage_options
if ( !current_user_can( 'publish_posts' ) )
{
    wp_die( esc_html__( 'You do not have sufficient permissions to access this page.' ) );
}

//veo si vienen datos (all must be integers)
$mes_desde = isset( $_REQUEST['md'] ) ? intval( $_REQUEST['md'] ) : (int) date( 'm' );
$mes_hasta = isset( $_REQUEST['mh'] ) ? intval( $_REQUEST['mh'] ) : (int) date( 'm' );

$d = isset( $_REQUEST['d'] ) ? intval( $_REQUEST['d'] ) : 0;
$h = isset( $_REQUEST['h'] ) ? intval( $_REQUEST['h'] ) : 100;

//validate
if ( $mes_desde <= 0 || $mes_desde > 12 )
    $mes_desde = (int) date( 'm' );
if ( $mes_hasta <= 0 || $mes_hasta > 12 )
    $mes_hasta = (int) date( 'm' );

if ( $d < 0 )
    $d = 0;
if ( $h <= 0 )
    $h = 100;

$table = new BdaysTable();

$result = $table->getallByDate( $mes_desde, $mes_hasta, $d, $h );

$ical_file = JasonUtils::bdToIcalFromResults( $result );


/* $meses = array( 1 => 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo',
  'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre' ); */

$meses = array( 1 => esc_html__( 'January', JASON_BD_TD ), esc_html__( 'February', JASON_BD_TD ),
    esc_html__( 'March', JASON_BD_TD ), esc_html__( 'April', JASON_BD_TD ), esc_html__( 'May', JASON_BD_TD ),
    esc_html__( 'June', JASON_BD_TD ), esc_html__( 'July', JASON_BD_TD ), esc_html__( 'August', JASON_BD_TD ),
    esc_html__( 'September', JASON_BD_TD ), esc_html__( 'October', JASON_BD_TD ), esc_html__( 'November', JASON_BD_TD ),
    esc_html__( 'December', JASON_BD_TD ) );
?>
<div class="wrap">
    <h2><?php echo JASON_BD_WIDGET_NAME; ?></h2>
    <h3><?php
        echo esc_html__( 'Birthdays list between ', JASON_BD_TD ) . ' ' . $meses[$mes_desde] . ' ' . esc_html__( 'and', JASON_BD_TD )
        . ' ' . $meses[$mes_hasta];
        ?></h3>

    <form method="GET" >
        <p><?php esc_html_e( 'From', JASON_BD_TD ); ?> <select id="md" name="md">
                <?php foreach ( $meses as $k => $m ): ?>
                    <?php $sel = ($k == $mes_desde) ? 'selected' : ''; ?>
                    <option <?php echo $sel; ?> value='<?php echo $k; ?>'><?php echo $m; ?></option>
                <?php endforeach;
                ?> 
            </select> <?php esc_html_e( 'to', JASON_BD_TD ); ?> <select id="mh" name="mh">
                <?php foreach ( $meses as $k => $m ): ?>
                    <?php $sel = ($k == $mes_hasta) ? 'selected' : ''; ?>
                    <option <?php echo $sel; ?> value='<?php echo $k; ?>'><?php echo $m; ?></option>
                <?php endforeach;
                ?> 
            </select></p>
        <p><input type="submit" name="submit" value="<?php esc_html_e( 'Search', JASON_BD_TD ); ?>"/></p>
        <input type="hidden" name="page" value="<?php echo isset( $_REQUEST['page'] ) ? $_REQUEST['page'] : ''; ?>" />

    </form>
    <?php if ( !empty( $result ) ): ?>       

        <p><a href="<?php echo $ical_file; ?>"><?php esc_html_e( 'Download iCalendar file', JASON_BD_TD ); ?></a></p>

        <table class="wp-list-table widefat fixed striped jason_bday_table">
            <thead>
                <tr>
                    <th><?php esc_html_e( 'User', JASON_BD_TD ); ?></th>
                    <th><?php esc_html_e( 'Birthday', JASON_BD_TD ); ?></th>                                   
                </tr>
            </thead>
            <tbody>
                <?php foreach ( $result as $r ): ?>
                    <?php
                    $user_id = $r['user_id'];
                    $user_profile_link = get_author_posts_url( $user_id );
                    $user_name = $r['display_name'];
                    $fecha = date( 'd-m-Y', strtotime( $r['bday_date'] ) );
                    ?>
                    <tr>
                        <td> <a href="<?php echo $user_profile_link; ?>"><?php echo $user_name; ?></a></td>
                        <td><?php echo $fecha; ?></td>                                       
                    </tr>

                <?php endforeach; ?>

            </tbody>
        </table>
    <?php else: ?>
        <p><?php esc_html_e( 'No birthdays found', JASON_BD_TD ); ?></p>
    <?php endif; ?>

</div>