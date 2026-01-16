<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - MLBB Tournament Manager</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@300;400;500;600;700&family=Orbitron:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Rajdhani', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #e2e8f0;
            background: #0a0e1a;
            background-image: 
                radial-gradient(at 0% 0%, rgba(20, 40, 100, 0.4) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(100, 20, 150, 0.3) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(20, 100, 150, 0.3) 0px, transparent 50%),
                radial-gradient(at 0% 100%, rgba(100, 50, 20, 0.3) 0px, transparent 50%);
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        
        /* Animated Background Elements */
        .bg-animation {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            pointer-events: none;
        }
        
        .bg-animation::before,
        .bg-animation::after {
            content: '';
            position: absolute;
            width: 600px;
            height: 600px;
            border-radius: 50%;
            filter: blur(120px);
            opacity: 0.15;
            animation: float 20s infinite ease-in-out;
        }
        
        .bg-animation::before {
            background: linear-gradient(135deg, #00d9ff, #7b2ff7);
            top: -200px;
            left: -200px;
            animation-delay: -5s;
        }
        
        .bg-animation::after {
            background: linear-gradient(135deg, #ffd700, #ff6b35);
            bottom: -200px;
            right: -200px;
            animation-delay: -10s;
        }
        
        @keyframes float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(100px, -100px) scale(1.1); }
            66% { transform: translate(-100px, 100px) scale(0.9); }
        }
        
        /* Login Container */
        .login-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 480px;
            margin: 0 20px;
        }
        
        .login-card {
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 50px 40px;
            box-shadow: 
                0 20px 60px rgba(0, 0, 0, 0.5),
                0 0 80px rgba(0, 217, 255, 0.1),
                inset 0 0 40px rgba(0, 217, 255, 0.03);
            border: 1px solid rgba(0, 217, 255, 0.2);
            position: relative;
            overflow: hidden;
        }
        
        .login-card::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(135deg, #00d9ff 0%, #7b2ff7 50%, #ffd700 100%);
            border-radius: 24px;
            z-index: -1;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .login-card:hover::before {
            opacity: 0.2;
        }
        
        /* Logo & Header */
        .login-header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .login-logo {
            width: 100px;
            height: 100px;
            margin: 0 auto 20px;
            background: linear-gradient(135deg, #00d9ff, #7b2ff7);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Orbitron', sans-serif;
            font-size: 48px;
            font-weight: 900;
            color: #fff;
            text-shadow: 0 0 20px rgba(0, 217, 255, 0.8);
            box-shadow: 0 10px 30px rgba(0, 217, 255, 0.3);
            position: relative;
            overflow: hidden;
        }
        
        .login-logo::before {
            content: '';
            position: absolute;
            width: 150%;
            height: 150%;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            animation: shine 3s infinite;
        }
        
        @keyframes shine {
            0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
            100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
        }
        
        .login-title {
            font-family: 'Orbitron', sans-serif;
            font-size: 32px;
            font-weight: 800;
            background: linear-gradient(135deg, #00d9ff, #7b2ff7, #ffd700);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 10px;
            letter-spacing: 1px;
        }
        
        .login-subtitle {
            color: #94a3b8;
            font-size: 16px;
            font-weight: 400;
        }
        
        /* Form Styles */
        .form-group {
            margin-bottom: 24px;
        }
        
        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #cbd5e1;
            margin-bottom: 8px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        
        .form-input {
            width: 100%;
            padding: 14px 18px;
            background: rgba(30, 41, 59, 0.6);
            border: 2px solid rgba(0, 217, 255, 0.2);
            border-radius: 12px;
            color: #e2e8f0;
            font-size: 16px;
            font-family: 'Rajdhani', sans-serif;
            transition: all 0.3s ease;
            outline: none;
        }
        
        .form-input:focus {
            background: rgba(30, 41, 59, 0.8);
            border-color: #00d9ff;
            box-shadow: 0 0 20px rgba(0, 217, 255, 0.2);
        }
        
        .form-input::placeholder {
            color: #64748b;
        }
        
        .form-checkbox {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 24px;
        }
        
        .form-checkbox input[type="checkbox"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
        }
        
        .form-checkbox label {
            color: #94a3b8;
            font-size: 14px;
            cursor: pointer;
        }
        
        /* Button Styles */
        .btn-primary {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #00d9ff, #7b2ff7);
            border: none;
            border-radius: 12px;
            color: #fff;
            font-size: 18px;
            font-weight: 700;
            font-family: 'Rajdhani', sans-serif;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(0, 217, 255, 0.3);
            position: relative;
            overflow: hidden;
        }
        
        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 40px rgba(0, 217, 255, 0.5);
        }
        
        .btn-primary:hover::before {
            left: 100%;
        }
        
        .btn-primary:active {
            transform: translateY(0);
        }
        
        /* Links */
        .form-links {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            font-size: 14px;
        }
        
        .form-links a {
            color: #00d9ff;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .form-links a:hover {
            color: #7b2ff7;
            text-shadow: 0 0 10px rgba(0, 217, 255, 0.5);
        }
        
        .form-footer {
            text-align: center;
            margin-top: 32px;
            padding-top: 24px;
            border-top: 1px solid rgba(0, 217, 255, 0.1);
            color: #94a3b8;
            font-size: 14px;
        }
        
        .form-footer a {
            color: #00d9ff;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .form-footer a:hover {
            color: #7b2ff7;
            text-shadow: 0 0 10px rgba(0, 217, 255, 0.5);
        }
        
        /* Error Messages */
        .alert {
            padding: 14px 18px;
            border-radius: 10px;
            margin-bottom: 24px;
            font-size: 14px;
            font-weight: 500;
        }
        
        .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
        }
        
        .alert-success {
            background: rgba(34, 197, 94, 0.1);
            border: 1px solid rgba(34, 197, 94, 0.3);
            color: #86efac;
        }
        
        /* Social Login (Optional) */
        .social-login {
            margin-top: 32px;
            padding-top: 24px;
            border-top: 1px solid rgba(0, 217, 255, 0.1);
        }
        
        .social-login-text {
            text-align: center;
            color: #94a3b8;
            font-size: 14px;
            margin-bottom: 16px;
        }
        
        .social-buttons {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }
        
        .btn-social {
            padding: 12px;
            background: rgba(30, 41, 59, 0.6);
            border: 1px solid rgba(0, 217, 255, 0.2);
            border-radius: 10px;
            color: #e2e8f0;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .btn-social:hover {
            background: rgba(30, 41, 59, 0.9);
            border-color: #00d9ff;
            transform: translateY(-2px);
        }
        
        /* Responsive */
        @media (max-width: 576px) {
            .login-card {
                padding: 40px 30px;
            }
            
            .login-title {
                font-size: 28px;
            }
            
            .social-buttons {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="bg-animation"></div>
    
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="login-logo">M</div>
                <h1 class="login-title">Welcome Back</h1>
                <p class="login-subtitle">Sign in to your MLBB Tournament Manager account</p>
            </div>
            
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul style="list-style: none; margin: 0; padding: 0;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            
            <form method="POST" action="{{ route('login') }}">
                @csrf
                
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        class="form-input" 
                        placeholder="Enter your email"
                        value="{{ old('email') }}"
                        required 
                        autofocus
                    >
                </div>
                
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-input" 
                        placeholder="Enter your password"
                        required
                    >
                </div>
                
                <div class="form-links">
                    <div class="form-checkbox">
                        <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label for="remember">Remember me</label>
                    </div>
                    
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}">Forgot password?</a>
                    @endif
                </div>
                
                <button type="submit" class="btn-primary">
                    Login
                </button>
            </form>
            
            <div class="form-footer">
                Don't have an account? <a href="{{ route('register') }}">Sign up now</a>
            </div>
        </div>
    </div>
</body>
</html>
