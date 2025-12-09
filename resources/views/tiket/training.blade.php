@extends('layouts.main')

@section('title', 'Training Model C4.5')

@push('styles')
<style>
.upload-area {
    border: 2px dashed #007bff;
    border-radius: 10px;
    padding: 40px;
    text-align: center;
    background: #f8f9fa;
    transition: all 0.3s;
}

.upload-area:hover {
    background: #e9ecef;
    border-color: #0056b3;
}

.upload-area.dragover {
    background: #cfe2ff;
    border-color: #0056b3;
}

.model-status {
    padding: 20px;
    border-radius: 10px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.data-preview {
    max-height: 400px;
    overflow-y: auto;
}

.accuracy-badge {
    font-size: 2em;
    padding: 10px 20px;
}
</style>
@endpush

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Training Model C4.5</h1>
                <small class="text-muted">Upload data dan latih model prediksi urgency</small>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('tiket.analytics') }}">Analytics</a></li>
                    <li class="breadcrumb-item active">Training</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">

        {{-- Alert --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif

        {{-- Status Model --}}
        <div class="row mb-4">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body model-status">
                        <div class="row align-items-center">
                            <div class="col-md-3 text-center">
                                <i class="fas fa-brain fa-4x mb-2"></i>
                                <h5>Model C4.5</h5>
                            </div>
                            <div class="col-md-9">
                                @if($model)
                                    <div class="row">
                                        <div class="col-md-4 text-center">
                                            <h3 class="mb-0">{{ number_format($model->accuracy, 1) }}%</h3>
                                            <small>Akurasi</small>
                                        </div>
                                        <div class="col-md-4 text-center">
                                            <h3 class="mb-0">{{ $model->data_count }}</h3>
                                            <small>Data Training</small>
                                        </div>
                                        <div class="col-md-4 text-center">
                                            <h3 class="mb-0">{{ \Carbon\Carbon::parse($model->updated_at)->diffForHumans() }}</h3>
                                            <small>Last Training</small>
                                        </div>
                                    </div>
                                @else
                                    <div class="text-center">
                                        <h4><i class="fas fa-exclamation-triangle"></i> Model Belum Di-Train</h4>
                                        <p class="mb-0">Upload data training dan klik tombol "Train Model"</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-gradient-info">
                    <div class="card-body text-center">
                        <i class="fas fa-database fa-3x mb-3"></i>
                        <h3>{{ $trainingCount }}</h3>
                        <p class="mb-0">Total Data Training</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Upload & Train Section --}}
        <div class="row">
            <div class="col-md-6">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-upload"></i> Upload Data Training</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('tiket.training.import') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                            @csrf
                            <div class="upload-area" id="uploadArea">
                                <i class="fas fa-cloud-upload-alt fa-3x text-primary mb-3"></i>
                                <h5>Drag & Drop file Excel di sini</h5>
                                <p class="text-muted mb-3">atau</p>
                                <input type="file" name="file" id="fileInput" accept=".xlsx,.xls,.csv" style="display:none;" required>
                                <button type="button" class="btn btn-primary" onclick="$('#fileInput').click()">
                                    <i class="fas fa-folder-open"></i> Pilih File
                                </button>
                                <p class="text-muted mt-3 mb-0">
                                    <small>Format: Excel (.xlsx, .xls) atau CSV. Maksimal 2MB</small>
                                </p>
                            </div>
                            <div id="fileInfo" class="mt-3" style="display:none;">
                                <div class="alert alert-info">
                                    <i class="fas fa-file-excel"></i> 
                                    <strong>File:</strong> <span id="fileName"></span>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success btn-block mt-3">
                                <i class="fas fa-upload"></i> Upload Data
                            </button>
                        </form>
                        
                        <hr>
                        
                        <a href="{{ route('tiket.training.template') }}" class="btn btn-outline-primary btn-block">
                            <i class="fas fa-download"></i> Download Template Excel
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card card-success card-outline">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-cogs"></i> Train Model</h3>
                    </div>
                    <div class="card-body text-center">
                        <i class="fas fa-brain fa-5x text-success mb-4"></i>
                        <h4>Training Model C4.5</h4>
                        <p class="text-muted">
                            Proses training akan membangun decision tree berdasarkan data yang sudah diupload.
                            Model akan digunakan untuk memprediksi urgency tiket baru.
                        </p>
                        
                        <div class="alert alert-warning">
                            <i class="fas fa-info-circle"></i> 
                            <strong>Catatan:</strong> Pastikan data training minimal 10 record untuk hasil yang optimal.
                        </div>

                        <form action="{{ route('tiket.training.train') }}" method="POST" 
                              onsubmit="return confirm('Proses training akan memakan waktu. Lanjutkan?')">
                            @csrf
                            <button type="submit" class="btn btn-success btn-lg btn-block" 
                                    {{ $trainingCount < 10 ? 'disabled' : '' }}>
                                <i class="fas fa-play-circle"></i> Train Model Sekarang
                            </button>
                        </form>

                        @if($trainingCount < 10)
                            <small class="text-danger">
                                <i class="fas fa-exclamation-triangle"></i> 
                                Data training kurang dari 10. Upload data terlebih dahulu.
                            </small>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Preview Data Training --}}
        <div class="row">
            <div class="col-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-table"></i> Preview Data Training</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-sm btn-danger" 
                                    onclick="if(confirm('Hapus semua data training?')) { document.getElementById('deleteAllForm').submit(); }">
                                <i class="fas fa-trash"></i> Hapus Semua
                            </button>
                        </div>
                    </div>
                    <div class="card-body table-responsive p-0 data-preview">
                        <table class="table table-hover table-striped text-nowrap">
                            <thead class="thead-light sticky-top">
    <tr>
        <th>No</th>
        <th>Tanggal</th>
        <th>Deskripsi Problem</th>
        <th>Kategori</th>
        <th>SLA (Hrs)</th>
        <th>Actual (Hrs)</th>
        <th>Urgency</th>
    </tr>
</thead>
<tbody>
    @php
        $trainings = DB::table('tiket_training')->orderBy('created_at', 'desc')->limit(50)->get();
    @endphp
    @forelse($trainings as $index => $data)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td><small>{{ $data->tanggal_problem }}</small></td>
            <td>{{ \Illuminate\Support\Str::limit($data->deskripsi_problem, 50) }}</td>
            <td><span class="badge badge-secondary">{{ $data->kategori }}</span></td>
            <td class="text-center">{{ $data->sla_target_hrs }}</td>
            <td class="text-center">{{ $data->actual_hrs }}</td>
            <td>
                @php
                    $badgeClass = [
                        'Low' => 'badge-info',
                        'Medium' => 'badge-warning',
                        'High' => 'badge-danger',
                        'Critical' => 'badge-dark'
                    ][$data->urgency_level] ?? 'badge-secondary';
                @endphp
                <span class="badge {{ $badgeClass }}">{{ $data->urgency_level }}</span>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="7" class="text-center py-4 text-muted">
                <i class="fas fa-inbox fa-3x mb-2 d-block"></i>
                Belum ada data training. Upload file Excel terlebih dahulu.
            </td>
        </tr>
    @endforelse
</tbody>

                        </table>
                    </div>
                    @if($trainingCount > 50)
                        <div class="card-footer">
                            <small class="text-muted">
                                Menampilkan 50 data terakhir dari {{ $trainingCount }} total data.
                            </small>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Lihat Hasil --}}
        @if($model)
        <div class="row">
            <div class="col-md-6">
                <a href="{{ route('tiket.training.tree') }}" class="btn btn-primary btn-lg btn-block">
                    <i class="fas fa-sitemap"></i> Lihat Pohon Keputusan
                </a>
            </div>
            <div class="col-md-6">
                <a href="{{ route('tiket.training.rules') }}" class="btn btn-info btn-lg btn-block">
                    <i class="fas fa-list-ol"></i> Lihat Pola & Rules
                </a>
            </div>
        </div>
        @endif

    </div>
</section>

{{-- Form Delete All --}}
<form id="deleteAllForm" action="{{ route('tiket.training.deleteAll') }}" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
$(function() {
    // File input handler
    $('#fileInput').on('change', function() {
        const fileName = $(this).val().split('\\').pop();
        if (fileName) {
            $('#fileName').text(fileName);
            $('#fileInfo').show();
        }
    });

    // Drag and drop
    const uploadArea = document.getElementById('uploadArea');
    
    ['dragenter', 'dragover'].forEach(eventName => {
        uploadArea.addEventListener(eventName, (e) => {
            e.preventDefault();
            uploadArea.classList.add('dragover');
        });
    });

    ['dragleave', 'drop'].forEach(eventName => {
        uploadArea.addEventListener(eventName, (e) => {
            e.preventDefault();
            uploadArea.classList.remove('dragover');
        });
    });

    uploadArea.addEventListener('drop', (e) => {
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            document.getElementById('fileInput').files = files;
            $('#fileName').text(files[0].name);
            $('#fileInfo').show();
        }
    });
});
</script>
@endpush
