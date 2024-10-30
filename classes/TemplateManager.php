<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TemplateManager
 *
 * @author angelorum
 */
class TemplateManager
{

    public static function getTemplates()
    {
        $d = 1;
        $h = JASON_BD_TEMPLATES_COUNT;

        $arr = array();

        for (; $d <= $h; $d++ )
        {
            $arr[] = $d;
        }
        return $arr;
    }

    public function getTemplate( $bdays_today, $bdays_past, $bdays_next, $template_number = 1, $avatar_show_bd_today, $avatar_show_bd_past, $avatar_show_bd_next )
    {
        $template_number= intval($template_number);
        
        //template file
        $tfile = dirname( __FILE__ ) . "/../templates/template_$template_number.php";
        if ( file_exists( $tfile ) )
        {
            require_once dirname( __FILE__ ) . "/../templates/template_$template_number.php";
        }
        else
        {
            echo("<h1>ERROR: template file $tfile not found</h1>");
        }
    }

}
