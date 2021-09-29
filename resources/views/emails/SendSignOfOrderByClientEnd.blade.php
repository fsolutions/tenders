@extends('emails.EmailTemplate')
@section('content')
<div class="lightbg" style="width: 70%; background-color: #fff; padding: 25px; margin: 0 auto;">
  <p style="font-size: 18px;">Привет!</p>

  <p>{{$title}}</p>
  <p>Ссылка на акт для клиента: <a href="https://tenders.gravescare.com/orders/client/act/{{$id}}" target="_blank">перейти &raquo;</a></p>

  <p><em><b>С уважением</b>,<br>
      Автоматическая система<br>
      сервиса Gravescare Tenders.</em></p>

</div>
@stop