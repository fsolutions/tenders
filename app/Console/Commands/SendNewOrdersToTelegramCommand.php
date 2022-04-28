<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Bundles\Telegram\Notifications\NewOrderNotification;

/**
 * Class SendNewOrdersToTelegramCommand
 *
 * @package App\Console\Commands
 */
class SendNewOrdersToTelegramCommand extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'orders:sendneworderstotelegram';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Sending new orders to telegram, which not worked up';

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle()
  {
    echo "\r\n";
    echo "Start searching none sended to telegram orders...";
    echo "\r\n";
    echo "\r\n";

    $orders = DB::select('select * from orders_primary where sended_to_telegram = ?', [0]);

    foreach ($orders as $order) {
      $notify = new \App\Notifications\NewUnworkedOrderRecieved($order);
      $notify->toTelegram();

      $userRobotNotify = new NewOrderNotification();
      $userRobotNotify->send($order);

      $update = DB::update(
        'update orders_primary set sended_to_telegram = 1 where id = ?',
        [$order->id]
      );

      echo 'Order #' . $order->id . ' sended to telegram.';
      echo "\r\n";
    }

    echo "\r\n";
    echo "Num of sended orders to telegram: " . count($orders);
    echo "\r\n";
  }
}
