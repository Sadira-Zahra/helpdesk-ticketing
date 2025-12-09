@extends('layouts.main')

@section('title', 'Profil Saya')

@push('styles')
<style>
/* Avatar Styles */
.profile-avatar {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    object-fit: cover;
    border: 5px solid #fff;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
}

.avatar-upload {
    position: relative;
    display: inline-block;
}

.avatar-preview {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    overflow: hidden;
    border: 5px solid #fff;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 3em;
    font-weight: bold;
}

.avatar-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.avatar-upload-btn {
    position: absolute;
    bottom: 10px;
    right: 10px;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #007bff;
    border: 3px solid #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.avatar-upload-btn:hover {
    background: #0056b3;
    transform: scale(1.1);
}

.avatar-upload-btn i {
    color: white;
}

/* Info Cards */
.info-card {
    border-radius: 12px;
    transition: all 0.3s ease;
    border: 1px solid #e5e7eb;
}

.info-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.info-row {
    padding: 12px 0;
    border-bottom: 1px solid #f3f4f6;
}

.info-row:last-child {
    border-bottom: none;
}

.info-label {
    font-weight: 600;
    color: #6b7280;
    font-size: 0.9em;
}

.info-value {
    color: #1f2937;
    font-weight: 500;
}

/* Form Styles */
.form-card {
    border-radius: 12px;
}

/* Password Strength */
.password-strength {
    height: 5px;
    border-radius: 3px;
    background: #e5e7eb;
    margin-top: 8px;
    overflow: hidden;
}

.password-strength-bar {
    height: 100%;
    transition: all 0.3s ease;
}

.strength-weak { width: 33%; background: #ef4444; }
.strength-medium { width: 66%; background: #f59e0b; }
.strength-strong { width: 100%; background: #10b981; }
</style>
@endpush

@section('content')
<!-- Content Header -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">
                    <i class="fas fa-user-circle text-primary mr-2"></i> 
                    Profil Saya
                </h1>
                <p class="text-muted mb-0">Kelola informasi profil dan keamanan akun Anda</p>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">
                            <i class="fas fa-home"></i> Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item active">Profil Saya</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<section class="content">
    <div class="container-fluid">

        {{-- Alert Messages --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle mr-2"></i>
                <strong>Berhasil!</strong> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <strong>Gagal!</strong> {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>
        @endif

        @if(session('error_password'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <strong>Gagal!</strong> {{ session('error_password') }}
                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>
        @endif

        <div class="row">
            
            {{-- Left Column: Profile Info --}}
            <div class="col-md-4">
                
                {{-- Profile Card --}}
                <div class="card card-outline card-primary info-card">
                    <div class="card-body text-center py-4">
                        {{-- Avatar --}}
                        <div class="avatar-upload mb-3">
                            <div class="avatar-preview">
                                @if($user->photo)
                                    <img src="{{ asset('public/storage/' . $user->photo) }}" 
             alt="Foto" 
             class="img-circle elevation-2" 
             width="40" 
             height="40" 
             style="object-fit: cover;">
                                @else
                                    {{ strtoupper(substr($user->nama, 0, 1)) }}
                                @endif
                            </div>
                        </div>

                        {{-- User Name --}}
                        <h4 class="mb-1">{{ $user->nama }}</h4>
                        <p class="text-muted mb-2">
                            <i class="fas fa-id-badge mr-1"></i> 
                            {{ ucfirst($user->role) }}
                        </p>

                        {{-- Departemen Badge --}}
                        @if($user->role !== 'administrator' && $user->departemen)
                            <span class="badge badge-primary badge-lg px-3 py-2">
                                <i class="fas fa-building mr-1"></i>
                                {{ $user->departemen->nama_departemen }}
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Contact Info Card --}}
                <div class="card card-outline card-info info-card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-address-card mr-1"></i> 
                            Informasi Kontak
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="info-row">
                            <div class="info-label">
                                <i class="fas fa-id-card text-muted mr-2"></i> NIK
                            </div>
                            <div class="info-value">{{ $user->nik }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">
                                <i class="fas fa-user text-muted mr-2"></i> Username
                            </div>
                            <div class="info-value">{{ $user->username }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">
                                <i class="fas fa-envelope text-muted mr-2"></i> Email
                            </div>
                            <div class="info-value">{{ $user->email }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">
                                <i class="fas fa-phone text-muted mr-2"></i> Telepon
                            </div>
                            <div class="info-value">{{ $user->no_telepon ?? '-' }}</div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Right Column: Edit Forms --}}
            <div class="col-md-8">
                
                {{-- Update Profile Form --}}
                <div class="card card-outline card-primary form-card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-edit mr-2"></i> 
                            Edit Profil
                        </h3>
                    </div>
                    <form action="{{ route('ganti_profil.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="card-body">
                            <div class="row">
                                
                                {{-- Photo Upload --}}
                                <div class="col-12 mb-3">
                                    <label>Foto Profil</label>
                                    <div class="custom-file">
                                        <input type="file" 
                                               name="photo" 
                                               class="custom-file-input @error('photo') is-invalid @enderror" 
                                               id="photoInput"
                                               accept="image/jpeg,image/png,image/jpg">
                                        <label class="custom-file-label" for="photoInput">Pilih foto...</label>
                                    </div>
                                    @error('photo')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle"></i> Format: JPG, PNG. Maksimal 2MB
                                    </small>
                                </div>

                                {{-- NIK --}}
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nik">
                                            NIK <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" 
                                               name="nik" 
                                               id="nik"
                                               class="form-control @error('nik') is-invalid @enderror" 
                                               value="{{ old('nik', $user->nik) }}"
                                               required>
                                        @error('nik')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Username --}}
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="username">
                                            Username <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" 
                                               name="username" 
                                               id="username"
                                               class="form-control @error('username') is-invalid @enderror" 
                                               value="{{ old('username', $user->username) }}"
                                               required>
                                        @error('username')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Nama Lengkap --}}
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="nama">
                                            Nama Lengkap <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" 
                                               name="nama" 
                                               id="nama"
                                               class="form-control @error('nama') is-invalid @enderror" 
                                               value="{{ old('nama', $user->nama) }}"
                                               required>
                                        @error('nama')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Email --}}
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">
                                            Email <span class="text-danger">*</span>
                                        </label>
                                        <input type="email" 
                                               name="email" 
                                               id="email"
                                               class="form-control @error('email') is-invalid @enderror" 
                                               value="{{ old('email', $user->email) }}"
                                               required>
                                        @error('email')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                {{-- No Telepon --}}
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="no_telepon">Nomor Telepon</label>
                                        <input type="text" 
                                               name="no_telepon" 
                                               id="no_telepon"
                                               class="form-control @error('no_telepon') is-invalid @enderror" 
                                               value="{{ old('no_telepon', $user->no_telepon) }}"
                                               placeholder="08xxxxxxxxxx">
                                        @error('no_telepon')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Departemen (non-administrator) --}}
                                @if($user->role !== 'administrator')
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="departemen_id">
                                            Departemen <span class="text-danger">*</span>
                                        </label>
                                        <select name="departemen_id" 
                                                id="departemen_id"
                                                class="form-control @error('departemen_id') is-invalid @enderror"
                                                required>
                                            <option value="">Pilih Departemen</option>
                                            @foreach($departemens as $dept)
                                                <option value="{{ $dept->id }}" 
                                                        {{ old('departemen_id', $user->departemen_id) == $dept->id ? 'selected' : '' }}>
                                                    {{ $dept->nama_departemen }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('departemen_id')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                @endif

                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i> Simpan Perubahan
                            </button>
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                                <i class="fas fa-times mr-1"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>

                {{-- Change Password Form --}}
                <div class="card card-outline card-warning form-card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-key mr-2"></i> 
                            Ganti Password
                        </h3>
                    </div>
                    <form action="{{ route('ganti_profil.update_password') }}" method="POST" id="passwordForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="card-body">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle mr-2"></i>
                                <strong>Keamanan:</strong> Gunakan password yang kuat dengan kombinasi huruf, angka, dan simbol.
                            </div>

                            {{-- Current Password --}}
                            <div class="form-group">
                                <label for="current_password">
                                    Password Lama <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="password" 
                                           name="current_password" 
                                           id="current_password"
                                           class="form-control @error('current_password') is-invalid @enderror"
                                           required>
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" 
                                                type="button" 
                                                onclick="togglePassword('current_password')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    @error('current_password')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- New Password --}}
                            <div class="form-group">
                                <label for="new_password">
                                    Password Baru <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="password" 
                                           name="new_password" 
                                           id="new_password"
                                           class="form-control @error('new_password') is-invalid @enderror"
                                           required
                                           minlength="6">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" 
                                                type="button" 
                                                onclick="togglePassword('new_password')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    @error('new_password')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                <small class="form-text text-muted">Minimal 6 karakter</small>
                                <div class="password-strength">
                                    <div class="password-strength-bar" id="strengthBar"></div>
                                </div>
                                <small id="strengthText" class="form-text"></small>
                            </div>

                            {{-- Confirm Password --}}
                            <div class="form-group">
                                <label for="new_password_confirmation">
                                    Konfirmasi Password Baru <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="password" 
                                           name="new_password_confirmation" 
                                           id="new_password_confirmation"
                                           class="form-control"
                                           required
                                           minlength="6">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" 
                                                type="button" 
                                                onclick="togglePassword('new_password_confirmation')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-lock mr-1"></i> Ubah Password
                            </button>
                        </div>
                    </form>
                </div>

            </div>

        </div>

    </div>
</section>
@endsection

@push('scripts')
<script>
$(function() {
    
    // ========================================
    // Photo Upload Preview
    // ========================================
    
    $('#photoInput').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').html(fileName);
        
        if (this.files && this.files[0]) {
            let reader = new FileReader();
            reader.onload = function(e) {
                $('#avatarImg').attr('src', e.target.result);
                if ($('.avatar-preview').children().is('img')) {
                    $('.avatar-preview img').attr('src', e.target.result);
                } else {
                    $('.avatar-preview').html('<img src="' + e.target.result + '" alt="Avatar">');
                }
            }
            reader.readAsDataURL(this.files[0]);
        }
    });

    // ========================================
    // Toggle Password Visibility
    // ========================================
    
    window.togglePassword = function(fieldId) {
        const field = document.getElementById(fieldId);
        const icon = event.currentTarget.querySelector('i');
        
        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    };

    // ========================================
    // Password Strength Meter
    // ========================================
    
    $('#new_password').on('keyup', function() {
        const password = $(this).val();
        const strengthBar = $('#strengthBar');
        const strengthText = $('#strengthText');
        
        if (password.length === 0) {
            strengthBar.removeClass().addClass('password-strength-bar');
            strengthText.text('');
            return;
        }
        
        let strength = 0;
        
        // Length check
        if (password.length >= 6) strength += 1;
        if (password.length >= 10) strength += 1;
        
        // Character variety
        if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength += 1;
        if (/[0-9]/.test(password)) strength += 1;
        if (/[^a-zA-Z0-9]/.test(password)) strength += 1;
        
        // Update UI
        strengthBar.removeClass();
        strengthBar.addClass('password-strength-bar');
        
        if (strength <= 2) {
            strengthBar.addClass('strength-weak');
            strengthText.html('<span class="text-danger"><i class="fas fa-exclamation-triangle"></i> Password lemah</span>');
        } else if (strength <= 4) {
            strengthBar.addClass('strength-medium');
            strengthText.html('<span class="text-warning"><i class="fas fa-exclamation-circle"></i> Password sedang</span>');
        } else {
            strengthBar.addClass('strength-strong');
            strengthText.html('<span class="text-success"><i class="fas fa-check-circle"></i> Password kuat</span>');
        }
    });

    // ========================================
    // Form Validation
    // ========================================
    
    $('#passwordForm').on('submit', function(e) {
        const newPassword = $('#new_password').val();
        const confirmPassword = $('#new_password_confirmation').val();
        
        if (newPassword !== confirmPassword) {
            e.preventDefault();
            alert('Konfirmasi password tidak cocok!');
            $('#new_password_confirmation').focus();
            return false;
        }
    });

    // ========================================
    // Auto-hide Alerts
    // ========================================
    
    setTimeout(function() {
        $('.alert').fadeOut('slow', function() {
            $(this).remove();
        });
    }, 5000);

});
</script>
@endpush
