<?php
namespace App\Providers;
class InteractiveBrokersBroker extends PaperBroker{public function placeOrder(array $order):array{throw new \RuntimeException('InteractiveBrokersBroker adapter is configured but live API credentials/integration are not enabled.');}}
