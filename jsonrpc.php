<?php

include_once("./jsonrpc/Addons.class.php");
include_once("./jsonrpc/Player.class.php");
include_once("./JsonRpcServer.class.php");

$server = new JsonRpcServer();

$server->registerClass(new Addons());
$server->registerClass(new Player());

$server->handle();
?>
