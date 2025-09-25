<!DOCTYPE html>
<html lang="fr" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un compte - Dentina</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #f5e6da 0%, #8eb6f7ff 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 10px;
            position: relative;
            overflow-x: hidden;
        }

        .register-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 28px;
            width: 100%;
            max-width: 460px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            position: relative;
            z-index: 1;
            max-height: 95vh;
            /* occupe max 95% de la hauteur */
            overflow-y: auto;
            /* si jamais trop petit écran */
        }

        .logo {
            text-align: center;
            margin-bottom: 18px;
        }

        .logo-content {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 6px;
        }

        .logo-img {
            width: 45px;
            height: auto;
        }

        .logo-title {
            font-size: 22px;
            font-weight: 700;
            color: #1e293b;
        }

        .logo-subtitle {
            color: #64748b;
            font-size: 13px;
        }

        .form-title {
            font-size: 20px;
            font-weight: 600;
            color: #1e293b;
            text-align: center;
            margin-bottom: 12px;
        }

        .form-subtitle {
            text-align: center;
            color: #64748b;
            font-size: 13px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 14px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: #374151;
            margin-bottom: 4px;
        }

        .required {
            color: #ef4444;
        }

        .form-input {
            width: 100%;
            padding: 10px 14px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
            background: rgba(248, 250, 252, 0.9);
            transition: all 0.3s ease;
            outline: none;
        }

        .form-input:focus {
            border-color: #3b82f6;
            background: white;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.15);
        }

        .register-button {
            width: 100%;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .register-button:hover:not(:disabled) {
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(59, 130, 246, 0.3);
        }

        .divider {
            text-align: center;
            margin: 18px 0;
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #e2e8f0;
        }

        .divider-text {
            background: rgba(255, 255, 255, 0.95);
            color: #64748b;
            padding: 0 12px;
            font-size: 13px;
            position: relative;
        }

        .login-link {
            text-align: center;
            color: #64748b;
            font-size: 13px;
        }

        .login-link a {
            color: #3b82f6;
            text-decoration: none;
            font-weight: 600;
        }

        .login-link a:hover {
            color: #1d4ed8;
        }
    </style>
</head>

<body>
    <div class="register-container">
        <!-- Logo Section -->
        <div class="logo">
            <div class="logo-content">
                <img src="{{ asset('images/dent.png') }}" alt="Logo dent" class="logo-img">

            </div>
            <p class="logo-subtitle">Cabinet Dentaire</p>
        </div>

        <!-- Register Form -->
        <h2 class="form-title">Créer un compte</h2>
        <p class="form-subtitle">Remplissez vos informations</p>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="form-group">
                <label for="name" class="form-label">Nom complet <span class="required">*</span></label>
                <input type="text" id="name" name="name" class="form-input" placeholder="Dr. Ahmed Ben Ali" required>
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Email <span class="required">*</span></label>
                <input type="email" id="email" name="email" class="form-input" placeholder="docteur@cabinet.com" required>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Mot de passe <span class="required">*</span></label>
                <input type="password" id="password" name="password" class="form-input" placeholder="••••••••" required>
            </div>

            <div class="form-group">
                <label for="password_confirmation" class="form-label">Confirmer <span class="required">*</span></label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" placeholder="••••••••" required>
            </div>

            <button type="submit" class="register-button">Créer mon compte</button>
        </form>

        <div class="divider"><span class="divider-text">Déjà inscrit ?</span></div>

        <div class="login-link">
            Vous avez déjà un compte ? <a href="{{ route('login') }}">Se connecter</a>
        </div>
    </div>
</body>

</html>