<?php

namespace App\Bundles;

/**
 * Class EncrypterStrings
 * Encrypt/Decrypt data 
 * 
 * @package EncrypterStrings
 */

class EncrypterStrings
{

  /**
   * Crypt key for refollowers id crypt and encrypt
   *
   * @var string
   */
  public const CRYPT_KEYS = [
    '1' => 'c',
    '2' => 'd',
    '3' => 'g',
    '4' => 'h',
    '5' => 'j',
    '6' => 'k',
    '7' => 'm',
    '8' => 'o',
    '9' => 't'
  ];

  /**
   * 
   * EncrypterStrings constructor.
   * 
   */
  public function __construct()
  {
  }

  /**
   * Encrypting text
   * 
   * @param string $text  text
   * 
   * @return string
   */
  public function encrypt($text): string
  {
    $exploded = str_split($text);

    foreach ($exploded as $letkey => $letter) {
      if (array_key_exists($letter, self::CRYPT_KEYS)) {
        $exploded[$letkey] = self::CRYPT_KEYS[$letter];
      }
    }

    return implode('', $exploded);
  }


  /**
   * Decrypting text
   * 
   * @param string $text  text
   * 
   * @return string
   */
  public function decrypt($text): string
  {
    $exploded = str_split($text);

    foreach ($exploded as $letkey => $letter) {
      $key = array_search($letter, self::CRYPT_KEYS);
      if ($key != false) {
        $exploded[$letkey] = $key;
      }
    }

    return implode("", $exploded);
  }
}
