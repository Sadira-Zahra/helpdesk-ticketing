<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar User - Helpdesk System</title>
    
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
            padding: 30px 20px;
        }
        
        .register-container {
            width: 100%;
            max-width: 550px;
        }
        
        .register-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .register-header {
            background: linear-gradient(135deg, #10b981, #059669);
            padding: 35px 30px;
            text-align: center;
            color: white;
        }
        
        .logo-container {
            width: 90px;
            height: 90px;
            background: white;
            border-radius: 15px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 18px;
            padding: 10px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }
        
        .logo-container img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        
        .register-header h1 {
            font-size: 1.75em;
            font-weight: 700;
            margin: 0 0 8px 0;
        }
        
        .register-header p {
            margin: 0;
            opacity: 0.95;
            font-size: 0.92em;
        }
        
        .register-body {
            padding: 35px 35px;
        }
        
        .badge-register {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            padding: 8px 18px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.9em;
            margin-bottom: 25px;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }
        
        .form-row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #334155;
            font-weight: 600;
            font-size: 0.9em;
        }
        
        .form-group label .text-danger {
            color: #ef4444;
            margin-left: 3px;
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
            font-size: 1em;
            z-index: 1;
        }
        
        .form-control, .form-select {
            width: 100%;
            padding: 12px 15px 12px 42px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 0.95em;
            transition: all 0.3s ease;
            background: #f8fafc;
        }
        
        .form-control:focus, .form-select:focus {
            outline: none;
            border-color: #10b981;
            background: white;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
        }
        
        .form-control.is-invalid, .form-select.is-invalid {
            border-color: #ef4444;
            background: #fef2f2;
        }
        
        .invalid-feedback {
            color: #ef4444;
            font-size: 0.8em;
            margin-top: 5px;
            display: block;
        }
        
        .btn-register {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1.05em;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 25px;
        }
        
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
        }
        
        .btn-register:active {
            transform: translateY(0);
        }
        
        .register-footer {
            padding: 22px 35px;
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
        
        .back-link, .login-link {
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
        
        .login-link {
            color: #06b6d4;
        }
        
        .login-link:hover {
            color: #0891b2;
            gap: 12px;
        }
        
        .alert {
            padding: 12px 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }
        
        .alert-danger {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }
        
        .alert-danger ul {
            margin: 0;
            padding-left: 20px;
        }
        
        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }
        }
        
        @media (max-width: 576px) {
            body {
                padding: 20px 15px;
            }
            
            .register-header {
                padding: 25px 20px;
            }
            
            .register-header h1 {
                font-size: 1.4em;
            }
            
            .register-body {
                padding: 25px 20px;
            }
            
            .register-footer {
                padding: 18px 20px;
            }
            
            .footer-links {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .form-control, .form-select {
                font-size: 0.9em;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-card">
            <!-- Header -->
            <div class="register-header">
                <div class="logo-container">
                    <img src="{{ asset('public/templates/dist/img/LogoIsuzu.png') }}" alt="Logo" onerror="this.style.display='none'">
                </div>
                <h1>Daftar User Baru</h1>
                <p>Helpdesk System PT. Mesin Isuzu Indonesia</p>
            </div>
            
            <!-- Body -->
            <div class="register-body">
                <div class="badge-register">
                    <i class="fas fa-user-plus"></i>
                    <span>Form Pendaftaran</span>
                </div>
                
                @if($errors->any())
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i>
                        <div>
                            <strong>Terdapat kesalahan:</strong>
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
                
                <form action="{{ route('register_user.post') }}" method="POST">
                    @csrf
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="nik">NIK <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <i class="fas fa-id-card input-group-icon"></i>
                                <input type="text" 
                                       name="nik" 
                                       id="nik" 
                                       class="form-control @error('nik') is-invalid @enderror" 
                                       placeholder="NIK"
                                       value="{{ old('nik') }}"
                                       required>
                            </div>
                            @error('nik')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="username">Username <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <i class="fas fa-user input-group-icon"></i>
                                <input type="text" 
                                       name="username" 
                                       id="username" 
                                       class="form-control @error('username') is-invalid @enderror" 
                                       placeholder="Username"
                                       value="{{ old('username') }}"
                                       required>
                            </div>
                            @error('username')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="nama">Nama Lengkap <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <i class="fas fa-signature input-group-icon"></i>
                            <input type="text" 
                                   name="nama" 
                                   id="nama" 
                                   class="form-control @error('nama') is-invalid @enderror" 
                                   placeholder="Nama lengkap"
                                   value="{{ old('nama') }}"
                                   required>
                        </div>
                        @error('nama')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <i class="fas fa-envelope input-group-icon"></i>
                            <input type="email" 
                                   name="email" 
                                   id="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   placeholder="email@example.com"
                                   value="{{ old('email') }}"
                                   required>
                        </div>
                        @error('email')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="password">Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <i class="fas fa-lock input-group-icon"></i>
                                <input type="password" 
                                       name="password" 
                                       id="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       placeholder="Min. 6 karakter"
                                       required>
                            </div>
                            @error('password')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="password_confirmation">Konfirmasi Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <i class="fas fa-lock input-group-icon"></i>
                                <input type="password" 
                                       name="password_confirmation" 
                                       id="password_confirmation" 
                                       class="form-control" 
                                       placeholder="Ulangi password"
                                       required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="no_telp">No. Telepon</label>
                            <div class="input-group">
                                <i class="fas fa-phone input-group-icon"></i>
                                <input type="text" 
                                       name="no_telp" 
                                       id="no_telp" 
                                       class="form-control @error('no_telp') is-invalid @enderror" 
                                       placeholder="08xxxxxxxxxx"
                                       value="{{ old('no_telp') }}">
                            </div>
                            @error('no_telp')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="departemen_id">Departemen <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <i class="fas fa-building input-group-icon"></i>
                                <select name="departemen_id" 
                                        id="departemen_id" 
                                        class="form-select @error('departemen_id') is-invalid @enderror" 
                                        required>
                                    <option value="">Pilih Departemen</option>
                                    @foreach($departemens as $dept)
                                        <option value="{{ $dept->id }}" {{ old('departemen_id') == $dept->id ? 'selected' : '' }}>
                                            {{ $dept->nama_departemen }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('departemen_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    
                    <button type="submit" class="btn-register">
                        <i class="fas fa-user-plus"></i>
                        <span>Daftar Sekarang</span>
                    </button>
                </form>
            </div>
            
            <!-- Footer -->
            <div class="register-footer">
                <div class="footer-links">
                    <a href="{{ url('/') }}" class="back-link">
                        <i class="fas fa-arrow-left"></i>
                        <span>Kembali</span>
                    </a>
                    <a href="{{ route('login_user') }}" class="login-link">
                        <span>Sudah punya akun?</span>
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
