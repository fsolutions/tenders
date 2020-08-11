@extends('layouts.app')

@section('content')
<one-platform :imported-platform='@json($data)'></one-platform>
@endsection