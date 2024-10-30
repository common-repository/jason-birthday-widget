<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BdaysTable
 *
 * @author angelorum
 */
class BdaysTable
{

    private $TABLE, $USERS_TABLE;

    function __construct()
    {
        global $wpdb;
        $this->TABLE = $wpdb->prefix . JASON_BIRTHDAY_TABLE;
        $this->USERS_TABLE = $wpdb->prefix . 'users';
    }

    public function insert_or_update( $user_id, $bday, $show_to_others )
    {
        global $wpdb;
        $table = $this->TABLE;

        //validate date
        if ( !JasonUtils::validateDate( $bday ) )
        {
            // die("mala fecha $bday");
            return false;
        }

        //separo campos de fecha para guardarlos x separado en la db
        list($dd, $mm, $yyyy) = explode( '-', $bday );
        $dd = (int) $dd;
        $mm = (int) $mm;
        $yyyy = (int) $yyyy;

        //paso bday a formato mysql
        $bday = $this->fechaMysql( $bday );

        /*  $data = array( 'user_id' => $user_id,
          'day' => $dd,
          'month' => $mm,
          'year' => $yyyy,
          'bday_date' => $bday,
          'show_bday' => $show_to_others
          ); */

        $sql = "INSERT INTO $table (user_id, day, month, year, bday_date, show_bday ) "
                . "VALUES (%d, %d, %d, %d, %s, %d)
                    ON DUPLICATE KEY UPDATE 
                    day = %d, month = %d, year = %d, "
                . " bday_date = %s, show_bday = %d ;";

        $sql = $wpdb->prepare( $sql, $user_id, $dd, $mm, $yyyy, $bday, $show_to_others, $dd, $mm, $yyyy, $bday, $show_to_others );
        //  die ($sql);
        return $wpdb->query( $sql );
    }

    /**
     * 
     * @global type $wpdb
     * @param type $user_id
     * @param type $bday
     * @param type $show_to_others
     * @return false si no pudo
     */
    public function add( $user_id, $bday, $show_to_others )
    {
        global $wpdb;
        $table = $this->TABLE;

        //validate date
        if ( !JasonUtils::validateDate( $bday ) )
        {
            // die("mala fecha $bday");
            return false;
        }

        //separo campos de fecha para guardarlos x separado en la db
        list($dd, $mm, $yyyy) = explode( '-', $bday );
        //paso bday a formato mysql
        $bday = $this->fechaMysql( $bday );

        $data = array( 'user_id' => $user_id,
            'day' => $dd,
            'month' => $mm,
            'year' => $yyyy,
            'bday_date' => $bday,
            'show_bday' => $show_to_others
        );

        return $wpdb->insert( $table, $data ); //false si no pudo
    }

    /**
     * 
     * @global type $wpdb
     * @param type $user_id
     * @param type $bday
     * @param type $show_to_others
     * @return false $x === false en caso de error
     */
    public function update( $user_id, $bday, $show_to_others )
    {
        global $wpdb;
        $table = $this->TABLE;

        //validate date
        if ( !JasonUtils::validateDate( $bday ) )
        {
            // die("mala fecha $bday");
            return false;
        }

        $user_id = intval( $user_id );

        //separo campos de fecha para guardarlos x separado en la db
        list($dd, $mm, $yyyy) = explode( '-', $bday );
        //paso bday a formato mysql
        $bday = $this->fechaMysql( $bday );

        $data = array( 'user_id' => $user_id,
            'day' => $dd,
            'month' => $mm,
            'year' => $yyyy,
            'bday_date' => $bday,
            'show_bday' => $show_to_others
        );

        $where = " user_id='$user_id' ";
        return $wpdb->update( $table, $data, $where );
    }

    public function delete( $user_id )
    {
        global $wpdb;
        $table = $this->TABLE;

        $user_id = intval( $user_id );

        $where = array( 'user_id' => $user_id);
        return $wpdb->delete( $table, $where );
    }

    public function get( $user_id )
    {
        global $wpdb;
        $table = $this->TABLE;

        $user_id = intval( $user_id );

        $sql = "SELECT * FROM $table WHERE user_id = %d ";
        $sql = $wpdb->prepare( $sql, $user_id );
        $row = $wpdb->get_row( $sql, ARRAY_A );
        return $row;
    }

    public function getallByDate( $mes_desde = null, $mes_hasta = null, $from = 0, $limit = -1 )
    {

        global $wpdb;
        $table = $this->TABLE;
        $users_table = $this->USERS_TABLE;

        $mes_desde = (int) $mes_desde;
        $mes_hasta = (int) $mes_hasta;

        $sql = "SELECT bd.*, u.display_name FROM $table bd, $users_table u  "
                . "WHERE "
                . "( bd.month between %d AND %d ) "
                . "   and bd.user_id = u.ID  "
                . " ORDER BY bd.user_id LIMIT %d, %d";

//        JasonUtils::show( $sql );

        $sql = $wpdb->prepare( $sql, intval( $mes_desde ), intval( $mes_hasta ), intval( $from ), intval( $limit ) );

        $results = $wpdb->get_results( $sql, ARRAY_A );

        return $results;
    }

   

    public function getBDayUsers()
    {

        global $wpdb;
        $table = $this->TABLE;
        $users_table = $this->USERS_TABLE;

        $sql = "SELECT bd.*, u.display_name FROM $table bd, $users_table u  "
                . "WHERE DATE(  bd.bday_date ) = CURDATE() and bd.user_id = u.ID  "
                . " ORDER BY bd.user_id";

      
        $sql = $wpdb->prepare( $sql, $table );
     //   JasonUtils::show( $sql );
        $results = $wpdb->get_results( $sql, ARRAY_A );

        return $results;
    }

    public function getPastBDayUsers( $ddmmyyy, $cantidad )
    {
        $cantidad = intval( $cantidad );

        if ( $cantidad <= 0 )
            return null;

        //validate date
        if ( !JasonUtils::validateDate( $ddmmyyy ) )
        {
            // die("mala fecha $bday");
            return false;
        }

        $bday = $this->fechaMysql( $ddmmyyy );

        global $wpdb;
        $table = $this->TABLE;
        $users_table = $this->USERS_TABLE;

        $sql = "SELECT bd.*, u.display_name FROM $table bd, $users_table u  "
                . "WHERE DATE(  bd.bday_date ) < CURDATE() and bd.user_id = u.ID   "
                . "ORDER BY bd.bday_date DESC limit %d";

        $sql = $wpdb->prepare( $sql, $cantidad );
        // JasonUtils::show($sql);

        $results = $wpdb->get_results( $sql, ARRAY_A );

        return $results;
    }

    public function getNextBDayUsers( $ddmmyyy, $cantidad )
    {
        $cantidad = intval( $cantidad );

        if ( $cantidad <= 0 )
            return null;

        //validate date
        if ( !JasonUtils::validateDate( $ddmmyyy ) )
        {
            // die("mala fecha $bday");
            return false;
        }

        $bday = $this->fechaMysql( $ddmmyyy );

        global $wpdb;
        $table = $this->TABLE;
        $users_table = $this->USERS_TABLE;

        $sql = "SELECT bd.*, u.display_name FROM $table bd, $users_table u  "
                . "WHERE DATE(  bd.bday_date ) > CURDATE() and bd.user_id = u.ID   "
                . "ORDER BY bd.bday_date DESC limit %d";
        $sql = $wpdb->prepare( $sql, $cantidad );
        //  JasonUtils::show($sql);

        $results = $wpdb->get_results( $sql, ARRAY_A );

        return $results;
    }

    public function fechaMysql( $fecha, $delim = '-' )
    {
        if ( $fecha == null )
            return $fecha;

        if ( $fecha == '' )
            return null;
        $arr = explode( $delim, $fecha );
        if ( count( $arr ) != 3 )
        {
            return $fecha;
        }
        else
        {
            $y = (int) $arr[2];
            $m = (int) $arr[1];
            $d = (int) $arr[0];
            return $y . $delim . $m . $delim . $d;
        }
    }

}
