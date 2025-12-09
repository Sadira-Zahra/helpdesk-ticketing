    @extends('layouts.main')

    @section('title', 'Daftar Tiket - Helpdesk')

    @push('styles')
    <style>
    /* Timeline Styles - FIXED */
    .timeline {
        position: relative;
        padding-left: 0;
        list-style: none;
        margin: 0;
    }

    .timeline-sm .timeline-item {
        margin-bottom: 18px;
        position: relative;
        padding-left: 40px;
        clear: both;
        min-height: 35px;
    }

    .timeline-sm .timeline-item:last-child {
        margin-bottom: 0;
    }

    .timeline-sm .timeline-item i {
        position: absolute;
        left: 0;
        top: 0;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        text-align: center;
        line-height: 30px;
        font-size: 13px;
        color: white;
        z-index: 2;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }



    .timeline-content {
        padding: 5px 0;
        position: relative;
        z-index: 3;
        background: transparent;
    }

    .timeline-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 6px;
        flex-wrap: wrap;
        gap: 5px;
    }

    .timeline-header strong {
        font-size: 0.9em;
        color: #495057;
        font-weight: 600;
    }

    .timeline-header .time {
        font-size: 0.75em;
        color: #6c757d;
        white-space: nowrap;
    }

    .timeline-body {
        font-size: 0.85em;
        color: #6c757d;
        line-height: 1.5;
        margin-top: 4px;
    }

    /* Card untuk timeline wrapper */
    .card-body .timeline {
        padding: 0;
    }

    .card-info .card-body {
        overflow: visible !important;
    }

    /* Alert Deadline */
    .alert {
        position: relative;
        z-index: 1;
    }

    .alert i {
        margin-right: 5px;
    }

    /* Progress bar untuk confidence */
    .progress {
        background-color: #e9ecef;
        position: relative;
        z-index: 1;
    }

    .progress-bar strong {
        font-size: 0.85em;
    }

    /* Badge sizing */
    .badge-sm {
        font-size: 0.7em;
        padding: 3px 6px;
    }

    /* Badge terlambat */
    .badge-overdue {
        background-color: #dc3545;
        color: white;
        font-weight: bold;
        animation: pulse 1.5s infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }

    /* Hover effect untuk row tabel */
    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }

    /* Modal max height */
    .modal-body-scroll {
        max-height: 70vh;
        overflow-y: auto;
    }

    /* Card consistency */
    .card {
        position: relative;
    }

    .card-header h6 {
        margin: 0;
        font-size: 0.95rem;
    }

    .card-body {
        position: relative;
    }

    /* Fix z-index layers */
    .card-outline {
        z-index: 1;
    }

    .timeline-item {
        z-index: 10 !important;
    }

    /* Icon background colors */
    .bg-primary {
        background-color: #007bff !important;
    }

    .bg-info {
        background-color: #17a2b8 !important;
    }

    .bg-success {
        background-color: #28a745 !important;
    }

    .bg-danger {
        background-color: #dc3545 !important;
    }

    .bg-dark {
        background-color: #343a40 !important;
    }

    /* Row highlight untuk tiket terlambat */
    tr.row-overdue {
        background-color: #fff5f5 !important;
    }

    tr.row-overdue:hover {
        background-color: #ffe6e6 !important;
    }

    /* TIMELINE TANPA GARIS – 100% BEBAS ADMINLTE */
    .timeline-custom {
        list-style: none;
        padding: 0;
        margin: 0;
        position: relative;
    }

    .timeline-custom .timeline-item {
        margin-bottom: 18px;
        position: relative;
        padding-left: 40px;
        min-height: 35px;
    }

    .timeline-custom .timeline-item i {
        position: absolute;
        left: 0;
        top: 0;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        text-align: center;
        line-height: 30px;
        font-size: 13px;
        color: white;
        z-index:  z-index: 2;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }

    /* GARIS DIHAPUS TOTAL */
    .timeline-custom::before,
    .timeline-custom .timeline-item::before,
    .timeline-custom .timeline-item::after {
        display: none !important;
    }

    /* Responsive timeline */
    @media (max-width: 768px) {
        .timeline-sm .timeline-item {
            padding-left: 35px;
        }
        
        .timeline-sm .timeline-item i {
            width: 26px;
            height: 26px;
            line-height: 26px;
            font-size: 11px;
        }
        
        
        
        .timeline-header {
            flex-direction: column;
            gap: 2px;
        }
    }
    </style>
    @endpush

    @section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-6">
                    <h1 class="m-0">Daftar Tiket</h1>
                    <small class="text-muted">
                        @if($role === 'user')
                            Portal tiket untuk user
                        @elseif($role === 'admin')
                            Panel admin untuk verifikasi, assign, dan close tiket
                        @elseif($role === 'teknisi')
                            Daftar tiket yang di-assign ke Anda
                        @else
                            Panel administrator
                        @endif
                    </small>
                </div>
                <div class="col-sm-6 text-right">
                    @if($role === 'user')
                        <button class="btn btn-primary" data-toggle="modal" data-target="#modalCreate">
                            <i class="fas fa-plus-circle"></i> Buat Tiket
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">

            {{-- Status cards --}}
            <div class="row mb-3">
                <div class="col-lg-3 col-md-6 col-sm-6 mb-2">
                    <a href="{{ route('tiket.index', ['status' => 'open']) }}" class="text-decoration-none">
                        <div class="small-box bg-gradient-secondary">
                            <div class="inner">
                                <h3>{{ $statusCounts['open'] ?? 0 }}</h3>
                                <p>Open</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-envelope-open-text"></i>
                            </div>
                            <div class="small-box-footer">
                                Tiket baru <i class="fas fa-arrow-circle-right"></i>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 mb-2">
                    <a href="{{ route('tiket.index', ['status' => 'pending']) }}" class="text-decoration-none">
                        <div class="small-box bg-gradient-warning">
                            <div class="inner">
                                <h3>{{ $statusCounts['pending'] ?? 0 }}</h3>
                                <p>Pending</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-user-clock"></i>
                            </div>
                            <div class="small-box-footer">
                                Menunggu teknisi <i class="fas fa-arrow-circle-right"></i>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 mb-2">
                    <a href="{{ route('tiket.index', ['status' => 'progress']) }}" class="text-decoration-none">
                        <div class="small-box bg-gradient-primary">
                            <div class="inner">
                                <h3>{{ $statusCounts['progress'] ?? 0 }}</h3>
                                <p>Progress</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-spinner"></i>
                            </div>
                            <div class="small-box-footer">
                                Sedang dikerjakan <i class="fas fa-arrow-circle-right"></i>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 mb-2">
                    <a href="{{ route('tiket.index', ['status' => 'finish']) }}" class="text-decoration-none">
                        <div class="small-box bg-gradient-success">
                            <div class="inner">
                                <h3>{{ $statusCounts['finish'] ?? 0 }}</h3>
                                <p>Finish</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="small-box-footer">
                                Menunggu verifikasi <i class="fas fa-arrow-circle-right"></i>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

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

            {{-- Filter + Search --}}
            <div class="card card-outline card-primary mb-3">
                <div class="card-body p-3">
                    <form method="GET" action="{{ route('tiket.index') }}" class="form-inline">
                        <div class="form-group mr-3 mb-2">
                            <label for="status" class="mr-2"><i class="fas fa-filter"></i> Status:</label>
                            <select name="status" id="status" class="form-control form-control-sm">
                                <option value="">Semua</option>
                                @foreach(['open','pending','progress','finish'] as $st)
                                    <option value="{{ $st }}" {{ request('status') === $st ? 'selected' : '' }}>
                                        {{ ucfirst($st) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mr-3 mb-2">
                            <label for="search" class="mr-2"><i class="fas fa-search"></i> Cari:</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}"
                                class="form-control form-control-sm" placeholder="Nomor / judul / keterangan" style="min-width: 250px;">
                        </div>
                        <button type="submit" class="btn btn-sm btn-primary mb-2 mr-2">
                            <i class="fas fa-search"></i> Filter
                        </button>
                        @if(request()->has('status') || request()->has('search'))
                            <a href="{{ route('tiket.index') }}" class="btn btn-sm btn-outline-secondary mb-2">
                                <i class="fas fa-redo"></i> Reset
                            </a>
                        @endif
                    </form>
                </div>
            </div>

            {{-- Tabel tiket --}}
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-list"></i> Daftar Tiket</h3>
                    <div class="card-tools">
                        <span class="badge badge-primary">{{ $tikets->total() }} tiket</span>
                    </div>
                </div>
                <div class="card-body table-responsive p-0" style="max-height: 600px;">
                    <table class="table table-hover table-head-fixed text-nowrap">
                        <thead class="thead-light">
                            <tr>
                                <th style="width: 40px;">No</th>
                                <th style="width: 140px;">Nomor Tiket</th>
                                <th style="width: 150px;">Tanggal / Deadline</th>
                                <th>Judul & Deskripsi</th>
                                
                                <th style="width: 120px;">Urgency</th>
                                <th style="width: 100px;">Dept</th>
                                <th style="width: 120px;">Teknisi</th>
                                <th style="width: 80px;">Status</th>
                                <th style="width: 100px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tikets as $tiket)
                                @php
    $isOverdue = false;
    if ($tiket->tanggal_selesai && !in_array($tiket->status, ['closed', 'finish'])) {
        // Cek apakah SEKARANG sudah melewati DEADLINE
        $isOverdue = now()->gt($tiket->tanggal_selesai);
    }
@endphp

                                <tr class="{{ $isOverdue ? 'row-overdue' : '' }}">
                                    <td class="text-center">{{ ($tikets->currentPage() - 1) * $tikets->perPage() + $loop->index + 1 }}</td>
                                    <td>
                                        <strong class="text-primary">{{ $tiket->nomor }}</strong><br>
                                        @if($tiket->tipe_masalah)
                                            <span class="badge badge-secondary badge-sm">{{ $tiket->tipe_masalah }}</span>
                                        @endif
                                        @if($isOverdue)
                                            <br><span class="badge badge-overdue badge-sm mt-1">
                                                <i class="fas fa-exclamation-triangle"></i> TERLAMBAT
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="mb-1">
                                            <i class="fas fa-calendar text-muted"></i>
                                            <small><strong>{{ $tiket->tanggal?->format('d/m/Y H:i') ?? '-' }}</strong></small>
                                        </div>
                                        @if($tiket->tanggal_selesai && !in_array($tiket->status, ['closed', 'finish']))
                                            <div class="mt-1">
                                                <i class="fas fa-clock {{ $isOverdue ? 'text-danger' : 'text-warning' }}"></i>
                                                <small class="{{ $isOverdue ? 'text-danger font-weight-bold' : 'text-warning' }}">
                                                    {{ $tiket->tanggal_selesai->format('d/m H:i') }}
                                                </small>
                                            <!-- @if($isOverdue)
                                                    <br><small class="text-danger"><strong>Lewat {{ now()->diffInHours($tiket->tanggal_selesai) }}h</strong></small>
                                                @endif -->
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="mb-1">
                                            <strong>{{ \Illuminate\Support\Str::limit($tiket->judul, 40) }}</strong>
                                        </div>
                                        <small class="text-muted">{{ \Illuminate\Support\Str::limit($tiket->keterangan, 50) }}</small>
                                    </td>
                                    
                                    <td>
                                        @if($tiket->urgency)
                                            @php
                                                $urgencyBadge = 'badge-info';
                                                if($tiket->urgency->jam <= 24) $urgencyBadge = 'badge-danger';
                                                elseif($tiket->urgency->jam <= 72) $urgencyBadge = 'badge-warning';
                                            @endphp
                                            <span class="badge {{ $urgencyBadge }}">{{ $tiket->urgency->urgency }}</span><br>
                                            <small class="text-muted">{{ $tiket->urgency->jam }}h</small>
                                        @else
                                            <small class="text-muted">Belum di-set</small>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ $tiket->departemen->nama_departemen ?? '-' }}</small>
                                    </td>
                                    <td>
                                        @if($tiket->teknisi)
                                            <small><strong>{{ $tiket->teknisi->nama }}</strong></small>
                                        @else
                                            <small class="text-muted">Belum di-assign</small>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $statusBadge = [
                                                'open' => 'badge-secondary',
                                                'pending' => 'badge-warning',
                                                'progress' => 'badge-primary',
                                                'finish' => 'badge-success',
                                                'closed' => 'badge-dark',
                                            ][$tiket->status] ?? 'badge-secondary';
                                        @endphp
                                        <span class="badge {{ $statusBadge }}">{{ strtoupper($tiket->status) }}</span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            {{-- Detail --}}
                                            <button type="button" class="btn btn-info" onclick="showDetail({{ $tiket->id }})" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </button>

                                            {{-- Admin Actions --}}
                                            @if(in_array($role, ['admin','administrator']))
                                                @if($tiket->status === 'open')
                                                    <button type="button" class="btn btn-primary" onclick="showDetail({{ $tiket->id }})" title="Assign">
                                                        <i class="fas fa-share-square"></i>
                                                    </button>
                                                @elseif($tiket->status === 'pending')
                                                    <form action="{{ route('tiket.unassign', $tiket->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Unassign tiket ini dan kembalikan ke status OPEN?')">
                                                        @csrf
                                                        <button type="submit" class="btn btn-outline-danger" title="Unassign">
                                                            <i class="fas fa-undo"></i>
                                                        </button>
                                                    </form>
                                                @elseif($tiket->status === 'finish')
                                                    <form action="{{ route('tiket.close', $tiket->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Verifikasi dan tutup tiket ini?')">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success" title="Close">
                                                            <i class="fas fa-check-double"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            @endif

                                            {{-- Teknisi Actions --}}
                                            @if($role === 'teknisi' && $tiket->teknisi_id === auth()->id())
                                                @if($tiket->status === 'pending')
                                                    <form action="{{ route('tiket.accept', $tiket->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Terima tiket ini dan mulai kerjakan?')">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success" title="Terima">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </form>
                                                    <button type="button" class="btn btn-danger" onclick="openReject({{ $tiket->id }})" title="Tolak">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                @elseif($tiket->status === 'progress')
                                                    <button type="button" class="btn btn-success" onclick="openComplete({{ $tiket->id }})" title="Selesai">
                                                        <i class="fas fa-flag-checkered"></i>
                                                    </button>
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5">
                                        <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                                        <h5 class="text-muted">Belum ada tiket untuk ditampilkan</h5>
                                        @if($role === 'user')
                                            <button class="btn btn-primary mt-2" data-toggle="modal" data-target="#modalCreate">
                                                <i class="fas fa-plus-circle"></i> Buat Tiket Baru
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($tikets->hasPages())
                    <div class="card-footer clearfix">
                        <div class="float-right">
                            {{ $tikets->appends(request()->query())->links() }}
                        </div>
                        <div class="float-left">
                            <small class="text-muted">
                                Menampilkan {{ $tikets->firstItem() ?? 0 }} sampai {{ $tikets->lastItem() ?? 0 }} dari {{ $tikets->total() }} tiket
                            </small>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- ======================= MODAL ======================= --}}

    {{-- Modal: Create Ticket (User) --}}
    @if($role === 'user')
    <div class="modal fade" id="modalCreate" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('tiket.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title"><i class="fas fa-plus-circle"></i> Buat Tiket Baru</h5>
                        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        
                        
                        <div class="form-group">
                            <label>Judul Tiket <span class="text-danger">*</span></label>
                            <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror"
                                value="{{ old('judul') }}"
                                required>
                            @error('judul')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                        
                        <div class="form-group">
                            <label>Deskripsi Masalah <span class="text-danger">*</span></label>
                            <textarea name="keterangan" rows="5"
                                    class="form-control @error('keterangan') is-invalid @enderror"
                                    placeholder="Jelaskan masalah secara detail"
                                    required>{{ old('keterangan') }}</textarea>
                            @error('keterangan')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            
                        </div>
                        
                        <div class="form-group">
                            <label>Lampiran Gambar (Wajib)</label>
                            <div class="custom-file">
                                <input type="file" name="gambar" class="custom-file-input @error('gambar') is-invalid @enderror" 
                                    id="gambarInput" accept="image/jpeg,image/png,image/jpg">
                                <label class="custom-file-label" for="gambarInput">Pilih file...</label>
                            </div>
                            @error('gambar')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                            <small class="form-text text-muted">Format: JPG, PNG. Maksimal 2MB.</small>
                            <div class="mt-2">
                                <img id="gambarPreview" src="" style="max-width: 200px; display:none;" class="img-thumbnail">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i> Kirim Tiket
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    {{-- Modal: Detail --}}
    <div class="modal fade" id="modalDetail" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-gradient-info text-white">
                    <h5 class="modal-title"><i class="fas fa-ticket-alt"></i> Detail Tiket</h5>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body modal-body-scroll">
                    <div id="detailBody">
                        <div class="text-center py-5">
                            <i class="fas fa-spinner fa-spin fa-3x text-muted"></i>
                            <p class="text-muted mt-3">Memuat data tiket...</p>
                        </div>
                    </div>
                </div>
                @if(in_array($role, ['admin','administrator']))
<div class="modal-footer bg-light">
    <div id="assignFormContainer" style="display:none; width:100%;">
        <!-- Container untuk analisis C4.5 -->
        <div id="c45AnalysisContainer" class="mb-3"></div>
        
        <form id="formAssign" method="POST" class="w-100">
            @csrf
            <div class="alert alert-info py-2 mb-3">
                <i class="fas fa-info-circle"></i> <strong>Assign tiket ini ke teknisi:</strong>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <label class="small font-weight-bold">Urgency <span class="text-danger">*</span></label>
                    <select name="urgency_id" id="assign_urgency_id" class="form-control form-control-sm" required>
                        <option value="">Pilih urgency</option>
                        @foreach($urgencies as $u)
                            <option value="{{ $u->id }}">{{ $u->urgency }} ({{ $u->jam }}h)</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="small font-weight-bold">Teknisi <span class="text-danger">*</span></label>
                    <select name="teknisi_id" id="assign_teknisi_id" class="form-control form-control-sm" required>
                        <option value="">Pilih teknisi</option>
                        @foreach($teknisis as $t)
                            <option value="{{ $t->id }}">{{ $t->nama }} - {{ $t->departemen->nama_departemen ?? '' }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="small font-weight-bold">Catatan (Opsional)</label>
                    <textarea name="catatan_admin" id="assign_catatan_admin" rows="1"
                            class="form-control form-control-sm"
                            placeholder="Catatan untuk teknisi"></textarea>
                </div>
            </div>
            <div class="mt-3 text-right">
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-sm btn-primary">
                    <i class="fas fa-share-square"></i> Assign ke Teknisi
                </button>
            </div>
        </form>
    </div>
    <div id="closeButtonContainer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
    </div>
</div>
@else
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Modal: Reject (Teknisi) --}}
    @if($role === 'teknisi')
    <div class="modal fade" id="modalReject" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formReject" method="POST">
                    @csrf
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title"><i class="fas fa-times-circle"></i> Tolak Tiket</h5>
                        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i> Tiket yang ditolak akan dikembalikan ke admin untuk di-assign ke teknisi lain.
                        </div>
                        <div class="form-group">
                            <label>Alasan Penolakan <span class="text-danger">*</span></label>
                            <textarea name="alasan_penolakan" id="reject_alasan" rows="4" class="form-control" 
                                    placeholder="Jelaskan alasan penolakan (wajib diisi)"
                                    required></textarea>
                            <small class="form-text text-muted">Alasan ini akan dikirim ke admin.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-times-circle"></i> Kirim Penolakan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal: Complete (Teknisi) --}}
    <div class="modal fade" id="modalComplete" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formComplete" method="POST">
                    @csrf
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title"><i class="fas fa-flag-checkered"></i> Selesaikan Tiket</h5>
                        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Tiket akan masuk ke status <strong>FINISH</strong> dan menunggu verifikasi dari admin.
                        </div>
                        <div class="form-group">
                            <label>Solusi / Tindakan yang Dilakukan <span class="text-danger">*</span></label>
                            <textarea name="solusi" id="complete_solusi" rows="4" class="form-control" 
                                    placeholder="Jelaskan tindakan yang sudah dilakukan untuk menyelesaikan masalah"
                                    required></textarea>
                            <small class="form-text text-muted">Solusi ini akan dilihat oleh admin dan user.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check-circle"></i> Tandai Selesai
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
    @endsection

    @push('scripts')
   <script>
$(function () {
    // Custom file label
    $('.custom-file-input').on('change', function () {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').html(fileName);
        if (this.files && this.files[0]) {
            let reader = new FileReader();
            reader.onload = function (e) {
                $('#gambarPreview').attr('src', e.target.result).show();
            }
            reader.readAsDataURL(this.files[0]);
        }
    });
});

// Handler untuk form assign
$('#formAssign').on('submit', function(e) {
    e.preventDefault(); // Cegah form submit biasa
    
    const form = $(this);
    const url = form.attr('action');
    const formData = form.serialize();
    
    // Disable button saat proses
    const submitBtn = form.find('button[type="submit"]');
    submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Memproses...');
    
    $.ajax({
        url: url,
        type: 'POST',
        data: formData,
        success: function(response) {
            if(response.success) {
                // Tutup modal
                $('#modalDetail').modal('hide');
                
                // Tampilkan pesan sukses
                alert('Berhasil! ' + response.message);
                
                // Reload halaman untuk update data
                location.reload();
            } else {
                alert('Gagal: ' + (response.message || 'Terjadi kesalahan'));
                submitBtn.prop('disabled', false).html('<i class="fas fa-share-square"></i> Assign ke Teknisi');
            }
        },
        error: function(xhr) {
            let errorMsg = 'Terjadi kesalahan saat assign tiket';
            if(xhr.responseJSON && xhr.responseJSON.message) {
                errorMsg = xhr.responseJSON.message;
            }
            alert('Error: ' + errorMsg);
            submitBtn.prop('disabled', false).html('<i class="fas fa-share-square"></i> Assign ke Teknisi');
        }
    });
});

function showDetail(id) {
    $('#detailBody').html('<div class="text-center py-5"><i class="fas fa-spinner fa-spin fa-3x text-muted"></i><p class="text-muted mt-3">Memuat data tiket...</p></div>');
    
    @if(in_array($role, ['admin','administrator']))
    $('#formAssign').attr('action', '{{ route("tiket.assign", ":id") }}'.replace(':id', id));
    $('#assignFormContainer').hide();
    $('#closeButtonContainer').show();
    $('#c45AnalysisContainer').html(''); // Kosongkan dulu
    @endif
    
    $('#modalDetail').modal('show');

    $.get('{{ route("tiket.show", ":id") }}'.replace(':id', id), function (res) {
        if (!res.success) {
            $('#detailBody').html('<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> '+res.message+'</div>');
            return;
        }
        const t = res.tiket;

        // Format tanggal
        const formatDate = (dateStr) => {
            if (!dateStr) return '-';
            const d = new Date(dateStr);
            const days = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
            return `${days[d.getDay()]}, ${d.getDate()} ${months[d.getMonth()]} ${d.getFullYear()} ${String(d.getHours()).padStart(2,'0')}:${String(d.getMinutes()).padStart(2,'0')}`;
        };

        // Hitung durasi
        const calculateDuration = (start, end) => {
            if (!start || !end) return null;
            const diff = new Date(end) - new Date(start);
            const hours = Math.floor(diff / (1000 * 60 * 60));
            const days = Math.floor(hours / 24);
            const remainHours = hours % 24;
            
            if (days > 0) {
                return `${days} hari ${remainHours} jam`;
            }
            return `${hours} jam`;
        };

        // Status deadline
let deadlineHtml = '';
if (t.tanggal_selesai && t.status !== 'closed') {
    const deadline = new Date(t.tanggal_selesai);
    const now = new Date();
    const isOverdue = now > deadline; // Perbaiki: now HARUS > deadline
    const hoursDiff = Math.abs(Math.floor((deadline - now) / (1000 * 60 * 60)));
    
    deadlineHtml = `
        <div class="alert alert-${isOverdue ? 'danger' : 'warning'} py-2 px-3 mb-3">
            <i class="fas ${isOverdue ? 'fa-exclamation-triangle' : 'fa-clock'}"></i> 
            <strong>${isOverdue ? 'TERLAMBAT' : 'DEADLINE'}:</strong> ${formatDate(t.tanggal_selesai)}
            ${isOverdue ? `<br><small><strong>Sudah lewat ${hoursDiff} jam dari deadline!</strong></small>` : `<br><small>Sisa waktu: ${hoursDiff} jam</small>`}
        </div>
    `;
}


        // Timeline
        let timelineHtml = `
            <li class="timeline-item">
                <i class="fas fa-plus-circle bg-primary"></i>
                <div class="timeline-content">
                    <div class="timeline-header">
                        <strong>Tiket Dibuat</strong>
                        <span class="time"><i class="fas fa-clock"></i> ${formatDate(t.tanggal)}</span>
                    </div>
                    <div class="timeline-body">
                        Oleh: <strong>${t.user?.nama || '-'}</strong><br>
                        Dept: ${t.user?.departemen?.nama_departemen || '-'}
                    </div>
                </div>
            </li>
        `;

        if (t.teknisi_id && t.urgency_id) {
            timelineHtml += `
                <li class="timeline-item">
                    <i class="fas fa-user-cog bg-info"></i>
                    <div class="timeline-content">
                        <div class="timeline-header">
                            <strong>Di-assign ke Teknisi</strong>
                        </div>
                        <div class="timeline-body">
                            Teknisi: <strong>${t.teknisi?.nama || '-'}</strong><br>
                            Urgency: <span class="badge badge-warning badge-sm">${t.urgency?.urgency || '-'}</span> (${t.urgency?.jam || 0}h)<br>
                            <small class="text-muted">Target: ${formatDate(t.tanggal_selesai)}</small>
                        </div>
                    </div>
                </li>
            `;
        }

        if (t.status === 'progress') {
            timelineHtml += `
                <li class="timeline-item">
                    <i class="fas fa-spinner bg-primary"></i>
                    <div class="timeline-content">
                        <div class="timeline-header">
                            <strong>Sedang Dikerjakan</strong>
                        </div>
                        <div class="timeline-body">
                            Teknisi sedang menangani tiket
                        </div>
                    </div>
                </li>
            `;
        }

        if (t.status === 'finish' || t.status === 'closed') {
            const duration = calculateDuration(t.tanggal, t.tanggal_selesai);
            timelineHtml += `
                <li class="timeline-item">
                    <i class="fas fa-check-circle bg-success"></i>
                    <div class="timeline-content">
                        <div class="timeline-header">
                            <strong>Selesai Dikerjakan</strong>
                            ${t.tanggal_selesai ? `<span class="time"><i class="fas fa-clock"></i> ${formatDate(t.tanggal_selesai)}</span>` : ''}
                        </div>
                        <div class="timeline-body">
                            ${duration ? `<small class="text-muted">Durasi pengerjaan: ${duration}</small>` : ''}
                        </div>
                    </div>
                </li>
            `;
        }

        if (t.status === 'closed') {
            timelineHtml += `
                <li class="timeline-item">
                    <i class="fas fa-lock bg-dark"></i>
                    <div class="timeline-content">
                        <div class="timeline-header">
                            <strong>Tiket Ditutup</strong>
                        </div>
                        <div class="timeline-body">
                            Diverifikasi dan ditutup oleh admin
                        </div>
                    </div>
                </li>
            `;
        }

        // Gambar
        let imgHtml = '';
        if (t.gambar) {
            imgHtml = `<div class="text-center mb-3">
                <img src="{{ asset('public/storage') }}/${t.gambar}" class="img-fluid rounded border shadow-sm" style="max-height:250px; cursor:pointer;" onclick="window.open(this.src, '_blank')">
                <br><small class="text-muted"><i class="fas fa-search-plus"></i> Klik untuk memperbesar</small>
            </div>`;
        }

        // Badge status
        const statusBadge = {
            'open': 'badge-secondary',
            'pending': 'badge-warning',
            'progress': 'badge-primary',
            'finish': 'badge-success',
            'closed': 'badge-dark'
        }[t.status] || 'badge-secondary';

        // Rekomendasi C4.5 (HANYA ADMIN & ADMINISTRATOR)
        let c45Html = '';
        @if(in_array($role, ['admin','administrator']))
        if (t.recommended_urgency_id) {
            const isMatch = t.urgency_id === t.recommended_urgency_id;
            const matchIcon = isMatch ? '<i class="fas fa-check-circle text-success"></i>' : '<i class="fas fa-times-circle text-warning"></i>';
            const matchText = isMatch ? 'Sesuai Rekomendasi' : 'Tidak Sesuai Rekomendasi';
            
            c45Html = `
            <div class="card card-info card-outline mb-3">
                <div class="card-header py-2 bg-gradient-info">
                    <h6 class="mb-0 text-white"><i class="fas fa-robot"></i> Analisis C4.5 Decision Tree</h6>
                </div>
                <div class="card-body p-3">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td width="45%"><strong>Rekomendasi Urgency:</strong></td>
                            <td><span class="badge badge-info">${t.recommended_urgency?.urgency || '-'}</span> <small class="text-muted">(${t.recommended_urgency?.jam || 0} jam)</small></td>
                        </tr>
                        <tr>
                            <td><strong>Confidence Score:</strong></td>
                            <td>
                                <div class="progress" style="height: 22px;">
                                    <div class="progress-bar bg-info progress-bar-striped progress-bar-animated" role="progressbar" style="width: ${(t.confidence_score * 100).toFixed(1)}%">
                                        <strong>${(t.confidence_score * 100).toFixed(1)}%</strong>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Tipe Masalah:</strong></td>
                            <td><span class="badge badge-secondary">${t.tipe_masalah || '-'}</span></td>
                        </tr>
                        <tr>
                            <td><strong>Kata Kunci:</strong></td>
                            <td>${t.kata_kunci || '-'}</code></td>
                        </tr>
                        <tr>
                            <td><strong>Dept. Terdampak:</strong></td>
                            <td>${t.dept_terdampak || '-'}</td>
                        </tr>
                        ${t.urgency_id ? `
                        <tr>
                            <td><strong>Status Akurasi:</strong></td>
                            <td>${matchIcon} <small class="font-weight-bold">${matchText}</small></td>
                        </tr>
                        ` : ''}
                    </table>
                </div>
            </div>
            `;
        }
        @endif

        // Catatan Ditolak (HANYA admin & administrator DAN tiket BELUM di-assign)
        let catatanDitolakHtml = '';
        @if(in_array($role, ['admin','administrator']))
        if (!t.teknisi_id && t.catatan_ditolak) {
            catatanDitolakHtml = `
                <div class="alert alert-danger">
                    <h6 class="text-danger mb-2"><i class="fas fa-ban"></i> Catatan Penolakan</h6>
                    <p class="mb-1" style="white-space: pre-line">${t.catatan_ditolak}</p>
                    <small class="text-muted"><em><i class="fas fa-info-circle"></i> Tiket ini pernah ditolak oleh teknisi dan dikembalikan ke admin</em></small>
                </div>
            `;
        }
        @endif

        // Catatan Admin
        let catatanAdminHtml = '';
        if (t.catatan_admin) {
            catatanAdminHtml = `
                <div class="card card-outline card-info mb-3">
                    <div class="card-header py-2">
                        <h6 class="mb-0"><i class="fas fa-sticky-note"></i> Catatan Admin</h6>
                    </div>
                    <div class="card-body p-3">
                        <p class="mb-0" style="white-space: pre-line">${t.catatan_admin}</p>
                    </div>
                </div>
            `;
        }

        $('#detailBody').html(`
            <div class="row mb-2">
                <div class="col-12">
                    <h5 class="mb-2">
                        <span class="badge badge-primary">${t.nomor}</span>
                        <span class="badge ${statusBadge}">${t.status.toUpperCase()}</span>
                    </h5>
                    <h6 class="mb-0 font-weight-bold">${t.judul}</h6>
                </div>
            </div>

            ${deadlineHtml}

            <div class="row">
                <div class="col-md-8">
                    ${catatanDitolakHtml}

                    ${catatanAdminHtml}

                    <div class="card card-outline card-secondary mb-3">
                        <div class="card-header py-2">
                            <h6 class="mb-0"><i class="fas fa-info-circle"></i> Deskripsi Masalah</h6>
                        </div>
                        <div class="card-body p-3">
                            <p class="mb-0" style="white-space: pre-line;">${t.keterangan || '-'}</p>
                        </div>
                    </div>

                    ${t.solusi ? `<div class="card card-outline card-success mb-3">
                        <div class="card-header py-2">
                            <h6 class="mb-0"><i class="fas fa-check-circle"></i> Solusi / Tindakan</h6>
                        </div>
                        <div class="card-body p-3">
                            <p class="mb-0" style="white-space: pre-line;">${t.solusi}</p>
                        </div>
                    </div>` : ''}

                    ${t.catatan ? `<div class="card card-outline card-warning mb-3">
                        <div class="card-header py-2">
                            <h6 class="mb-0"><i class="fas fa-sticky-note"></i> Catatan Teknisi</h6>
                        </div>
                        <div class="card-body p-3">
                            <p class="mb-0" style="white-space: pre-line;">${t.catatan}</p>
                        </div>
                    </div>` : ''}

                    ${imgHtml}
                </div>

                <div class="col-md-4">
                    <div class="card card-outline card-primary mb-3">
                        <div class="card-header py-2">
                            <h6 class="mb-0"><i class="fas fa-clipboard-list"></i> Informasi Tiket</h6>
                        </div>
                        <div class="card-body p-2">
                            <table class="table table-sm table-borderless mb-0">
                                <tr>
                                    <td width="40%" class="text-muted"><small><i class="fas fa-building"></i> Departemen</small></td>
                                    <td><strong>${t.departemen?.nama_departemen || '-'}</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-muted"><small><i class="fas fa-user"></i> Pembuat</small></td>
                                    <td><strong>${t.user?.nama || '-'}</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-muted"><small><i class="fas fa-exclamation-triangle"></i> Urgency</small></td>
                                    <td>${t.urgency ? `<span class="badge badge-warning">${t.urgency.urgency}</span><br><small class="text-muted">${t.urgency.jam} jam (SLA)</small>` : '<small class="text-muted">Belum di-set</small>'}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted"><small><i class="fas fa-user-cog"></i> Teknisi</small></td>
                                    <td>${t.teknisi ? `<strong>${t.teknisi.nama}</strong><br><small class="text-muted">${t.teknisi.departemen?.nama_departemen || '-'}</small>` : '<small class="text-muted">Belum di-assign</small>'}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="card card-outline card-info" style="overflow: visible;">
                        <div class="card-header py-2 bg-light">
                            <h6 class="mb-0"><i class="fas fa-history"></i> Timeline Tiket</h6>
                        </div>
                        <div class="card-body p-3" style="overflow: visible;">
                            <ul class="timeline-custom" style="list-style: none; padding-left: 0; margin: 0;">
                                ${timelineHtml}
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        `);

        // Show assign form untuk admin jika status open
        @if(in_array($role, ['admin','administrator']))
        if (t.status === 'open') {
            // Inject C4.5 analysis ke dalam assign form container
            $('#c45AnalysisContainer').html(c45Html);
            
            $('#assignFormContainer').show();
            $('#closeButtonContainer').hide();
            
            // Pre-select rekomendasi urgency
            if (t.recommended_urgency_id) {
                $('#assign_urgency_id').val(t.recommended_urgency_id);
            }
        } else {
            // Kosongkan C4.5 jika bukan status open
            $('#c45AnalysisContainer').html('');
        }
        @endif
    }).fail(function (xhr) {
        $('#detailBody').html('<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> Gagal memuat detail tiket. Silakan coba lagi.</div>');
    });
}

function openReject(id) {
    $('#formReject').attr('action', '{{ route("tiket.reject", ":id") }}'.replace(':id', id));
    $('#reject_alasan').val('');
    $('#modalReject').modal('show');
}

function openComplete(id) {
    $('#formComplete').attr('action', '{{ route("tiket.complete", ":id") }}'.replace(':id', id));
    $('#complete_solusi').val('');
    $('#modalComplete').modal('show');
}
</script>


    @endpush
