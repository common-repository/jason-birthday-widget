<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of JasonUtils
 *
 * @author angelorum
 */
class JasonUtils
{

    public static function bdToIcal( $mes_desde = null, $mes_hasta = null, $from = 0, $limit = -1 )
    {
        $table = new BdaysTable();
        $results = $table->getallByDate( $mes_desde, $mes_hasta, $from, $limit );

        $res = self::bdToIcalFromResults( $results );

        header( "Content-Type: text/calendar;" );
        header( "Content-Disposition: inline; filename=jason_bday_events.ics" );
        echo $res;
        exit;
    }

    public static function bdToIcalFromResults( $result )
    {
        //no resultados que agregar
        if ( count( $result ) <= 0 )
            return false;


        // the iCal date format. Note the Z on the end indicates a UTC timestamp.
        define( 'DATE_ICAL', 'Ymd\THis\Z' );

// max line length is 75 chars. New line is \\n

        $output = "BEGIN:VCALENDAR\r\n" .
                "METHOD:PUBLISH\r\n" .
                "VERSION:2.0\\rn" .
                "PRODID:-//Jason Matamala//" . JASON_BD_WIDGET_NAME . "//EN\r\n";

        $hoy = date( DATE_ICAL, strtotime( 'now' ) );
// loop over events
        foreach ( $result as $r )
        {
            $user_id = $r['user_id'];
            $user_profile_link = get_author_posts_url( $user_id );
            $user_name = $r['display_name'];
            $fecha_mysql = date( DATE_ICAL, strtotime( $r['bday_date'] ) );
            //date( 'd-m-Y', strtotime( $r['bday_date'] ) );

            $output .= "BEGIN:VEVENT\r\n" .
                    "SUMMARY:Cumpleaños de $user_name\r\n" .
                    "UID:$user_id\r\n" .
                    "STATUS:CONFIRMED\r\n" .
                    "DTSTART:$fecha_mysql\r\n" .
                    "DTEND:$fecha_mysql\r\n" .
                    "LAST-MODIFIED:$hoy\r\n" .
                    "LOCATION:$user_profile_link\r\n" .
                    "END:VEVENT\r\n";
        }

// close calendar
        $output .= "END:VCALENDAR";

        //   self::show($output, "ical convertido:");
        //guardo salida en fichero
        $dir = plugin_dir_path( __FILE__ );
        $filename = JASON_BD_WIDGET_NAME . '.ics';
        $filename = sanitize_file_name( $filename );

        file_put_contents( $dir . $filename, $output );

        //devuelvo url del file
        $url_file = plugin_dir_url( __FILE__ ) . "$filename";
        return $url_file;
    }

    public static function validateDate( $date, $format = JASON_BD_DATE_FORMAT )
    {
        $d = DateTime::createFromFormat( $format, $date );
        return $d && $d->format( $format ) == $date;
    }

    /**
     *  just for debugging
     * @param type $arr
     * @param type $title
     */
    public static function show( $arr, $title = null )
    {
        //uncomment to debug
       // return;
        if ( !empty( $title ) )
            echo("<h4>$title</h4>");

        if ( $arr != null )
        {
            //si no es array ni json
            if ( !is_array( $arr ) && strpos( $arr, '{' ) === false )
            {
                echo("<p>$arr</p>");
            }
            else
            {
                echo("<textarea cols='60' rows='3'>"); //pre
                print_r( $arr );
                echo("</textarea>");
            }
        }
    }

}
