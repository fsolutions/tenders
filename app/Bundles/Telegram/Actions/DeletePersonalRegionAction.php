<?php

namespace App\Bundles\Telegram\Actions;

use Throwable;
use App\Model\TGUserRegion;
use Illuminate\Support\Facades\Log;
use App\Traits\Telegram\RequestTrait;
use App\Bundles\Telegram\Actions\AddRegionAction as TelegramAddRegionAction;

class DeletePersonalRegionAction
{
  use RequestTrait;

  /**
   * Handler for delete subscribe action with region deleting by id of region
   *
   * @param int $regionId
   * @param int $chatId
   * @param int $message_id
   * 
   * @return string
   */
  static function delete(int $regionId, int $chatId, int $message_id): string
  {
    $userRegion = TGUserRegion::where([
      ['tg_user_id', $chatId],
      ['regions_id', $regionId],
    ])->first();

    if ($userRegion) {
      $text = "Подписка на регион успешно удалена.";

      try {
        $userRegion->delete();
        Log::info("Deleting region subscribe with id: " . $userRegion->id . ' success');
      } catch (Throwable $e) {
        Log::error("Cant delete channel subscribe in chat " . $chatId);
      }

      TelegramAddRegionAction::start($chatId, $message_id, 'editMessageText');
    } else {
      $text = "Видимо подписка на регион уже удалена, так как мы не нашли ее в списке ваших подписок.";
    }

    return $text;
  }
}
