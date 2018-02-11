<?php

    require __DIR__ . '/vendor/autoload.php';

    $connector = NULL;

    \Ratchet\Client\connect('ws://192.168.1.158:5555')->then(function($conn) {
        $conn->on('message', function($msg) use ($conn) {
            echo "Received: {$msg}\n";
            $conn->close();
            print_r($conn);
            //$conn->send('Hello World!');

        });
        $connector = $conn;

        $conn->send('Hello World!');
    }, function ($e) {
        echo "Could not connect: {$e->getMessage()}\n";
    });

    $connector->send("");
?>
