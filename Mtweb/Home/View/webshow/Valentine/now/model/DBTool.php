<?php

//define( "ServerName", "localhost" );
//define( "UserName", "gameuser" );
//define( "PassWord", "tv#shangtv2017" );
//define( "DBName", "games_temp" );

define( "ServerName", "localhost" );
define( "UserName", "17user" );
define( "PassWord", "sh#nGSQL2017" );
define( "DBName", "games_temp" );

$db = new mysqli( ServerName, UserName, PassWord, DBName );
$db->query( "SET NAMES 'utf8'" );

/* check connection */
if( mysqli_connect_errno() ) {
    printf( "Connect failed: %s\n", mysqli_connect_error() );
    exit();
}

?>