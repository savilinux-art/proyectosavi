<?php
namespace App\Http\Requests\Telegram;

use Illuminate\Foundation\Http\FormRequest;

class SendLocationRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Cambia si usas políticas
    }

    public function rules()
    {
        return [
            'id' => 'required|integer|exists:usuarios,id',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $usuario = \App\Models\Usuario::find($this->id);
            if (!$usuario) {
                $validator->errors()->add('id', 'Usuario no encontrado.');
                return;
            }
            if (empty($usuario->telegram_chat_id)) {
                $validator->errors()->add('id', 'El usuario no tiene chat_id de Telegram.');
            }
            // ✅ Validación del rol
            if ($usuario->rol !== 'instalador') {
                $validator->errors()->add('id', 'Solo los instaladores pueden recibir esta solicitud.');
            }
        });
    }
}