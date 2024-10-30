<!--template 1-->
<div class="jason_bday_div2">
    <?php
    require_once dirname( __FILE__ ) . '/../classes/JasonUtils.php';

    //  $today_db = esc_html__("Today's Birthdays", JASON_BD_TD);
    // JasonUtils::show( $bdays_today, 'cumplen hoy' );
    //  JasonUtils::show( $bdays_past, 'cumplieron' );
    //   JasonUtils::show( $bdays_next, 'cumpliran' );
    ?>
    <?php if ( !empty( $bdays_today ) ): ?>
        <div class="jason_bday_today jason_bday">        
            <h1><?php esc_html_e( "Today's Birthdays", JASON_BD_TD ); ?></h1>

            <ul>
                <?php foreach ( $bdays_today as $b ): ?>
                    <?php
                    //get avatar
                    $user_id = $b['user_id'];
                    $size = 256;
                    $avatar = get_avatar( $user_id, $size );
                    $user_name = $b['display_name'];
                    $user_profile_link = get_author_posts_url( $user_id );
                    ?>
                    <li><p><?php echo ($avatar_show_bd_today == JASON_BD_YES) ? $avatar : ''; ?>
                            <a class="bday_user" href="<?php echo $user_profile_link; ?>"><?php echo $user_name; ?></a>
                        </p></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    <?php if ( !empty( $bdays_past ) ): ?>
        <div class="jason_bday_past jason_bday">        
            <h1><?php esc_html_e( "Past Birthdays", JASON_BD_TD ); ?></h1>

            <ul>
                <?php foreach ( $bdays_past as $b ): ?>
                    <?php
                    //get avatar
                    $user_id = $b['user_id'];
                    $size = 48;
                    $avatar = get_avatar( $user_id, $size );
                    $user_name = $b['display_name'];
                    $user_profile_link = get_author_posts_url( $user_id );
                    $fecha = date( 'd-m-Y', strtotime( $b['bday_date'] ) );
                    ?>
                    <li><p><?php echo ($avatar_show_bd_past == JASON_BD_YES) ? $avatar : ''; ?>
                            <a class="bday_user" href="<?php echo $user_profile_link; ?>" 
                               title="<?php echo $fecha; ?>"><?php echo $user_name; ?></a>
                        </p></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if ( !empty( $bdays_next ) ): ?>
        <div class="jason_bday_next jason_bday">        
            <h1><?php esc_html_e( "Next Birthdays", JASON_BD_TD ); ?></h1>

            <ul>
                <?php foreach ( $bdays_next as $b ): ?>
                    <?php
                    //get avatar
                    $user_id = $b['user_id'];
                    $size = 48;
                    $avatar = get_avatar( $user_id, $size );
                    $user_name = $b['display_name'];
                    $user_profile_link = get_author_posts_url( $user_id );
                    $fecha = date( 'd-m-Y', strtotime( $b['bday_date'] ) );
                    ?>
                    <li><p><?php echo ($avatar_show_bd_next == JASON_BD_YES) ? $avatar : ''; ?>
                            <a class="bday_user" href="<?php echo $user_profile_link; ?>"
                               title="<?php echo $fecha; ?>" ><?php echo $user_name; ?></a>
                        </p></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>



</div>

