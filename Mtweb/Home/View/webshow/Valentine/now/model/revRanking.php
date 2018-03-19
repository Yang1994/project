<?php
header( 'Content-Type:application/json' );

include "../model/DBTool.php";

function getJsonData( mysqli $db ) {
    $arr = array();

    $sql = "SELECT * FROM v_mooncake_rev_ranking";
    $result = $db->query( $sql ) or die( "db error" );

    if( $result ) {
        while( list( $uid, $gift_count, $last_time, $nick_name, $image_url ) = $result->fetch_row() ) {
            $arr[] = [ $image_url, $nick_name, $uid, $gift_count ];
        }
        $result->close();
        $db->next_result();
    }

    return json_encode( $arr );
}

echo getJsonData( $db );

$db->close();
?>