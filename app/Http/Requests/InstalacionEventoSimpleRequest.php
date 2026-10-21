<?php

namespace App\Http\Requests;

class InstalacionEventoSimpleRequest extends InstalacionEventoRequest
{
    public function rules(): array
    {
        return [
            'tipo'             => ['required', 'in:incidencia,nota_agregada'],
            'capturado_at'     => ['required', 'date'],
            'descripcion'      => ['nullable', 'string', 'max:2000'],
            'payload_json'     => ['nullable', 'array'],
            'latitud'          => ['nullable', 'numeric', 'between:-90,90'],
            'longitud'         => ['nullable', 'numeric', 'between:-180,180'],
            'precision_metros' => ['nullable', 'integer', 'min:0'],
            'fuente'           => ['nullable', 'in:gps,network,cached,mock,no_disponible'],
            'foto'             => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:10240'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($v) {
            if (!$this->filled('descripcion') && empty($this->input('payload_json'))) {
                $v->errors()->add(
                    'descripcion',
                    'Se requiere descripcion o payload_json.'
                );
            }
        });
    }
}