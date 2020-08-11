@extends('emails.EmailTemplate')
@section('content')
<div class="lightbg" style="width: 70%; background-color: #fff; padding: 25px; margin: 0 auto;">
  <p style="font-size: 18px;">Привет!</p>

  <p>{{$title}}</p>

  <ul>
    <li><b>Пользователь. ID: {{ $user['id'] }}</b></li>
    <li><b>Пользователь. Имя: {{ $user['name'] }}</b></li>
    <li><b>Пользователь. Почта: {{ $user['email'] }}</b></li>
    <li><b>Пользователь. Телефон: {{ $user['phone'] }}</b></li>
    <li>Сообщение. Тема: {{ $question['subject'] }}</li>
    <li>Сообщение. Тело: {{ $question['question'] }}</li>
  </ul>

  <p><em><b>С уважением</b>,<br>
      Автоматическая система<br>
      сервиса Refollower.</em></p>

</div>
@stop