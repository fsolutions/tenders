<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
    $sended = 0;

    foreach ($orders as $order) {
      try {
        $claimed = DB::update(
          'update orders_primary set sended_to_telegram = 1 where id = ? and sended_to_telegram = 0',
          [$order->id]
        );

        if ($claimed === 0) {
          continue;
        }

        $notify = new \App\Notifications\NewUnworkedOrderRecieved($order);
        $notify->toTelegram();

        $userRobotNotify = new NewOrderNotification();
        $userRobotNotify->send($order);

        $sended++;
        echo 'Order #' . $order->id . ' sended to telegram.';
        echo "\r\n";
      } catch (\Throwable $e) {
        Log::error('orders:sendneworderstotelegram failed for order #' . $order->id, [
          'exception' => $e->getMessage(),
        ]);
        echo 'Order #' . $order->id . ' failed: ' . $e->getMessage();
        echo "\r\n";
      }
    }

    echo "\r\n";
    echo "Num of sended orders to telegram: " . $sended;
    echo "\r\n";
  }
}
