@extends('layouts.app')
@section('page-title', 'Editar Recordatorio')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-alarm"></i> Editar Recordatorio #{{ $recordatorio->id }}</h1>
</div>
<div class="card"><div class="card-body">
    <form action="{{ route('recordatorios.update', $recordatorio->id) }}" method="POST">
        @csrf @method('PUT')
        @include('recordatorios._form')
    </form>
</div></div>
@endsection
