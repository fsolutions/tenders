<?php

namespace App\Console\Commands;

use App\Model\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Class SendOrderToWhatsupCommand
 *
 * @package App\Console\Commands
 */
class SendOrderToWhatsupCommand extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'orders:sendnewtowhatsup';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Sending none sended orders to whatsup';

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
   * Send to whatsup API message by chatid
   *
   * @param [type] $chatId
   * @param [type] $message
   * @return void
   */
  public function sendToWhatsupChatMessage($chatId, $uslugiList, $order, $graveyardName)
  {
    $tarifInfo = "Тип: " . $order->tarif_stringify;
    $uslugiInfo = "*Услуги: *\n" . $uslugiList;
    $cityInfo = "Город: " . ($order->city ? $order->city->pagetitle : '-');
    $graveyardInfo = $graveyardName != '-' ? "Кладбище: " . $graveyardName . "\n" : '';
    $costInfo = "*Бюджет: * от " . $order->itogsum . ' руб.';
    $numberOfGraves = "";

    if ($order->tarif == "extended" || $order->tarif == "easy") {
      $numberOfGraves = "Количество могил на обработку:  " . $order->number_of_graves . "\n";
    }

    $message = "*Новый заказ #" . $order->id . "*\n\n" .
      $tarifInfo . "\n" .
      $uslugiInfo . "\n" .
      $cityInfo . "\n" .
      $graveyardInfo . "\n" .
      $numberOfGraves .
      $costInfo . "\n\n" . "https://tenders.gravescare.com/orders/page/" . $order->id;

    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://eu180.chat-api.com/instance179953/sendMessage?token=q77s7tc7jnopjxng",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => array('chatId' => $chatId, 'phone' => '', 'body' => $message),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    return $response;
  }

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle()
  {
    echo "\r\n";
    echo "Start searching none sended to whatsup orders...";
    echo "\r\n";
    echo "\r\n";

    $economicRegions = Order::getEconomicRegions();

    $orders = Order::where('sended_to_whatsup', '=', 0)->limit(10)->get();
    $orders->load(['city', 'graveyard']);
    $sended = 0;

    // Найдем по городам регион

    foreach ($orders as $order) {
      try {
        $claimed = DB::table('orders')
          ->where('id', $order->id)
          ->where('sended_to_whatsup', 0)
          ->update(['sended_to_whatsup' => 1]);

        if ($claimed === 0) {
          continue;
        }

        if (!isset($economicRegions[$order->order_economic_region_id])) {
          Log::warning('orders:sendnewtowhatsup missing economic region for order #' . $order->id, [
            'order_economic_region_id' => $order->order_economic_region_id,
          ]);
          continue;
        }

        $chatId = $economicRegions[$order->order_economic_region_id]['chatId'];
        $graveyardName = "-";

        if ($order->order_object_name_ext) {
          $graveyardName = $order->order_object_name_ext;
        } else if ($order->graveyard && $order->graveyard->pagetitle) {
          $graveyardName = $order->graveyard->pagetitle;
        }

        $orderInfoForWhatsup = Order::safeUnserializeOrderTxt($order->order_txt);
        $orderInfoForWhatsupString = "";

        foreach ($orderInfoForWhatsup as $orderrow) {
          if (isset($orderrow['name'])) {
            $orderInfoForWhatsupString .= "- " . $orderrow["name"] . "\n";
          }
        }

        // $order->notify(new \App\Notifications\NewOrderPublished($graveyardName, $orderInfoForWhatsupString));
        $this->sendToWhatsupChatMessage($chatId, $orderInfoForWhatsupString, $order, $graveyardName);
        $sended++;
        echo 'Order #' . $order->id . ' sended to whatsup.';
        echo "\r\n";
      } catch (\Throwable $e) {
        Log::error('orders:sendnewtowhatsup failed for order #' . $order->id, [
          'exception' => $e->getMessage(),
        ]);
        echo 'Order #' . $order->id . ' failed: ' . $e->getMessage();
        echo "\r\n";
      }
    }

    echo "\r\n";
    echo "Num of sended orders to whatsup: " . $sended;
    echo "\r\n";
  }
}
