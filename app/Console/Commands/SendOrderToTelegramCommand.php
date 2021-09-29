<?php

namespace App\Console\Commands;

use App\Model\Order;
use Illuminate\Console\Command;

/**
 * Class SendOrderToTelegramCommand
 *
 * @package App\Console\Commands
 */
class SendOrderToTelegramCommand extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'orders:sendnewtotelegram';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Sending none sended orders to telegram';

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

    $orders = Order::where('sended_to_telegram', '=', 0)->get();
    $orders->load(['city', 'graveyard']);

    foreach ($orders as $order) {
      $graveyardName = "-";

      if ($order->order_object_name_ext) {
        $graveyardName = $order->order_object_name_ext;
      } else if ($order->graveyard && $order->graveyard->pagetitle) {
        $graveyardName = $order->graveyard->pagetitle;
      }

      $orderServices = ['data' => [], 'itog' => 0];

      // Если есть услуги в новом формате
      if (isset($order->order_services)) {
        $orderServices = json_decode($order->order_services);
      }

      $orderInfoForTelegramString = "";

      // Если работаем со старым форматом
      if (!isset($orderServices->data) || count($orderServices->data) == 0) {
        $orderInfoForTelegram = unserialize($order->order_txt);

        foreach ($orderInfoForTelegram as $orderrow) {
          $orderInfoForTelegramString .= "- " . $orderrow["name"] . "\n";
        }
      } else {
        foreach ($orderServices->data as $orderrow) {
          $orderInfoForTelegramString .= "- " . $orderrow->name . "\n";
        }
      }

      if ($orderInfoForTelegramString == '') {
        $orderInfoForTelegramString = 'Уточняйте у нашего менеджера';
      }

      if ($order->opened_order == 1) {
        $order = $order->makeVisible([
          // 'user_web_users_id',
          'user_web_users_name',
          'user_web_users_phone',
          'user_web_users_email',
        ]);
      }

      $order->notify(new \App\Notifications\NewOrderPublished($graveyardName, $orderInfoForTelegramString));
      $order->sended_to_telegram = 1;
      $order->save();
      echo 'Order #' . $order->id . ' sended to telegram.';
      echo "\r\n";
    }

    echo "\r\n";
    echo "Num of sended orders to telegram: " . count($orders);
    echo "\r\n";
  }
}
