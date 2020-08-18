@extends('emails.EmailTemplate')
@section('content')
<div class="lightbg" style="width: 70%; background-color: #fff; padding: 25px; margin: 0 auto;">
  <p style="font-size: 18px;">Подробности для исполнителя!</p>

  <p>{{$title}}</p>

  <ul>
    <li><b>Исполнитель. ID: {{ $user['id'] }}</b></li>
    <li><b>Исполнитель. Имя: {{ $user['name'] }}</b></li>
    <li><b>Исполнитель. Почта: {{ $user['email'] }}</b></li>
    <li><b>Исполнитель. Телефон: {{ $user['phone'] }}</b></li>
    <li>Заказ. №{{ $order['id'] }}.</li>
    <li>Заказ. Контакт. Имя: {{ $order['user_web_users_name'] }}.</li>
    <li>Заказ. Контакт. Почта: {{ $order['user_web_users_email'] }}.</li>
    <li>Заказ. Контакт. Телефон: {{ $order['user_web_users_phone'] }}.</li>
    <li>Заказ. Ориентировочная сумма заказа: от {{ $order['itogsum'] }} руб.</li>
    <li>Заказ. Быстрая ссылка: <a href="https://tenders.gravescare.com/orders/page/{{ $order['id'] }}">https://tenders.gravescare.com/orders/page/{{ $order['id'] }}</a></li>
  </ul>

  <p><em><b>С уважением</b>,<br>
      Автоматическая система<br>
      сервиса Gravescare Tenders.</em></p>

</div>
@stop