<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InstalacionEventoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isFinalizar = $this->routeIs('api.instalaciones.finalizar');

        return [
            'capturado_at'     => ['required', 'date'],
            'latitud'          => ['nullable', 'numeric', 'between:-90,90'],
            'longitud'         => ['nullable', 'numeric', 'between:-180,180'],
            'precision_metros' => ['nullable', 'integer', 'min:0'],
            'fuente'           => ['nullable', 'in:gps,network,cached,mock,no_disponible'],
            'notas'            => ['nullable', 'string', 'max:2000'],
            'foto'             => [
                $isFinalizar ? 'required' : 'nullable',
                'file',
                'mimes:jpg,jpeg,png',
                'max:10240',
            ],
        ];
    }
}
