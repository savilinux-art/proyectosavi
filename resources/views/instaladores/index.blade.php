@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Instaladores</h1>
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Email</th>
                <th>Estado Telegram</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($instaladores as $instalador)
            <tr>
                <td>{{ $instalador->nombre }}</td>
                <td>{{ $instalador->email }}</td>
                <td>
                    @if($instalador->telegram_chat_id)
                        <span class="badge bg-success">✅ Conectado</span>
                    @else
                        <span class="badge bg-danger">❌ No conectado</span>
                    @endif
                </td>
                <td>
                    @if($instalador->telegram_chat_id)
                        <div class="btn-group" role="group">
                            <!-- Botón INICIAR -->
                            <form action="{{ route('telegram.send-start', $instalador->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('¿Enviar solicitud de INICIO a {{ $instalador->nombre }}?')">
                                    <i class="fas fa-play"></i> Iniciar
                                </button>
                            </form>
                            <!-- Botón FINALIZAR -->
                            <form action="{{ route('telegram.send-end', $instalador->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Enviar solicitud de FIN a {{ $instalador->nombre }}?')">
                                    <i class="fas fa-stop"></i> Finalizar
                                </button>
                            </form>
                        </div>
                    @else
                        <span class="text-muted">No disponible</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection