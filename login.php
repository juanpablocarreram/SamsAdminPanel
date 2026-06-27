<?php
session_start();
if (!empty($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}
$error = $_GET['error'] ?? '';
$tab   = $_GET['tab']   ?? 'login';
$googlePending = $_SESSION['google_pending'] ?? null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SAMS · Iniciar sesión</title>
    <script>window.tailwind={config:{theme:{extend:{colors:{'sams-blue':'#003DA5','sams-yellow':'#FFC220','sams-red':'#C8102E'}}}}}</script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #0f172a; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .input-dark { background: #1e293b; border: 1.5px solid #334155; color: #f1f5f9; border-radius: 8px; padding: 10px 14px; font-size: .9rem; width: 100%; font-family: inherit; transition: border-color .15s; outline: none; }
        .input-dark:focus { border-color: #003DA5; box-shadow: 0 0 0 3px rgba(0,61,165,.15); }
        .input-dark::placeholder { color: #475569; }
        .btn-primary { background: #003DA5; color: #fff; border: none; border-radius: 8px; padding: 10px 0; font-size: .92rem; font-weight: 700; width: 100%; cursor: pointer; font-family: inherit; transition: background .15s; }
        .btn-primary:hover { background: #002d7a; }
        .btn-google { background: #1e293b; color: #f1f5f9; border: 1.5px solid #334155; border-radius: 8px; padding: 9px 0; font-size: .88rem; font-weight: 600; width: 100%; cursor: pointer; font-family: inherit; display: flex; align-items: center; justify-content: center; gap: 8px; transition: border-color .15s; text-decoration: none; }
        .btn-google:hover { border-color: #475569; }
        .tab-btn { flex: 1; padding: 8px; border-radius: 6px; font-size: .85rem; font-weight: 600; cursor: pointer; border: none; font-family: inherit; transition: all .15s; }
        .tab-btn.active { background: #003DA5; color: #fff; }
        .tab-btn.inactive { background: transparent; color: #64748b; }
        .tab-btn:hover.inactive { color: #94a3b8; }
        .error-box { background: rgba(239,68,68,.12); border: 1px solid rgba(239,68,68,.3); color: #fca5a5; border-radius: 8px; padding: 10px 14px; font-size: .82rem; margin-bottom: 14px; }
    </style>
</head>
<body>
    <div class="w-full max-w-sm mx-auto px-4">
        <!-- Logo / Brand -->
        <div class="text-center mb-6">
            <img src="img/sams_logo.png" alt="Sam's" class="h-10 mx-auto mb-2">
            <p class="text-slate-400 text-sm">Panel de Administración</p>
        </div>

        <!-- Card -->
        <div class="bg-gray-900 border border-slate-700/50 rounded-2xl shadow-xl p-6">

            <!-- Tab Switcher -->
            <div class="flex gap-1 bg-slate-800 rounded-lg p-1 mb-5">
                <button class="tab-btn <?= $tab === 'login' ? 'active' : 'inactive' ?>" onclick="switchTab('login')" id="tab-login">Iniciar sesión</button>
                <button class="tab-btn <?= $tab === 'register' ? 'active' : 'inactive' ?>" onclick="switchTab('register')" id="tab-register">Registrarse</button>
            </div>

            <!-- Error block -->
            <?php if ($error): ?>
            <div class="error-box">
                <?php
                $msgs = [
                    'credenciales' => 'Email o contraseña incorrectos.',
                    'campos'       => 'Por favor completa todos los campos.',
                    'email_exists' => 'Ya existe una cuenta con ese email.',
                    'server'       => 'Error del servidor. Intenta de nuevo.',
                    'invalid'      => 'Sesión inválida. Por favor inicia el proceso de nuevo.',
                ];
                echo htmlspecialchars($msgs[$error] ?? 'Error desconocido.');
                ?>
            </div>
            <?php endif; ?>

            <!-- Panel: Login -->
            <div id="panel-login" <?= $tab !== 'login' ? 'style="display:none"' : '' ?>>
                <form method="POST" action="auth_handler.php">
                    <input type="hidden" name="action" value="login">
                    <div class="mb-3">
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5">Email</label>
                        <input type="email" name="email" class="input-dark" placeholder="admin@sams.mx" required>
                    </div>
                    <div class="mb-5">
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5">Contraseña</label>
                        <input type="password" name="password" class="input-dark" placeholder="••••••••" required>
                    </div>
                    <button type="submit" class="btn-primary mb-3">Iniciar sesión</button>
                </form>
                <div class="relative my-3">
                    <div class="border-t border-slate-700/60"></div>
                    <span class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-gray-900 px-3 text-xs text-slate-500">o</span>
                </div>
                <a href="google_callback.php?start=1" class="btn-google">
                    <!-- Google SVG icon inline -->
                    <svg width="16" height="16" viewBox="0 0 48 48"><path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/><path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/><path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/><path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/></svg>
                    Continuar con Google
                </a>
            </div>

            <!-- Panel: Register -->
            <div id="panel-register" <?= $tab !== 'register' ? 'style="display:none"' : '' ?>>
                <?php if ($googlePending): ?>
                <div class="mb-4 p-3 bg-blue-900/30 border border-blue-700/40 rounded-lg text-xs text-blue-300">
                    Completando registro con Google. Solo falta el nombre de tu sucursal.
                </div>
                <form method="POST" action="auth_handler.php">
                    <input type="hidden" name="action" value="register_google">
                    <div class="mb-3">
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5">Nombre</label>
                        <input type="text" class="input-dark opacity-60" value="<?= htmlspecialchars($googlePending['nombre']) ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5">Email</label>
                        <input type="email" class="input-dark opacity-60" value="<?= htmlspecialchars($googlePending['email']) ?>" readonly>
                    </div>
                    <div class="mb-5">
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5">Nombre de tu Sucursal</label>
                        <input type="text" name="nombre_sucursal" class="input-dark" placeholder="Ej: Sam's Polanco" required>
                    </div>
                    <button type="submit" class="btn-primary">Crear mi sucursal</button>
                </form>
                <?php else: ?>
                <form method="POST" action="auth_handler.php">
                    <input type="hidden" name="action" value="register">
                    <div class="mb-3">
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5">Nombre completo</label>
                        <input type="text" name="nombre" class="input-dark" placeholder="Juan García" required>
                    </div>
                    <div class="mb-3">
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5">Email</label>
                        <input type="email" name="email" class="input-dark" placeholder="admin@sams.mx" required>
                    </div>
                    <div class="mb-3">
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5">Contraseña</label>
                        <input type="password" name="password" class="input-dark" placeholder="••••••••" required minlength="6">
                    </div>
                    <div class="mb-5">
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5">Nombre de tu Sucursal</label>
                        <input type="text" name="nombre_sucursal" class="input-dark" placeholder="Ej: Sam's Polanco" required>
                        <p class="text-xs text-slate-500 mt-1.5">Se creará una nueva sucursal con este nombre y serás su administrador.</p>
                    </div>
                    <button type="submit" class="btn-primary">Crear cuenta y sucursal</button>
                </form>
                <?php endif; ?>
            </div>

        </div>
    </div>

    <script>
    function switchTab(tab) {
        document.getElementById('panel-login').style.display = tab === 'login' ? 'block' : 'none';
        document.getElementById('panel-register').style.display = tab === 'register' ? 'block' : 'none';
        document.getElementById('tab-login').className = 'tab-btn ' + (tab === 'login' ? 'active' : 'inactive');
        document.getElementById('tab-register').className = 'tab-btn ' + (tab === 'register' ? 'active' : 'inactive');
    }
    document.addEventListener('DOMContentLoaded', () => lucide.createIcons());
    </script>
</body>
</html>
