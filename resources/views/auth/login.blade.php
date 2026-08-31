<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ProyectoSAVI - Iniciar Sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #fa9c21 0%, #99631b 100%);
            min-height: 100vh; display: flex; align-items: center; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-container { max-width: 420px; margin: 0 auto; width: 100%; padding: 20px; }
        .login-card {
            background: white; border-radius: 20px; box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            padding: 40px; animation: fadeInUp 0.5s ease;
        }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        .login-header { text-align: center; margin-bottom: 30px; }
        .login-header .icon { font-size: 64px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .login-header h1 { color: #2d3748; font-weight: 700; font-size: 28px; margin-top: 10px; }
        .login-header p { color: #718096; font-size: 14px; }
        .form-control { border-radius: 10px; padding: 12px 15px; border: 2px solid #e2e8f0; transition: all 0.3s; }
        .form-control:focus { border-color: #667eea; box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.25); }
        .input-group-text { background: #f7fafc; border: 2px solid #e2e8f0; border-right: none; border-radius: 10px 0 0 10px; color: #718096; }
        .input-group .form-control { border-radius: 0 10px 10px 0; border-left: none; }
        .btn-login {
            background: linear-gradient(135deg, #2144df 0%, #2c2581 100%); border: none; color: white;
            padding: 14px; font-weight: 600; border-radius: 10px; width: 100%; transition: all 0.3s; font-size: 16px;
        }
        .btn-login:hover { transform: translateY(-2px); box-shadow: 0 10px 25px rgba(19, 59, 235, 0.4); }
        .login-footer { text-align: center; margin-top: 25px; color: #a0aec0; font-size: 13px; }
        .version-badge { position: fixed; bottom: 20px; right: 20px; background: rgba(255,255,255,0.2); color: white; padding: 8px 16px; border-radius: 20px; font-size: 12px; backdrop-filter: blur(10px); }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="icon"> <img src="{{ asset('images/login.svg') }}" alt="{{ config('app.name') }}" style="max-width: 150px;"></i></div>
                <h1>ProyectoSAVI</h1>
                <p>Sistema de Gestión de Proyectos</p>
                <span class="badge bg-primary">v0.1.1</span>
            </div>

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show"><i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    @foreach($errors->all() as $error){{ $error }}<br>@endforeach
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="usuario" class="form-label fw-semibold">Usuario</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" class="form-control @error('usuario') is-invalid @enderror" id="usuario" name="usuario" placeholder="Ingresa tu usuario" value="{{ old('usuario') }}" required autofocus>
                    </div>
                    @error('usuario')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label for="contraseña" class="form-label fw-semibold">Contraseña</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" class="form-control @error('contraseña') is-invalid @enderror" id="contraseña" name="contraseña" placeholder="Ingresa tu contraseña" required>
                        <button class="btn btn-outline-secondary" type="button" id="togglePassword"><i class="bi bi-eye"></i></button>
                    </div>
                    @error('contraseña')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>

                <div class="mb-4 d-flex justify-content-between align-items-center">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="remember" name="remember">
                        <label class="form-check-label" for="remember">Recordarme</label>
                    </div>
                    <a href="#" class="text-decoration-none text-primary" style="font-size: 14px;">¿Olvidaste tu contraseña?</a>
                </div>

                <button type="submit" class="btn btn-login"><i class="bi bi-box-arrow-in-right me-2"></i>Iniciar Sesión</button>
            </form>

            <div class="login-footer">
                <i class="bi bi-shield-check me-1"></i> Sistema seguro con autenticación de usuarios
                <br><small>© {{ date('Y') }} ProyectoSAVI - Todos los derechos reservados</small>
            </div>
        </div>
    </div>
    <div class="version-badge"><i class="bi bi-code-square me-1"></i>ProyectoSAVI v0.1.1</div>
    <script>
        document.getElementById('togglePassword').addEventListener('click', function() {
            const pwd = document.getElementById('contraseña');
            const icon = this.querySelector('i');
            if (pwd.type === 'password') { pwd.type = 'text'; icon.className = 'bi bi-eye-slash'; }
            else { pwd.type = 'password'; icon.className = 'bi bi-eye'; }
        });
        setTimeout(function() {
            document.querySelectorAll('.alert').forEach(function(alert) {
                var bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
</body>
</html>