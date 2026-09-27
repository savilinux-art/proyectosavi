@extends('layouts.app')
@section('page-title', 'Nuevo Recordatorio')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-alarm"></i> Nuevo Recordatorio</h1>
</div>
<div class="card"><div class="card-body">
    <form action="{{ route('recordatorios.store') }}" method="POST">
        @csrf
        @include('recordatorios._form')
    </form>
</div></div>
@endsection
