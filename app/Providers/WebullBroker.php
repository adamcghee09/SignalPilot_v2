<?php
namespace App\Providers;
class WebullBroker extends PaperBroker{public function placeOrder(array $order):array{throw new \RuntimeException('WebullBroker adapter is configured but live API credentials/integration are not enabled.');}}
