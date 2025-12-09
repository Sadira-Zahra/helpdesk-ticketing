@extends('layouts.main')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Master Urgency</h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Urgency Level</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalTambah">
                        <i class="fas fa-plus"></i> Tambah Urgency
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

                <table id="tableUrgency" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Level Urgency</th>
                            <th width="15%">Durasi (Jam)</th>
                          <!--  <th width="15%">Durasi (Hari)</th>
                            <th width="12%">Jumlah Tiket</th> -->
                            <th width="12%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($urgencies as $index => $urg)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <strong>{{ $urg->urgency }}</strong>
                                @if($urg->jam <= 24)
                                    <span class="badge badge-danger ml-2">Critical</span>
                                @elseif($urg->jam <= 72)
                                    <span class="badge badge-warning ml-2">High</span>
                                @elseif($urg->jam <= 168)
                                    <span class="badge badge-info ml-2">Medium</span>
                                @else
                                    <span class="badge badge-secondary ml-2">Low</span>
                                @endif
                            </td>
                            <td class="text-center"><span class="badge badge-primary">{{ $urg->jam }} jam</span></td>
                          <!--  <td class="text-center"><span class="badge badge-info">{{ number_format($urg->jam / 24, 1) }} hari</span></td>
                            <td class="text-center">
                                <span class="badge badge-success">{{ $urg->tiket_count }}</span>
                            </td> -->
                            <td>
                                <button type="button" class="btn btn-warning btn-sm" 
                                    onclick="editData({{ $urg->id }}, '{{ addslashes($urg->urgency) }}', {{ $urg->jam }})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-danger btn-sm" 
                                    onclick="deleteData({{ $urg->id }}, '{{ addslashes($urg->urgency) }}')">
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
            <form action="{{ route('urgency.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Urgency</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Level Urgency <span class="text-danger">*</span></label>
                        <input type="text" 
                               name="urgency" 
                               class="form-control @error('urgency') is-invalid @enderror" 
                               placeholder="Contoh: Low, Medium, High, Critical"
                               value="{{ old('urgency') }}" 
                               required>
                        @error('urgency')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label>Durasi Penyelesaian (Jam) <span class="text-danger">*</span></label>
                        <input type="number" 
                               name="jam" 
                               class="form-control @error('jam') is-invalid @enderror" 
                               placeholder="Contoh: 24, 48, 72, 168"
                               value="{{ old('jam') }}" 
                               min="1"
                               max="720"
                               required>
                        @error('jam')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle"></i> 
                            Contoh: 24 jam = 1 hari, 168 jam = 7 hari, 720 jam = 30 hari
                        </small>
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
                    <h5 class="modal-title">Edit Urgency</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Level Urgency <span class="text-danger">*</span></label>
                        <input type="text" 
                               name="urgency" 
                               id="edit_urgency" 
                               class="form-control" 
                               required>
                    </div>
                    
                    <div class="form-group">
                        <label>Durasi Penyelesaian (Jam) <span class="text-danger">*</span></label>
                        <input type="number" 
                               name="jam" 
                               id="edit_jam" 
                               class="form-control" 
                               min="1"
                               max="720"
                               required>
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle"></i> 
                            Contoh: 24 jam = 1 hari, 168 jam = 7 hari
                        </small>
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
    $('#tableUrgency').DataTable({
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

function editData(id, urgency, jam) {
    let url = "{{ route('urgency.update', ':id') }}";
    url = url.replace(':id', id);
    
    $('#formEdit').attr('action', url);
    $('#edit_urgency').val(urgency);
    $('#edit_jam').val(jam);
    $('#modalEdit').modal('show');
}

function deleteData(id, urgency) {
    if (confirm('Apakah Anda yakin ingin menghapus urgency "' + urgency + '"?\n\nData yang sudah dihapus tidak dapat dikembalikan.')) {
        let url = "{{ route('urgency.destroy', ':id') }}";
        url = url.replace(':id', id);
        
        $('#formDelete').attr('action', url);
        $('#formDelete').submit();
    }
}
</script>
@endpush
