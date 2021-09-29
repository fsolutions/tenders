@extends('layouts.app')

@section('content')
<one-order :imported-order='@json($data)'></one-order>
@endsection