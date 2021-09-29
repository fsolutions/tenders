@extends('layouts.app')

@section('content')
<order-create :imported-order='@json($data ?? '')'></order-create>
@endsection