<?php
namespace App\Providers;
class AlpacaBroker extends PaperBroker{public function placeOrder(array $order):array{throw new \RuntimeException('AlpacaBroker adapter is configured but live API credentials/integration are not enabled.');}}
