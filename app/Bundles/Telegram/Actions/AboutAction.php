<?php

namespace App\Bundles\Telegram\Actions;

use App\Model\TGUser;
use App\Traits\Telegram\RequestTrait;
use App\Traits\Telegram\MakeComponents;

class AboutAction
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
  static function make(int $chatId): bool
  {
    $text = "*Спасибо что пользуетесь ботом «Забота об ушедших»!*

Наш веб-сайт: https://gravescare.com
    
Мы всегда доступны в телеграме: @managergravescare
В whatsapp: +79300332833

Наш e-mail: info@gravescare.com";

    self::apiRequest('sendMessage', [
      'chat_id' => $chatId,
      'text' => $text
    ]);

    return true;
  }
}
