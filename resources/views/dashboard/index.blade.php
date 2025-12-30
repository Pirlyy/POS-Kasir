@extends('layouts.app')
@section('content_title', 'Dashboard')
@section('content')
<div>
    <div class="card-body">
        YOKOSO TO POS KASIR APLIKASI, <strong class="capitalize">{{ auth()->user()->name }}</strong>
    </div>
</div>
@endsection