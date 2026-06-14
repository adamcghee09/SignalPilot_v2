<?php
namespace App\Providers;
class TradierBroker extends PaperBroker{public function placeOrder(array $order):array{throw new \RuntimeException('TradierBroker adapter is configured but live API credentials/integration are not enabled.');}}
