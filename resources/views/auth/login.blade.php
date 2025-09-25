<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Dentina</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5e6da 0%, #8eb6f7ff 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-card {
            background: #fffefb;
            border-radius: 18px;
            padding: 40px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
        }

        .logo {
            text-align: center;
            margin-bottom: 25px;
        }

        .logo-icon {
            width: 70px;
            height: 70px;
            background: #3b82f6;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 14px auto;
        }

        .logo-icon svg {
            width: 36px;
            height: 36px;
        }

        .logo-title {
            font-size: 26px;
            font-weight: 700;
            color: #333;
        }

        .logo-subtitle {
            color: #777;
            font-size: 15px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-label {
            display: block;
            font-weight: 500;
            color: #444;
            margin-bottom: 6px;
            font-size: 14px;
        }

        .form-input {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e5e5e5;
            border-radius: 10px;
            font-size: 15px;
            transition: border-color .3s;
        }

        .form-input:focus {
            outline: none;
            border-color: #3b82f6;
        }

        .error-message {
            color: #d9534f;
            font-size: 13px;
            margin-top: 4px;
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .remember-me {
            color: #666;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .checkbox {
            width: 16px;
            height: 16px;
        }

        .forgot-link {
            color: #3b82f6;
            text-decoration: none;
        }

        .forgot-link:hover {
            text-decoration: underline;
        }

        .login-button {
            width: 100%;
            background: #3b82f6;
            color: white;
            border: none;
            padding: 15px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color .3s;
        }

        .login-button:hover {
            background: #375c97ff;
        }

        .register-link {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #555;
        }

        .register-link a {
            color: #3b82f6;
            text-decoration: none;
            font-weight: 500;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        .logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo-content {
            display: flex;
            align-items: center;
            justify-content: center;
            /* centre horizontalement */
            gap: 10px;
            /* espace entre l’image et le texte */
            margin-bottom: 8px;
        }

        .logo-img {
            width: 50px;
            height: auto;
        }
         .form-title {
            font-size: 20px;
            font-weight: 600;
            color: #1e293b;
            text-align: center;
            margin-bottom: 12px;
        }
    </style>
</head>

<body>
    <div class="login-card">
        <!-- Logo -->
        <div class="logo">
            <div class="logo-content">
                <img src="{{ asset('images/dent.png') }}" alt="Logo dent" class="logo-img">
                
            </div>
            <p class="logo-subtitle">Cabinet Dentaire</p>
        </div>
        <h2 class="form-title">Connexion</h2>

        <!-- Form -->
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-input" value="{{ old('email') }}" required>
                @error('email') <div class="error-message">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" id="password" name="password" class="form-input" required>
                @error('password') <div class="error-message">{{ $message }}</div> @enderror
            </div>

            <div class="form-options">
                <label class="remember-me">
                    <input type="checkbox" name="remember" class="checkbox"> Se souvenir
                </label>
                @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="forgot-link">Mot de passe oublié ?</a>
                @endif
            </div>

            <button type="submit" class="login-button">Se connecter</button>
        </form>

        <div class="register-link">
            Pas de compte ? <a href="{{ route('register') }}">S'inscrire</a>
        </div>
    </div>
</body>

</html>