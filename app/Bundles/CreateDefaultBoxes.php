<?php

namespace App\Bundles;

use App\Model\Boxes;

/**
 * Class CreateDefaultBoxes
 * Preparing data for user transport reporting
 * 
 * @package CreateDefaultBoxes
 */

class CreateDefaultBoxes
{
  /**
   * 
   * CreateDefaultBoxes constructor.
   * 
   */
  public function __construct()
  {
  }

  /**
   * Creating default boxes
   * 
   * @param integer $platformId platform id
   * 
   * @return array
   */
  public function create($platformId): array
  {
    $boxes = [];

    $boxes[0] = Boxes::create([
      'platform_id' => $platformId,
      'index' => 1,
      'grad'  => 5,
      'way' => '5%',
      'way_name' => 'Скидка',
      'way_description' => 'Приведи на сайт 5 друзей и получи скидку!',
      'secret_code' => '',
    ]);
    $boxes[1] = Boxes::create([
      'platform_id' => $platformId,
      'index' => 2,
      'grad'  => 10,
      'way' => '10%',
      'way_name' => 'Скидка',
      'way_description' => 'Приведи на сайт 10 друзей и получи скидку!',
      'secret_code' => '',
    ]);
    $boxes[2] = Boxes::create([
      'platform_id' => $platformId,
      'index' => 3,
      'grad'  => 15,
      'way' => '15%',
      'way_name' => 'Скидка',
      'way_description' => 'Приведи на сайт 15 друзей и получи скидку!',
      'secret_code' => '',
    ]);
    $boxes[4] = Boxes::create([
      'platform_id' => $platformId,
      'index' => 4,
      'grad'  => 20,
      'way' => '20%',
      'way_name' => 'Скидка',
      'way_description' => 'Приведи на сайт 20 друзей и получи скидку!',
      'secret_code' => '',
    ]);

    return $boxes;
  }
}
