<?php
    define( 'DB_NAME', 'camagru' );
    define( 'DB_USER', 'root' );
    define( 'DB_PASSWORD', 'rootpass' );
    define( 'DB_HOST', '10.4.3.2' );

    $DB_DSN = `mysql:dbname=DB_NAME;host=DB_HOST`;
    $DB_USER = `DB_USER`;
    $DB_PASSWORD = `DB_PASSWORD`;
?>