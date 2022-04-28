<?php

namespace App\Bundles\Telegram\Callbacks;

use Illuminate\Support\Facades\Log;
use App\Traits\Telegram\RequestTrait;
use App\Bundles\Telegram\Actions\AddPersonalRegionAction;
use App\Bundles\Telegram\Actions\DeletePersonalRegionAction;

class CallbackCombineAction
{
  use RequestTrait;

  /**
   * Handler of callback query
   *
   * @param string $callback_query
   * @param int $callback_query_id
   * @param int $chatId
   * @param int $message_id
   * 
   * @return bool
   */
  static function handler(string $callback_query, int $callback_query_id, int $chatId, int $message_id): bool
  {
    $findCommand = explode('||', $callback_query);
    $text = '';

    switch ($findCommand[0]) {
      case 'add_personal_region':
        $text = AddPersonalRegionAction::add($findCommand[1], $chatId, $message_id);
        break;

      case 'delete_personal_region':
        $text = DeletePersonalRegionAction::delete($findCommand[1], $chatId, $message_id);
        break;

      default:
        Log::info('Unknown callback_query operation: ' . $callback_query);
        break;
    }

    self::apiRequest('answerCallbackQuery', [
      'callback_query_id' => $callback_query_id,
      'text' => $text
    ]);

    return true;
  }
}
