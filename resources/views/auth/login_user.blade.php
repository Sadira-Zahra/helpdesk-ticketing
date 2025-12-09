<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login User - Helpdesk System</title>
    
    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="{{ asset('public/templates/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/templates/dist/css/adminlte.min.css') }}">
    
    <style>
        body {
            background: linear-gradient(135deg, #0c4a6e 0%, #164e63 50%, #155e75 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
        }
        
        .login-container {
            width: 100%;
            max-width: 450px;
        }
        
        .login-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .login-header {
            background: linear-gradient(135deg, #06b6d4, #0891b2);
            padding: 40px 30px;
            text-align: center;
            color: white;
        }
        
        .logo-container {
            width: 100px;
            height: 100px;
            background: white;
            border-radius: 15px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            padding: 10px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }
        
        .logo-container img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        
        .login-header h1 {
            font-size: 1.8em;
            font-weight: 700;
            margin: 0 0 8px 0;
        }
        
        .login-header p {
            margin: 0;
            opacity: 0.95;
            font-size: 0.95em;
        }
        
        .login-body {
            padding: 40px 35px;
        }
        
        .badge-user {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #06b6d4, #0891b2);
            color: white;
            padding: 8px 18px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.9em;
            margin-bottom: 25px;
            box-shadow: 0 4px 12px rgba(6, 182, 212, 0.3);
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #334155;
            font-weight: 600;
            font-size: 0.95em;
        }
        
        .input-group {
            position: relative;
        }
        
        .input-group-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 1.1em;
            z-index: 1;
        }
        
        .form-control {
            width: 100%;
            padding: 13px 15px 13px 45px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 1em;
            transition: all 0.3s ease;
            background: #f8fafc;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #06b6d4;
            background: white;
            box-shadow: 0 0 0 4px rgba(6, 182, 212, 0.1);
        }
        
        .form-control.is-invalid {
            border-color: #ef4444;
            background: #fef2f2;
        }
        
        .invalid-feedback {
            color: #ef4444;
            font-size: 0.85em;
            margin-top: 6px;
            display: block;
        }
        
        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #06b6d4, #0891b2);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1.05em;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 6px 20px rgba(6, 182, 212, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(6, 182, 212, 0.4);
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
        
        .login-footer {
            padding: 25px 35px;
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
        }
        
        .footer-links {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }
        
        .back-link, .register-link {
            color: #0c4a6e;
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            font-size: 0.9em;
        }
        
        .back-link:hover {
            color: #06b6d4;
            gap: 12px;
        }
        
        .register-link {
            color: #10b981;
        }
        
        .register-link:hover {
            color: #059669;
            gap: 12px;
        }
        
        .alert {
            padding: 12px 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .alert-danger {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }
        
        .alert-success {
            background: #f0fdf4;
            color: #14532d;
            border: 1px solid #bbf7d0;
        }
        
        @media (max-width: 576px) {
            .login-header {
                padding: 30px 20px;
            }
            
            .login-header h1 {
                font-size: 1.5em;
            }
            
            .login-body {
                padding: 30px 25px;
            }
            
            .login-footer {
                padding: 20px 25px;
            }
            
            .footer-links {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <!-- Header -->
            <div class="login-header">
                <div class="logo-container">
                    <img src="{{ asset('public/templates/dist/img/LogoIsuzu.png') }}" alt="Logo" onerror="this.style.display='none'">
                </div>
                <h1>Login User</h1>
                <p>Helpdesk System PT. Mesin Isuzu Indonesia</p>
            </div>
            
            <!-- Body -->
            <div class="login-body">
                <div class="badge-user">
                    <i class="fas fa-user"></i>
                    <span>Portal User</span>
                </div>
                
                {{-- Error Messages --}}
                @if($errors->has('error'))
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>{{ $errors->first('error') }}</span>
                    </div>
                @endif
                
                @if(session('success'))
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif
                
                <form action="{{ route('login_user.post') }}" method="POST">
                    @csrf
                    
                    {{-- USERNAME INPUT --}}
                    <div class="form-group">
                        <label for="username">Username</label>
                        <div class="input-group">
                            <i class="fas fa-user input-group-icon"></i>
                            <input type="text" 
                                   name="username" 
                                   id="username" 
                                   class="form-control @error('username') is-invalid @enderror" 
                                   placeholder="Masukkan username"
                                   value="{{ old('username') }}"
                                   required
                                   autofocus>
                        </div>
                        @error('username')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    {{-- PASSWORD INPUT --}}
                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-group">
                            <i class="fas fa-lock input-group-icon"></i>
                            <input type="password" 
                                   name="password" 
                                   id="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   placeholder="Masukkan password"
                                   required>
                        </div>
                        @error('password')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <button type="submit" class="btn-login">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Masuk</span>
                    </button>
                </form>
            </div>
            
            <!-- Footer -->
            <div class="login-footer">
                <div class="footer-links">
                    <a href="{{ url('/') }}" class="back-link">
                        <i class="fas fa-arrow-left"></i>
                        <span>Kembali</span>
                    </a>
                    <a href="{{ route('register_user') }}" class="register-link">
                        <span>Belum punya akun?</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- AdminLTE JS -->
    <script src="{{ asset('public/templates/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('public/templates/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
