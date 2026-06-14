<?php
namespace App\Providers;
interface BrokerInterface{public function getAccount():array;public function getPositions():array;public function getOrders():array;public function placeOrder(array $order):array;public function cancelOrder(int|string $id):bool;public function modifyOrder(int|string $id,array $changes):array;public function getBalance():float;}
