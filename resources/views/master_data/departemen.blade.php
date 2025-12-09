@extends('layouts.main')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Master Departemen</h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Departemen</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalTambah">
                        <i class="fas fa-plus"></i> Tambah Departemen
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

                <table id="tableDepartemen" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Nama Departemen</th>
                            <th width="10%">Jumlah User</th>
                            <th width="10%">Jumlah Tiket</th>
                            <th width="12%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($departemens as $index => $dept)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><strong>{{ $dept->nama_departemen }}</strong></td>
                            <td class="text-center">
                                <span class="badge badge-info">{{ $dept->users_count }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-primary">{{ $dept->tiket_count }}</span>
                            </td>
                            <td>
                                <button type="button" class="btn btn-warning btn-sm" 
                                    onclick="editData({{ $dept->id }}, '{{ addslashes($dept->nama_departemen) }}')">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-danger btn-sm" 
                                    onclick="deleteData({{ $dept->id }}, '{{ addslashes($dept->nama_departemen) }}')">
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
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('departemen.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Departemen</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Departemen <span class="text-danger">*</span></label>
                        <input type="text" 
                               name="nama_departemen" 
                               class="form-control @error('nama_departemen') is-invalid @enderror" 
                               placeholder="Contoh: IT, GA, EHS, PUR, FA"
                               value="{{ old('nama_departemen') }}" 
                               required>
                        @error('nama_departemen')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
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
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="formEdit" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Departemen</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Departemen <span class="text-danger">*</span></label>
                        <input type="text" 
                               name="nama_departemen" 
                               id="edit_nama_departemen" 
                               class="form-control" 
                               required>
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
    $('#tableDepartemen').DataTable({
        "responsive": true,
        "autoWidth": false,
        "language": {
            "search": "Cari:",
            "lengthMenu": "Tampilkan _MENU_ data",
            "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            "infoEmpty": "Tidak ada data",
            "zeroRecords": "Data tidak ditemukan",
            "paginate": {
                "first": "Pertama",
                "last": "Terakhir",
                "next": "Selanjutnya",
                "previous": "Sebelumnya"
            }
        }
    });
});

function editData(id, nama_departemen) {
    let url = "{{ route('departemen.update', ':id') }}";
    url = url.replace(':id', id);
    
    $('#formEdit').attr('action', url);
    $('#edit_nama_departemen').val(nama_departemen);
    $('#modalEdit').modal('show');
}

function deleteData(id, nama_departemen) {
    if (confirm('Apakah Anda yakin ingin menghapus departemen "' + nama_departemen + '"?\n\nData yang sudah dihapus tidak dapat dikembalikan.')) {
        let url = "{{ route('departemen.destroy', ':id') }}";
        url = url.replace(':id', id);
        
        $('#formDelete').attr('action', url);
        $('#formDelete').submit();
    }
}
</script>
@endpush
