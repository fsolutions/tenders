<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ListsController extends Controller
{
  /**
   * Load service list
   *
   * @return void
   */
  public function servicesIndex()
  {
    $services = DB::table('modx_site_content')
      ->where('parent', 21370)
      ->where('published', 1)
      ->orderBy('modx_site_content.pagetitle')
      ->get(['id', 'pagetitle as name']);


    return $services;
  }

  /**
   * Load cities list
   *
   * @return void
   */
  public function citiesIndex()
  {
    $regionsAndCities = DB::table('modx_site_content')
      ->whereIn('template', [5, 6])
      ->where('published', 1)
      ->select(['id', 'pagetitle', 'parent'])
      ->get();


    $result = [];

    foreach ($regionsAndCities as $geoObject) {
      if ($geoObject->id != 20004 && $geoObject->parent == 20004) {
        $result[$geoObject->id] = [
          'id' => $geoObject->id,
          'name' => $geoObject->pagetitle,
          'cities' => []
        ];
      }
    }

    foreach ($regionsAndCities as $geoObject) {
      if ($geoObject->id != 20004 && $geoObject->parent != 20004) {
        $result[$geoObject->parent]['cities'][] = [
          'id' => $geoObject->id,
          'name' => $geoObject->pagetitle,
        ];
      }
    }

    return $result;
  }

  /**
   * Load graveyards by city_id
   *
   * @param int $city_id
   * @return void
   */
  public function graveyardsIndex($city_id)
  {
    $graveyards = DB::table('modx_site_content')
      ->where('template', 7)
      ->where('parent', $city_id)
      ->where('published', 1)
      ->select(['id', 'pagetitle as name'])
      ->get();

    return $graveyards;
  }
}
