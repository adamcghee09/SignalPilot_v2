<?php
namespace App\Providers; interface MarketDataProviderInterface{public function quote(string $symbol):array;public function candles(string $symbol,string $tf='1d',int $limit=50):array;}
