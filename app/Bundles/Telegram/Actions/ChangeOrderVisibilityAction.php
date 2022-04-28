<?php

namespace App\Bundles\Telegram\Actions;

use App\Model\TGUser;
use App\Traits\Telegram\RequestTrait;
use App\Traits\Telegram\MakeComponents;

class ChangeOrderVisibilityAction
{
  use RequestTrait;
  use MakeComponents;

  /**
   * Handler for all orders feeder action
   *
   * @param int $chatId
   * @param int $userId
   * 
   * @return bool
   */
  static function allOrders(int $chatId, int $userId): bool
  {
    $text = "Вы активировали ленту заказов по всей России. Вы всегда можете переключиться на региональную ленту заказов, выбрав нужную опцию в меню.";

    $options = [
      [
        ['text' => "Отслеживать заказы по заданным регионам", 'callback_data' => 'regionalnie_zakazy'],
      ],
    ];

    self::apiRequest('sendMessage', [
      'chat_id' => $chatId,
      'text' => $text,
      'reply_markup' => self::keyboardBtn($options)
    ]);

    $TGUser = TGUser::updateOrCreate(
      [
        'tg_user_id' => $userId
      ],
      [
        'tg_user_id' => $userId
      ]
    );

    $TGUser->only_regions = 0;
    $TGUser->save();

    return true;
  }

  /**
   * Handler for only regions orders feeder action
   *
   * @param int $chatId
   * @param int $userId
   * 
   * @return bool
   */
  static function onlyRegions(int $chatId, int $userId): bool
  {
    $text = "Вы активировали региональную ленту заказов. Вы всегда можете переключиться на ленту заказов по всей России, выбрав нужную опцию в меню.";

    $options = [
      [
        ['text' => "Отслеживать все заказы", 'callback_data' => 'vse_zakazy']
      ],
      [
        ['text' => "Задать мои регионы", 'callback_data' => 'ustanovit_moi_regiony']
      ],
    ];

    self::apiRequest('sendMessage', [
      'chat_id' => $chatId,
      'text' => $text,
      'reply_markup' => self::keyboardBtn($options)
    ]);

    $TGUser = TGUser::updateOrCreate(
      [
        'tg_user_id' => $userId
      ],
      [
        'tg_user_id' => $userId
      ]
    );

    $TGUser->only_regions = 1;
    $TGUser->save();

    return true;
  }
}
