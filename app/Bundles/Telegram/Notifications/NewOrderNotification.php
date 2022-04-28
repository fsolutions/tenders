<?php

namespace App\Bundles\Telegram\Notifications;

use App\Model\TGUser;
use App\Bundles\Slugifier;
use App\Model\TGUserRegion;
use Illuminate\Support\Facades\DB;
use App\Traits\Telegram\RequestTrait;
use App\Traits\Telegram\MakeComponents;

class NewOrderNotification
{
  use RequestTrait;
  use MakeComponents;

  /**
   * Send message to user chat
   * 
   * @return bool
   */
  static function send($orderInfo): bool
  {
    $text = "<b>#" . $orderInfo->id . ": Новый заказ «" . $orderInfo->usluga . "»</b>\n\n" .
      "Заявка со страницы: " . $orderInfo->url . "\n\n" .

      "<b>Имя: </b>Уточняйте у менеджера\n" .
      "<b>Телефон: </b> Уточняйте у менеджера\n\n" .

      "Бюджет: от " . $orderInfo->budjet . "\n" .
      "Скидка: " . $orderInfo->skidka . "\n" .
      "Регулярность: " . $orderInfo->regularnost . "\n\n" .

      "Комментарий: " . $orderInfo->comment . "\n";

    $tgUsers = TGUser::where('only_regions', 0)->get()->pluck('tg_user_id')->all();
    $cityToFind = 'Москва';

    if (!is_null($orderInfo->city)) {
      $cityToFind = $orderInfo->city;
    }

    $chatIdsByRegions = self::getChatsByNameOfCity($cityToFind);
    $finalChatsForSend = array_merge($tgUsers, $chatIdsByRegions);
    $finalChatsForSend = array_unique($finalChatsForSend);

    foreach ($finalChatsForSend as $chatId) {
      // foreach ($tgUsers as $chatId) {
      self::apiRequest('sendMessage', [
        'chat_id' => $chatId,
        'text' => $text,
        'parse_mode' => 'HTML',
        'reply_markup' => self::button("Откликнуться на заказ", "https://t.me/dchusov")
      ]);
    }

    return true;
  }

  static function getChatsByNameOfCity($cityToFind)
  {
    $regions = [];
    $chats = [];

    if ($cityToFind == 'Москва') {
      $regions = [77, 50];
    } else {
      $slugifier = new Slugifier();
      $slugedCityName = $slugifier->make($cityToFind, '-', false);
      $slugedCityName = mb_substr($slugedCityName, 0, 5);

      $regionsDB = DB::select('SELECT `id`, `description` FROM `modx_site_content` WHERE `template` = 5 AND `id` IN (SELECT `parent` FROM `modx_site_content` WHERE `alias` LIKE \'%' . $slugedCityName . '%\' AND `template` = 6)');

      foreach ($regionsDB as $region) {
        if (!is_null($region->description)) {
          $regions[] = $region->description;
        }
      }
    }

    $tgUsersWithOnlyRegions = TGUser::where('only_regions', 1)->get()->pluck('tg_user_id')->all();
    $chats = TGUserRegion::whereIn('tg_user_id', $tgUsersWithOnlyRegions)
      ->whereIn('regions_id', $regions)->get()->pluck('tg_user_id')->all();

    return $chats;
  }
}
