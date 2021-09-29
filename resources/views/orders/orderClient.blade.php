@extends('layouts.appMini')

@section('content')
<one-client-order :imported-order='@json($data ?? '')' :param='@json($additional)'></one-client-order>
@endsection