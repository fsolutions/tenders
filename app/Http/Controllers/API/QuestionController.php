<?php

namespace App\Http\Controllers\API;

use App\User;
use App\Mail\SendQuestion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class QuestionController extends Controller
{
  public $userId;
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
  }

  /**
   * Send message to support
   *
   * @param  \Illuminate\Http\Request  $request
   * @return bool
   */
  public function sendMessage(Request $request)
  {
    if (Auth::check()) {
      $this->userId = Auth::user()->id;
    } else {
      $this->userId = NULL;
    }

    $requestData = $request->all();

    $question = [
      'subject' => $requestData['subject'],
      'question' => $requestData['question'],
    ];

    $userToSend = [
      'id' => 'Новый пользователь',
      'name' => 'Новый пользователь',
      'email' => 'Новый пользователь',
      'phone' => 'Новый пользователь'
    ];

    if ($this->userId != NULL) {
      $user = User::find($this->userId);
      $userToSend = [
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'phone' => $user->phone
      ];
    }

    Mail::to("info@gravescare.com")->send(new SendQuestion($question, $userToSend, 'Запрос в техподдержку пользователя через форму в ЛК'));

    return true;
  }
}
