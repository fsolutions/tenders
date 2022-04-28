<?php

namespace App\Bundles\Telegram\Actions;

use App\Model\Region;
use App\Model\TGUser;
use App\Model\TGUserSubscription;
use App\Traits\Telegram\RequestTrait;
use App\Traits\Telegram\MakeComponents;
use App\Models\TGBotChannelSubscribtion;

class AddRegionAction
{
  use RequestTrait;
  use MakeComponents;

  /**
   * Handler for send list of regions action
   *
   * @param int $chatId
   * @param string $requestType
   * @param int $message_id
   * 
   * @return bool
   */
  static function start(int $chatId, int $message_id = -1, string $requestType = 'sendMessage'): bool
  {
    $text = "Список регионов, доступный для отслеживания заказов. Кликните на регион, для того, чтобы подписаться. Для отписки от региона - нажмите повторно на регион.";

    $listOfRegions = Region::orderBy('name')->get();
    $user = TGUser::where('tg_user_id', $chatId)->first();
    $listOfUserRegionsId = $user->regions()->get()->pluck('regions_id')->all();

    $options = [];
    $row = -1;
    foreach ($listOfRegions as $key => $region) {
      if ($key % 2 == 0) {
        $row++;
        $options[$row] = [];
      }

      $regionCuttedName = mb_substr($region->name, 0, 13);

      if (!in_array($region->id, $listOfUserRegionsId)) {
        $options[$row][] = [
          'text' => $regionCuttedName . ' ➕',
          'callback_data' => 'add_personal_region||' . $region->id
        ];
      } else {
        $options[$row][] = [
          'text' => $regionCuttedName . ' ❌',
          'callback_data' => 'delete_personal_region||' . $region->id
        ];
      }
    }

    self::apiRequest($requestType, [
      'chat_id' => $chatId,
      'text' => $text,
      'reply_markup' => self::inlineKeyboardBtn($options),
      'message_id'   => $message_id,
    ]);

    return true;
  }

  /**
   * Handler for add channel action with channel name operation
   *
   * @param int $chatId
   * @param int $userId
   * @param string $channelName
   * 
   * @return bool
   */
  static function add(int $chatId, int $userId, string $channelName): bool
  {
    $text = "К сожалению, мы не смогли найти канал с таким именем, попробуйте скопировать название из канала и ввести его снова.";

    $channel = self::apiRequest('getChat', [
      'chat_id' => $channelName
    ]);

    if ($channel != false) {
      $text = "Мы успешно подписали Вас на выбранный канал!";

      TGBotChannelSubscribtion::updateOrCreate(
        ['tg_channel_id' => $channel['id']],
        ['tg_channel_name' => $channel['username'], 'tg_channel_title' => $channel['title']]
      );

      TGUserSubscription::updateOrCreate(
        ['tg_user_id' => $userId, 'tg_bot_channel_subscription_id' => $channel['id']],
        ['tg_bot_channel_subscription_id' => $channel['id']]
      );
    }

    self::apiRequest('sendMessage', [
      'chat_id' => $chatId,
      'text' => $text
    ]);

    return true;
  }
}
