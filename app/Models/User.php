<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    
    protected $table = 'usuarios';
    protected $primaryKey = 'usuario';
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = ['usuario', 'nombre', 'telegram_chat_id', 'correo', 'contraseña', 'rol'];
    protected $hidden = ['contraseña', 'remember_token'];
}
