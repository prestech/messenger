<?php

    require 'Iconnection.php';
    use Ratchet\MessageComponentInterface;
    use Ratchet\ConnectionInterface;

    class ChatApp implements MessageComponentInterface {

        protected $clients;
        public static $count = 0;
        public $receiver; 


        public function __construct($receiver) {

            $this->receiver = $receiver; 

            $this->clients = new \SplObjectStorage;
        }#__construct() Ends 

        public function onOpen(ConnectionInterface $conn) {
            // Store the new connection to send messages to later
            self::$count = self::$count+1;

            $this->clients->attach( new Iconnection( $receiver, $conn) );

             echo "New connection! ({$conn->resourceId})\n";
             echo "Number of users: ".self::$count."\n";

        }//onOpen() Ends 

        public function onMessage(ConnectionInterface $from, $msg) {
            $numRecv = count($this->clients) - 1;
            echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
                , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

            foreach ($this->clients as $client) {
                if ($receiver == $client->getId()) {
                    // The sender is not the receiver, send to each client connected
                    $client->send($msg);
                }
            }
        }

        public function onClose(ConnectionInterface $conn) {
            // The connection is closed, remove it, as we can no longer send it messages
            $this->clients->detach($conn);

            echo "Connection {$conn->resourceId} has disconnected\n";
        }

        public function onError(ConnectionInterface $conn, \Exception $e) {
            echo "An error has occurred: {$e->getMessage()}\n";

            $conn->close();
        }
        
    }// Class Chat Ends 


    //require __Dir__."/vendor/autoload.php";
    //use chatapp\app;
    use React\EventLoop;

    $receiver = "";

    $loop = \React\EventLoop\Factory::create();

    //listen to incoming messages from Apache 
    $context = new \React\ZMQ\Context($loop);
    $socket_in = $context->getSocket(\ZMQ::SOCKET_PULL); 

    $socket_in->bind("tcp://127.0.0.1:5555");
    
    echo "Local bind at 5555";

    $socket_in-> on("message", function($msg){
            echo "\nincoming message from $msg \n";
            $receiver = $msg;
    });
    //get message from socket_in


    //port to send outgoing messages from Apeche server 
    $web_socket = new React\Socket\Server("0.0.0.0:8080", $loop); 
    $webServer = new Ratchet\Server\IoServer(
            new \Ratchet\Http\HttpServer(
                    new \Ratchet\WebSocket\WsServer(

                        new ChatApp()
                    )
            ),

            $web_socket
        );

    echo "web port opened...";
    $loop->run();

?>
