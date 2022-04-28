<?php

namespace App\Bundles\Telegram\Actions;

use Throwable;
use App\Model\TGUserRegion;
use Illuminate\Support\Facades\Log;
use App\Traits\Telegram\RequestTrait;
use App\Bundles\Telegram\Actions\AddRegionAction as TelegramAddRegionAction;

class AddPersonalRegionAction
{
  use RequestTrait;

  /**
   * Handler for add subscribe action with region adding by id of region
   *
   * @param int $regionId
   * @param int $chatId
   * @param int $message_id
   * 
   * @return string
   */
  static function add(int $regionId, int $chatId, int $message_id): string
  {
    $userRegion = TGUserRegion::where([
      ['tg_user_id', $chatId],
      ['regions_id', $regionId],
    ])->get()->toArray();

    if (count($userRegion) == 0) {
      $text = "Подписка на регион успешно добавлена.";

      try {
        $userRegion = TGUserRegion::create([
          'tg_user_id' => $chatId,
          'regions_id' => $regionId
        ]);
        Log::info("Adding region subscribe with id: " . $userRegion->id . ' success');
      } catch (Throwable $e) {
        Log::error("Cant add channel subscribe for chatId " . $chatId);
      }

      TelegramAddRegionAction::start($chatId, $message_id, 'editMessageText');
    } else {
      $text = "Видимо подписка на регион уже существует, так как мы нашли ее в списке ваших подписок.";
    }

    return $text;
  }
}
