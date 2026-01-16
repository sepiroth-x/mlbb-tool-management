<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register - MLBB Tournament Manager</title>
    
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
            padding: 40px 0;
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
        
        /* Register Container */
        .register-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 520px;
            margin: 0 20px;
        }
        
        .register-card {
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
        
        .register-card::before {
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
        
        .register-card:hover::before {
            opacity: 0.2;
        }
        
        /* Logo & Header */
        .register-header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .register-logo {
            width: 90px;
            height: 90px;
            margin: 0 auto 20px;
            background: linear-gradient(135deg, #00d9ff, #7b2ff7);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Orbitron', sans-serif;
            font-size: 42px;
            font-weight: 900;
            color: #fff;
            text-shadow: 0 0 20px rgba(0, 217, 255, 0.8);
            box-shadow: 0 10px 30px rgba(0, 217, 255, 0.3);
            position: relative;
            overflow: hidden;
        }
        
        .register-logo::before {
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
        
        .register-title {
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
        
        .register-subtitle {
            color: #94a3b8;
            font-size: 16px;
            font-weight: 400;
        }
        
        /* Form Styles */
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            display: block;
            font-size: 13px;
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
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }
        
        .form-checkbox {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 24px;
        }
        
        .form-checkbox input[type="checkbox"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
            margin-top: 2px;
            flex-shrink: 0;
        }
        
        .form-checkbox label {
            color: #94a3b8;
            font-size: 13px;
            cursor: pointer;
            line-height: 1.5;
        }
        
        .form-checkbox label a {
            color: #00d9ff;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .form-checkbox label a:hover {
            color: #7b2ff7;
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
        
        /* Footer */
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
        
        .alert-danger ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }
        
        .alert-danger ul li {
            margin-bottom: 4px;
        }
        
        .alert-danger ul li:last-child {
            margin-bottom: 0;
        }
        
        /* Password Strength Indicator */
        .password-strength {
            margin-top: 8px;
            height: 4px;
            background: rgba(30, 41, 59, 0.6);
            border-radius: 2px;
            overflow: hidden;
            display: none;
        }
        
        .password-strength-bar {
            height: 100%;
            width: 0;
            transition: all 0.3s ease;
            border-radius: 2px;
        }
        
        .password-strength.active {
            display: block;
        }
        
        .strength-weak .password-strength-bar {
            width: 33%;
            background: #ef4444;
        }
        
        .strength-medium .password-strength-bar {
            width: 66%;
            background: #f59e0b;
        }
        
        .strength-strong .password-strength-bar {
            width: 100%;
            background: #22c55e;
        }
        
        /* Responsive */
        @media (max-width: 576px) {
            .register-card {
                padding: 40px 30px;
            }
            
            .register-title {
                font-size: 28px;
            }
            
            .form-row {
                grid-template-columns: 1fr;
                gap: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="bg-animation"></div>
    
    <div class="register-container">
        <div class="register-card">
            <div class="register-header">
                <div class="register-logo">M</div>
                <h1 class="register-title">Join Us</h1>
                <p class="register-subtitle">Create your MLBB Tournament Manager account</p>
            </div>
            
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form method="POST" action="{{ route('register') }}">
                @csrf
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="first_name" class="form-label">First Name</label>
                        <input 
                            type="text" 
                            id="first_name" 
                            name="first_name" 
                            class="form-input" 
                            placeholder="John"
                            value="{{ old('first_name') }}"
                            required 
                            autofocus
                        >
                    </div>
                    
                    <div class="form-group">
                        <label for="last_name" class="form-label">Last Name</label>
                        <input 
                            type="text" 
                            id="last_name" 
                            name="last_name" 
                            class="form-input" 
                            placeholder="Doe"
                            value="{{ old('last_name') }}"
                            required
                        >
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="name" class="form-label">Username</label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        class="form-input" 
                        placeholder="Choose a unique username"
                        value="{{ old('name') }}"
                        required
                    >
                </div>
                
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        class="form-input" 
                        placeholder="your.email@example.com"
                        value="{{ old('email') }}"
                        required
                    >
                </div>
                
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-input" 
                        placeholder="Create a strong password"
                        required
                        onkeyup="checkPasswordStrength(this.value)"
                    >
                    <div class="password-strength" id="passwordStrength">
                        <div class="password-strength-bar"></div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input 
                        type="password" 
                        id="password_confirmation" 
                        name="password_confirmation" 
                        class="form-input" 
                        placeholder="Re-enter your password"
                        required
                    >
                </div>
                
                <div class="form-checkbox">
                    <input type="checkbox" id="terms" name="terms" required>
                    <label for="terms">
                        I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>
                    </label>
                </div>
                
                <button type="submit" class="btn-primary">
                    Create Account
                </button>
            </form>
            
            <div class="form-footer">
                Already have an account? <a href="{{ route('login') }}">Sign in</a>
            </div>
        </div>
    </div>
    
    <script>
        function checkPasswordStrength(password) {
            const strengthIndicator = document.getElementById('passwordStrength');
            
            if (password.length === 0) {
                strengthIndicator.classList.remove('active', 'strength-weak', 'strength-medium', 'strength-strong');
                return;
            }
            
            strengthIndicator.classList.add('active');
            
            let strength = 0;
            
            // Length check
            if (password.length >= 8) strength++;
            if (password.length >= 12) strength++;
            
            // Character variety checks
            if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
            if (/\d/.test(password)) strength++;
            if (/[^a-zA-Z\d]/.test(password)) strength++;
            
            // Remove previous classes
            strengthIndicator.classList.remove('strength-weak', 'strength-medium', 'strength-strong');
            
            // Apply appropriate class
            if (strength <= 2) {
                strengthIndicator.classList.add('strength-weak');
            } else if (strength <= 4) {
                strengthIndicator.classList.add('strength-medium');
            } else {
                strengthIndicator.classList.add('strength-strong');
            }
        }
    </script>
</body>
</html>
