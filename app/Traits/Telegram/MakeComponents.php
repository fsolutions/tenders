<?php

namespace App\Traits\Telegram;

trait MakeComponents
{
  /**
   * Keyboard buttons
   *
   * @param array $options
   * @return string
   */
  static function keyboardBtn(array $options): string
  {
    $keyboard = [
      'keyboard' => $options,
      'resize_keyboard' => true,
      'one_time_keyboard' => false,
      'selective' => true
    ];

    return json_encode($keyboard);
  }

  /**
   * Inline keyboard buttons
   *
   * @param array $options
   * @return string
   */
  static function inlineKeyboardBtn(array $options): string
  {
    $keyboard = [
      'inline_keyboard' => $options,
      'resize_keyboard' => true,
      'one_time_keyboard' => false,
      'selective' => true
    ];

    return json_encode($keyboard);
  }

  /**
   * Add an inline button.
   *
   * @param string $text
   * @param string $url
   * @param int    $columns
   *
   * @return : string
   */
  static function button($text, $url, $columns = 2): string
  {
    $buttons = [];
    $buttons[] = compact('text', 'url');

    return json_encode([
      'inline_keyboard' => array_chunk($buttons, $columns),
    ]);
  }
}
