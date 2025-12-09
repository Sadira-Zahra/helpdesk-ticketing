@extends('layouts.main')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Master Teknisi</h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Teknisi (Departemen: IT, GA, EHS)</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalTambah">
                        <i class="fas fa-plus"></i> Tambah Teknisi
                    </button>
                </div>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <table id="tableTeknisi" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="8%">Foto</th>
                            <th>NIK</th>
                            <th>Username</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>No Telp</th>
                            <th>Departemen</th>
                            <th width="12%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $index => $user)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                           <td class="text-center">
    @if($user->photo)
        <img src="{{ asset('public/storage/' . $user->photo) }}" 
             alt="Foto" 
             class="img-circle elevation-2" 
             width="40" 
             height="40" 
             style="object-fit: cover;">
    @else
        <img src="https://via.placeholder.com/40/667eea/ffffff?text={{ strtoupper(substr($user->nama ?? 'U', 0, 1)) }}" 
             alt="Foto" 
             class="img-circle" 
             width="40" 
             height="40">
    @endif
</td>

                            <td>{{ $user->nik }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->nama }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->no_telp ?? '-' }}</td>
                            <td>{{ $user->departemen->nama_departemen ?? '-' }}</td>
                            <td>
                                <button type="button" class="btn btn-warning btn-sm" 
                                    onclick="editData({{ $user->id }}, '{{ $user->nik }}', '{{ $user->username }}', '{{ $user->nama }}', '{{ $user->email }}', '{{ $user->no_telp }}', {{ $user->departemen_id }})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-danger btn-sm" onclick="deleteData({{ $user->id }}, '{{ $user->nama }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="{{ route('master_user.teknisi.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Teknisi</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>NIK <span class="text-danger">*</span></label>
                                <input type="text" name="nik" class="form-control @error('nik') is-invalid @enderror" 
                                    value="{{ old('nik') }}" required>
                                @error('nik')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Username <span class="text-danger">*</span></label>
                                <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" 
                                    value="{{ old('username') }}" required>
                                @error('username')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" 
                            value="{{ old('nama') }}" required>
                        @error('nama')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                    value="{{ old('email') }}" required>
                                @error('email')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Password <span class="text-danger">*</span></label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                                @error('password')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>No Telp</label>
                                <input type="text" name="no_telp" class="form-control @error('no_telp') is-invalid @enderror" 
                                    value="{{ old('no_telp') }}">
                                @error('no_telp')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Departemen <span class="text-danger">*</span></label>
                                <select name="departemen_id" class="form-control @error('departemen_id') is-invalid @enderror" required>
                                    <option value="">Pilih Departemen</option>
                                    @foreach($departemens as $dept)
                                        <option value="{{ $dept->id }}" {{ old('departemen_id') == $dept->id ? 'selected' : '' }}>
                                            {{ $dept->nama_departemen }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('departemen_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Foto Profil</label>
                        <div class="custom-file">
                            <input type="file" name="photo" class="custom-file-input @error('photo') is-invalid @enderror" 
                                id="photoTambah" accept="image/*" onchange="previewImage(this, 'previewTambah')">
                            <label class="custom-file-label" for="photoTambah">Pilih file...</label>
                        </div>
                        @error('photo')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                        <div class="mt-2">
                            <img id="previewTambah" src="" alt="" style="max-width: 150px; display: none;" class="img-thumbnail">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="modalEdit" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="formEdit" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Teknisi</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>NIK <span class="text-danger">*</span></label>
                                <input type="text" name="nik" id="edit_nik" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Username <span class="text-danger">*</span></label>
                                <input type="text" name="username" id="edit_username" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="nama" id="edit_nama" class="form-control" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" id="edit_email" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Password <small class="text-muted">(Kosongkan jika tidak diubah)</small></label>
                                <input type="password" name="password" class="form-control">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>No Telp</label>
                                <input type="text" name="no_telp" id="edit_no_telp" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Departemen <span class="text-danger">*</span></label>
                                <select name="departemen_id" id="edit_departemen_id" class="form-control" required>
                                    <option value="">Pilih Departemen</option>
                                    @foreach($departemens as $dept)
                                        <option value="{{ $dept->id }}">{{ $dept->nama_departemen }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Foto Profil</label>
                        <div class="custom-file">
                            <input type="file" name="photo" class="custom-file-input" 
                                id="photoEdit" accept="image/*" onchange="previewImage(this, 'previewEdit')">
                            <label class="custom-file-label" for="photoEdit">Pilih file...</label>
                        </div>
                        <div class="mt-2">
                            <img id="previewEdit" src="" alt="" style="max-width: 150px; display: none;" class="img-thumbnail">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Form Delete -->
<form id="formDelete" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#tableTeknisi').DataTable({
        "responsive": true,
        "autoWidth": false,
    });
    
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').html(fileName);
    });
});

function previewImage(input, previewId) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#' + previewId).attr('src', e.target.result).show();
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function editData(id, nik, username, nama, email, no_telp, departemen_id) {
    let url = "{{ route('master_user.teknisi.update', ':id') }}";
    url = url.replace(':id', id);
    
    $('#formEdit').attr('action', url);
    $('#edit_nik').val(nik);
    $('#edit_username').val(username);
    $('#edit_nama').val(nama);
    $('#edit_email').val(email);
    $('#edit_no_telp').val(no_telp);
    $('#edit_departemen_id').val(departemen_id);
    $('#modalEdit').modal('show');
}

function deleteData(id, nama) {
    if (confirm('Apakah Anda yakin ingin menghapus teknisi ' + nama + '?')) {
        let url = "{{ route('master_user.teknisi.destroy', ':id') }}";
        url = url.replace(':id', id);
        
        $('#formDelete').attr('action', url);
        $('#formDelete').submit();
    }
}
</script>
@endpush

