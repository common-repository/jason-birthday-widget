<?php
require_once dirname( __FILE__ ) . '/classes/TemplateManager.php';
require_once dirname( __FILE__ ) . '/classes/JasonUtils.php';

class Jason_Bday_Widget extends WP_Widget
{

    /**
     * Register widget with WordPress.
     */
    function __construct()
    {
        parent::__construct(
                'jason_birthday_widget', // Base ID
                esc_html__( JASON_BD_WIDGET_NAME, 'text_domain' ), // Name
                array( 'description' => esc_html__( 'List user birthdays', 'text_domain' ), ) // Args
        );
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance )
    {
        $title = !empty( $instance['title'] ) ? $instance['title'] : '';
        $pasados_bdays_a_mostrar = !empty( $instance['pasados_bdays_a_mostrar'] ) ? $instance['pasados_bdays_a_mostrar'] : 0;
        $next_bdays_a_mostrar = !empty( $instance['next_bdays_a_mostrar'] ) ? $instance['next_bdays_a_mostrar'] : 0;
        $template = !empty( $instance['template'] ) ? $instance['template'] : 1;

        $avatar_show_bd_today = !empty( $instance['avatar_show_bd_today'] ) ? $instance['avatar_show_bd_today'] : 1;
        $avatar_show_bd_past = !empty( $instance['avatar_show_bd_past'] ) ? $instance['avatar_show_bd_past'] : 1;
        $avatar_show_bd_next = !empty( $instance['avatar_show_bd_next'] ) ? $instance['avatar_show_bd_next'] : 1;



        $title = apply_filters( 'widget_title', $title );

        echo $args['before_widget'];
        if ( !empty( $title ) )
        {
            echo $args['before_title'] . $title . $args['after_title'];
        }
        //fecha de hoy
        $ddmmyyy = date( 'd-m-Y' );
        $table = new BdaysTable();
        $bday_users_today = $table->getBDayUsers( );
        $bday_users_past = $table->getPastBDayUsers( $ddmmyyy, $pasados_bdays_a_mostrar );
        $bday_users_next = $table->getNextBDayUsers( $ddmmyyy, $next_bdays_a_mostrar );

        $tm = new TemplateManager();
        $tm->getTemplate( $bday_users_today, $bday_users_past, $bday_users_next, $template, $avatar_show_bd_today, $avatar_show_bd_past, $avatar_show_bd_next );

        //cuerpo del widget
        //  echo esc_html__( "past: $pasados_bdays_a_mostrar, next: $next_bdays_a_mostrar, template: $template", 'text_domain' );


        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance )
    {
        $title = !empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'title', 'text_domain' );
        $pasados_bdays_a_mostrar = !empty( $instance['pasados_bdays_a_mostrar'] ) ? $instance['pasados_bdays_a_mostrar'] : '2';
        $next_bdays_a_mostrar = !empty( $instance['next_bdays_a_mostrar'] ) ? $instance['next_bdays_a_mostrar'] : '3';
        $template = !empty( $instance['template'] ) ? $instance['template'] : 1;
        //mostrar avatars?
        $avatar_show_bd_today = !empty( $instance['avatar_show_bd_today'] ) ? $instance['avatar_show_bd_today'] : 1;
        $avatar_show_bd_past = !empty( $instance['avatar_show_bd_past'] ) ? $instance['avatar_show_bd_past'] : 1;
        $avatar_show_bd_next = !empty( $instance['avatar_show_bd_next'] ) ? $instance['avatar_show_bd_next'] : 1;

        $template_arr = TemplateManager::getTemplates();
        $arr_yes_no = array( 1 => esc_html__( 'yes', JASON_BD_TD ), esc_html__( 'no', JASON_BD_TD ) );
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title:', JASON_BD_TD ); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'pasados_bdays_a_mostrar' ); ?>"><?php esc_html_e( 'Past birthdays to show:', JASON_BD_TD ); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id( 'pasados_bdays_a_mostrar' ); ?>" name="<?php echo $this->get_field_name( 'pasados_bdays_a_mostrar' ); ?>" type="text" value="<?php echo esc_attr( $pasados_bdays_a_mostrar ); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'next_bdays_a_mostrar' ); ?>"><?php esc_html_e( 'Next birthdays to show:', JASON_BD_TD ); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id( 'next_bdays_a_mostrar' ); ?>" name="<?php echo $this->get_field_name( 'next_bdays_a_mostrar' ); ?>" type="text" value="<?php echo esc_attr( $next_bdays_a_mostrar ); ?>">
        </p>




        <p>
            <label for="<?php echo $this->get_field_id( 'avatar_show_bd_today' ); ?>"><?php esc_html_e( "Show avatar for today's birthdays?:", JASON_BD_TD ); ?></label> 
            <select class="widefat" id="<?php echo $this->get_field_id( 'avatar_show_bd_today' ); ?>" name="<?php echo $this->get_field_name( 'avatar_show_bd_today' ); ?>">
                <?php foreach ( $arr_yes_no as $t => $v ): ?>
                    <?php
                    $sel = ($t == $avatar_show_bd_today) ? 'selected' : '';
                    ?>
                    <option <?php echo $sel; ?> value="<?php echo $t; ?>"><?php echo $v; ?></option>
                <?php endforeach; ?>
            </select>        
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'avatar_show_bd_past' ); ?>"><?php esc_html_e( "Show avatar for past birthdays?:", JASON_BD_TD ); ?></label> 
            <select class="widefat" id="<?php echo $this->get_field_id( 'avatar_show_bd_past' ); ?>" name="<?php echo $this->get_field_name( 'avatar_show_bd_past' ); ?>">
                <?php foreach ( $arr_yes_no as $t => $v ): ?>
                    <?php
                    $sel = ($t == $avatar_show_bd_past) ? 'selected' : '';
                    ?>
                    <option <?php echo $sel; ?> value="<?php echo $t; ?>"><?php echo $v; ?></option>
                <?php endforeach; ?>
            </select>        
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'avatar_show_bd_next' ); ?>"><?php esc_html_e( "Show avatar for next birthdays?:", JASON_BD_TD ); ?></label> 
            <select class="widefat" id="<?php echo $this->get_field_id( 'avatar_show_bd_next' ); ?>" name="<?php echo $this->get_field_name( 'avatar_show_bd_next' ); ?>">
                <?php foreach ( $arr_yes_no as $t => $v ): ?>
                    <?php
                    $sel = ($t == $avatar_show_bd_next) ? 'selected' : '';
                    ?>
                    <option <?php echo $sel; ?> value="<?php echo $t; ?>"><?php echo $v; ?></option>
                <?php endforeach; ?>
            </select>        
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'template' ); ?>"><?php esc_html_e( 'Template:', JASON_BD_TD ); ?></label> 
            <select class="widefat" id="<?php echo $this->get_field_id( 'template' ); ?>" name="<?php echo $this->get_field_name( 'template' ); ?>">
                <?php foreach ( $template_arr as $t ): ?>
                    <?php
                    $sel = ($t == $template) ? 'selected' : '';
                    ?>
                    <option <?php echo $sel; ?> value="<?php echo $t; ?>"><?php echo esc_html__( 'Template', 'text_domain' ) . " $t"; ?></option>
                <?php endforeach; ?>
            </select>        
        </p>

        <?php
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update( $new_instance, $old_instance )
    {


        $title = (!empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

        $pasados_bdays_a_mostrar = (!empty( $new_instance['pasados_bdays_a_mostrar'] ) ) ? strip_tags( $new_instance['pasados_bdays_a_mostrar'] ) : 0;
        $pasados_bdays_a_mostrar = intval( $pasados_bdays_a_mostrar );

        $next_bdays_a_mostrar = (!empty( $new_instance['next_bdays_a_mostrar'] ) ) ? strip_tags( $new_instance['next_bdays_a_mostrar'] ) : 0;
        $next_bdays_a_mostrar = intval( $next_bdays_a_mostrar );

        $template = (!empty( $new_instance['template'] ) ) ? strip_tags( $new_instance['template'] ) : 1;
        $template = intval( $template );
        //ensure template exists
        $template = ($template>=1 && $template<JASON_BD_TEMPLATES_COUNT) ? $template : 1;

        $avatar_show_bd_today = !empty( $new_instance['avatar_show_bd_today'] ) ? strip_tags( $new_instance['avatar_show_bd_today'] ) : 1;
        $avatar_show_bd_today = intval( $avatar_show_bd_today );
        $avatar_show_bd_past = !empty( $new_instance['avatar_show_bd_past'] ) ? strip_tags( $new_instance['avatar_show_bd_past'] ) : 1;
        $avatar_show_bd_past = intval( $avatar_show_bd_past );
        $avatar_show_bd_next = !empty( $new_instance['avatar_show_bd_next'] ) ? strip_tags( $new_instance['avatar_show_bd_next'] ) : 1;
        $avatar_show_bd_next = intval( $avatar_show_bd_next );

        $instance = array();
        $instance['title'] = $title;
        $instance['pasados_bdays_a_mostrar'] = $pasados_bdays_a_mostrar;
        $instance['next_bdays_a_mostrar'] = $next_bdays_a_mostrar;
        $instance['template'] = $template;
        //show avatars?
        $instance['avatar_show_bd_today'] = $avatar_show_bd_today;
        $instance['avatar_show_bd_past'] = $avatar_show_bd_past;
        $instance['avatar_show_bd_next'] = $avatar_show_bd_next;

        return $instance;
    }

}

// class Foo_Widge