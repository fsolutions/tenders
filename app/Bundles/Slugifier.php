<?php

namespace App\Bundles;

class Slugifier
{
  private static array $replace = [
    'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G',
    'Д' => 'D', 'Е' => 'E', 'Ё' => 'YO', 'Ж' => 'ZH', 'З' => 'Z', 'И' => 'I',
    'Й' => 'Y', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O',
    'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F',
    'Х' => 'H', 'Ц' => 'C', 'Ч' => 'CH', 'Ш' => 'SH', 'Щ' => 'SCH', 'Ъ' => '',
    'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'YU', 'Я' => 'YA', 'а' => 'a',
    'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo',
    'ж' => 'zh', 'з' => 'z', 'и' => 'i', 'й' => 'y', 'к' => 'k', 'л' => 'l',
    'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's',
    'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c', 'ч' => 'ch',
    'ш' => 'sh', 'щ' => 'sch', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e',
    'ю' => 'yu', 'я' => 'ya', "," => "", "." => "", "(" => "", ")" => "",
    "/" => "", "\/" => "", ";" => "", ":" => "", "%" => "", "&" => "", "$" => "",
    "@" => "", "#" => "", "^" => "", "*" => ""
  ];

  /**
   * Make slug from russian text string. Can be used for url generation from russian text
   *
   * @param string $text
   * @param string $separator
   * @param bool $upparcase
   * @return string
   */
  public static function make(string $text, string $separator = '_', bool $upparcase = true): string
  {
    $text = strtr($text, self::$replace);
    $text = preg_replace('~[^\\pL\d.]+~u', $separator, $text);
    $text = trim($text, $separator);
    $text = preg_replace('~[^-\w.]+~', '', $text);

    $text = strtolower($text);

    if ($upparcase) {
      $text = strtoupper($text);
    }

    return $text;
  }
}
