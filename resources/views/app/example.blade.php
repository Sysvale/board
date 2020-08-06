@extends('index')

@section('title')
	Nova página
@endsection

@section('css')
  <link rel="stylesheet" href="/css/app.css">
@endsection

@section('app-js')
	<script src="{{ mix('/js/app.min.js') }}"></script>
@endsection
