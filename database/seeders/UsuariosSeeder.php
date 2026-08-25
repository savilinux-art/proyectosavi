<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuario;
use App\Models\Rol;
use Illuminate\Support\Facades\Hash;

class UsuariosSeeder extends Seeder
{
    public function run(): void
    {
        // Verificar que los roles existen
        $roles = [
            'Administrador' => Rol::where('rol', 'Administrador')->first(),
            'Ventas' => Rol::where('rol', 'Ventas')->first(),
            'Instalador' => Rol::where('rol', 'Instalador')->first(),
            'Inventarios' => Rol::where('rol', 'Inventarios')->first(),
            'Contabilidad' => Rol::where('rol', 'Contabilidad')->first(),
            'Sistemas' => Rol::where('rol', 'Sistemas')->first(),
        ];

        $usuarios = [
            [
                'usuario' => 'admin',
                'nombre' => 'Administrador Principal',
                'correo' => 'admin@saviproyectos.com',
                'contraseña' => Hash::make('Admin123!'),
                'rol' => 'Administrador'
            ],
            [
                'usuario' => 'ventas1',
                'nombre' => 'Juan Pérez',
                'correo' => 'juan.ventas@saviproyectos.com',
                'contraseña' => Hash::make('Ventas123!'),
                'rol' => 'Ventas'
            ],
            [
                'usuario' => 'instalador1',
                'nombre' => 'Carlos López',
                'correo' => 'carlos.instalador@saviproyectos.com',
                'contraseña' => Hash::make('Instalador123!'),
                'rol' => 'Instalador'
            ],
            [
                'usuario' => 'inventarios1',
                'nombre' => 'María García',
                'correo' => 'maria.inventarios@saviproyectos.com',
                'contraseña' => Hash::make('Inventario123!'),
                'rol' => 'Inventarios'
            ],
            [
                'usuario' => 'contabilidad1',
                'nombre' => 'Ana Martínez',
                'correo' => 'ana.contabilidad@saviproyectos.com',
                'contraseña' => Hash::make('Contabilidad123!'),
                'rol' => 'Contabilidad'
            ],
            [
                'usuario' => 'sistemas1',
                'nombre' => 'Roberto Sánchez',
                'correo' => 'roberto.sistemas@saviproyectos.com',
                'contraseña' => Hash::make('Sistemas123!'),
                'rol' => 'Sistemas'
            ]
        ];
        
        $count = 0;
        foreach ($usuarios as $usuario) {
            // Verificar si el usuario ya existe
            $existing = Usuario::where('usuario', $usuario['usuario'])->first();
            
            if (!$existing) {
                // Crear nuevo usuario
                Usuario::create($usuario);
                $count++;
            } else {
                // Actualizar usuario existente (opcional)
                $existing->update([
                    'nombre' => $usuario['nombre'],
                    'correo' => $usuario['correo'],
                    'contraseña' => $usuario['contraseña'],
                    'rol' => $usuario['rol']
                ]);
                $this->command->info("Usuario {$usuario['usuario']} actualizado");
            }
        }
        
        $this->command->info('Usuarios procesados: ' . count($usuarios) . ' (nuevos: ' . $count . ')');
        $this->command->info('Usuario admin: admin / Admin123!');
    }
}