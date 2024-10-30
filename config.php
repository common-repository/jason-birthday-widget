<?php
//manage_options
if ( !current_user_can( 'publish_posts' ) )
{
    wp_die( esc_html__( 'You do not have sufficient permissions to access this page.' ) );
}

//veo si vienen datos
if ( isset( $_POST['submit'] ) )
{
    
}
?>
<div class="wrap">
    <h2><?php echo JASON_BD_WIDGET_NAME; ?></h2>
    
    <p><?php esc_html_e( "Just go to Appearance/Widgets section and add " . JASON_BD_WIDGET_NAME. " to your sidebar"
            , JASON_BD_TD ); ?></p>
    
    <h2><?php esc_html_e( "About " . JASON_BD_WIDGET_NAME. " plugin", JASON_BD_TD ); ?></h2>
    
    <p><?php esc_html_e( 'Created by ', JASON_BD_TD ); ?>Jason Matamala Gajardo, 
        <a href="mailto:jason.matamala@gmail.com">jason.matamala@gmail.com</a></p>
    
     <p><?php esc_html_e( 'Website ', JASON_BD_TD ); ?><a href="http://codificando.cl/web/?page_id=92" target="_blank">http://codificando.cl/web/?page_id=92</a></p>
     
     <p><?php esc_html_e( 'I am a software developer interested in web development and Android apps development. I work developing personal and enterprise sites. If you are looking for a website developer, you can contact me to my email.', JASON_BD_TD ); ?></p>
    
    <form method="post" action="options.php" style="display: none !important">
    <?php settings_fields( 'jason_bd_option-group' ); ?>
    <?php do_settings_sections( 'jason_bd_option-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">New Option Name</th>
        <td><input type="text" name="option1" value="<?php echo esc_attr( get_option('option1') ); ?>" /></td>
        </tr>
         
        <tr valign="top">
        <th scope="row">Some Other Option</th>
        <td><input type="text" name="option2" value="<?php echo esc_attr( get_option('option2') ); ?>" /></td>
        </tr>
        
        <tr valign="top">
        <th scope="row">Options, Etc.</th>
        <td><input type="text" name="option3" value="<?php echo esc_attr( get_option('option3') ); ?>" /></td>
        </tr>
    </table>
    
    <?php submit_button(); ?>

</form>
</div>