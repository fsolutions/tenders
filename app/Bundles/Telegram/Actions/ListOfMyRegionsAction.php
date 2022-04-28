<?php

namespace App\Bundles\Telegram\Actions;

use App\Model\TGUser;
use App\Traits\Telegram\RequestTrait;
use App\Traits\Telegram\MakeComponents;

class ListOfMyRegionsAction
{
  use RequestTrait;
  use MakeComponents;

  /**
   * Handler for send list of users region action
   *
   * @param int $chatId
   * @param string $requestType
   * @param int $message_id
   * 
   * @return bool
   */
  static function make(int $chatId, int $message_id = -1, string $requestType = 'sendMessage'): bool
  {
    $text = "Ваш список регионов для отслеживания заказов временно пуст. Добавьте регионы в этом роботе и получайте ленту только территориально близких к вам заказам.";

    $user = TGUser::where('tg_user_id', $chatId)->first();
    $listOfUserRegions = $user->regions();

    if ($listOfUserRegions->count() > 0) {
      $text = "Кликните на регион, чтобы удалить его из списка подписок. Вот регионы, на которые вы подписаны:";

      $options = [];
      $row = -1;
      foreach ($listOfUserRegions->get() as $key => $region) {
        if ($key % 2 == 0) {
          $row++;
          $options[$row] = [];
        }

        $regionCuttedName = mb_substr($region->info->name ? $region->info->name : $region->info->name, 0, 13);

        $options[$row][] = [
          'text' => $regionCuttedName . ' ❌',
          'callback_data' => 'delete_region||' . $region->id
        ];
      }

      self::apiRequest($requestType, [
        'chat_id' => $chatId,
        'text' => $text,
        'reply_markup' => self::inlineKeyboardBtn($options),
        'message_id'   => $message_id,
      ]);
    } else {
      self::apiRequest($requestType, [
        'chat_id' => $chatId,
        'text' => $text,
        'message_id'   => $message_id,
      ]);
    }

    return true;
  }
}
