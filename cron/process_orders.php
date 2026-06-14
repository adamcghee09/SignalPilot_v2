<?php
require __DIR__.'/../app/bootstrap.php';
$m=App\Services\Factories::market();
$pdo=DB::pdo();
$orders=$pdo->query("SELECT * FROM orders WHERE status='open' AND provider='paper' ORDER BY id")->fetchAll();
foreach($orders as $o){
  $q=$m->quote($o['symbol']); $price=(float)($o['limit_price'] ?: $q['price']); $qty=(float)$o['qty']; $cost=$price*$qty; $side=strtolower($o['side']);
  $pdo->beginTransaction();
  $pos=$pdo->prepare("SELECT * FROM positions WHERE symbol=? AND broker='paper' FOR UPDATE");$pos->execute([$o['symbol']]);$p=$pos->fetch();
  if($side==='buy'){$newQty=($p['qty']??0)+$qty;$avg=$p?((($p['qty']*$p['avg_price'])+$cost)/max($newQty,0.000001)):$price;$cash=-$cost;} else {$newQty=($p['qty']??0)-$qty;$avg=$p['avg_price']??$price;$cash=$cost;}
  if($p)$pdo->prepare('UPDATE positions SET qty=?,avg_price=?,market_price=? WHERE id=?')->execute([$newQty,$avg,$price,$p['id']]); else $pdo->prepare('INSERT INTO positions(symbol,qty,avg_price,market_price,broker) VALUES(?,?,?,?,\'paper\')')->execute([$o['symbol'],$newQty,$avg,$price]);
  $pnl=$side==='sell' && $p ? ($price-$p['avg_price'])*$qty : 0;
  $pdo->prepare('UPDATE paper_accounts SET balance=balance+?, equity=equity+?, buying_power=buying_power+? WHERE id=1')->execute([$cash,$cash,$cash]);
  $pdo->prepare("UPDATE orders SET status='filled',filled_at=NOW() WHERE id=?")->execute([$o['id']]);
  $pdo->prepare('INSERT INTO trades(order_id,symbol,side,qty,price,pnl,reason) VALUES(?,?,?,?,?,?,?)')->execute([$o['id'],$o['symbol'],$side,$qty,$price,$pnl,'paper fill']);
  $pdo->commit(); log_event('trading','info','Order filled',['order_id'=>$o['id'],'symbol'=>$o['symbol']]);
}
echo 'Processed '.count($orders)." orders\n";
