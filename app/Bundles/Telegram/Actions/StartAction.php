<?php

namespace App\Bundles\Telegram\Actions;

use App\Model\TGUser;
use App\Traits\Telegram\RequestTrait;
use App\Traits\Telegram\MakeComponents;

class StartAction
{
  use RequestTrait;
  use MakeComponents;

  /**
   * Handler for start action
   *
   * @param int $chatId
   * @param int $userId
   * @param string $userName
   * 
   * @return bool
   */
  static function make(int $chatId, int $userId, string $userName): bool
  {
    $text = "*Добро пожаловать в бота сервиса «Забота об ушедших» по поиску разовых работ!*
    
Здесь вы можете найти себе заказ на исполнение различных услуг: уборка могилок, изготовление и реставрация памятников, приобретение мест на кладбище и многие другие услуги.

Важно: По умолчанию, вы будете видеть Заказы со всей России.

Меню ниже поможет Вам сориентироваться.";

    $options = [
      [
        ['text' => "Отслеживать все заказы", 'callback_data' => 'vse_zakazy']
      ],
      [
        ['text' => "Отслеживать заказы по заданным регионам", 'callback_data' => 'regionalnie_zakazy'],
      ],
      [
        ['text' => "Задать мои регионы", 'callback_data' => 'ustanovit_moi_regiony']
      ]
    ];

    self::apiRequest('sendPhoto', [
      'chat_id' => $chatId,
      'photo' => 'https://tenders.gravescare.com/img/promo/start_screen.jpg',
      'caption' => '',
      'reply_markup' => self::keyboardBtn($options)
    ]);

    self::apiRequest('sendMessage', [
      'chat_id' => $chatId,
      'text' => 'В ближайшее время тут появятся новые заказы...',
      // 'reply_markup' => self::keyboardBtn($options)
    ]);

    TGUser::updateOrCreate(
      [
        'tg_user_id' => $userId
      ],
      [
        'tg_username' => $userName,
      ]
    );

    return true;
  }
}
