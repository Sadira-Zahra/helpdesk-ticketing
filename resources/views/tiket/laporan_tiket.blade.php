@extends('layouts.main')

@section('title', 'Laporan Tiket')

@push('styles')
<!-- Daterangepicker CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">

<style>
/* Stat Cards */
.stat-card {
    border-radius: 12px;
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

/* Filter Card */
.filter-card {
    background: linear-gradient(135deg, #f9fafb 0%, #ffffff 100%);
}

/* Badge Status */
.badge-status {
    padding: 0.5em 1em;
    border-radius: 8px;
    font-weight: 600;
}

/* Daterangepicker Position Fix */
.daterangepicker {
    z-index: 10000 !important;
    position: fixed !important;
}

.main-sidebar .daterangepicker,
.sidebar .daterangepicker {
    display: none !important;
}

.main-sidebar {
    z-index: 1050 !important;
}

/* Table Responsive */
.table-responsive {
    border-radius: 12px;
    overflow: hidden;
}

/* Empty State */
.empty-state {
    padding: 60px 20px;
    text-align: center;
}

.empty-state i {
    font-size: 4rem;
    color: #d1d5db;
    margin-bottom: 1rem;
}

.empty-state h5 {
    color: #374151;
    margin-bottom: 0.5rem;
}

.empty-state p {
    color: #6b7280;
}

/* Modal Styles */
.modal-body-scroll {
    max-height: 70vh;
    overflow-y: auto;
}

/* Timeline Styles - Custom Clean */
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
    z-index: 2;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}

.timeline-custom::before,
.timeline-custom .timeline-item::before,
.timeline-custom .timeline-item::after {
    display: none !important;
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

/* Progress Bar */
.progress {
    background-color: #e9ecef;
    position: relative;
    z-index: 1;
}

.progress-bar strong {
    font-size: 0.85em;
}
</style>
@endpush

@section('content')
<!-- Content Header -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">
                    <i class="fas fa-file-alt text-primary mr-2"></i> 
                    Laporan Tiket
                </h1>
                <p class="text-muted mb-0">Laporan dan export data tiket</p>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">
                            <i class="fas fa-home"></i> Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item active">Laporan Tiket</li>
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
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <strong>Gagal!</strong> {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        {{-- Filter Card --}}
        <div class="card card-outline card-primary filter-card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-filter mr-2"></i> Filter Laporan
                </h3>
            </div>
            <div class="card-body">
                <form action="{{ route('tiket.laporan') }}" method="GET" id="filterForm">
                    <div class="row">
                        
                        {{-- Periode Tanggal --}}
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="dateRangePicker">
                                    <i class="fas fa-calendar mr-1"></i> 
                                    Rentang Tanggal
                                </label>
                                <input type="text" 
                                       name="filter_date" 
                                       id="dateRangePicker" 
                                       class="form-control" 
                                       placeholder="Pilih rentang tanggal"
                                       value="{{ $filterDate ?? '' }}"
                                       autocomplete="off">
                                <input type="hidden" name="start_date" id="start_date" value="{{ $startDate ?? '' }}">
                                <input type="hidden" name="end_date" id="end_date" value="{{ $endDate ?? '' }}">
                                <small class="text-muted">Kosongkan untuk semua tanggal</small>
                            </div>
                        </div>

                        {{-- Departemen Filter (Administrator Only) --}}
                        @if($role === 'administrator')
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="departemen_id">
                                    <i class="fas fa-building mr-1"></i> 
                                    Departemen
                                </label>
                                <select name="departemen_id" id="departemen_id" class="form-control">
                                    <option value="">Semua Departemen</option>
                                    @foreach($departemens as $dept)
                                        <option value="{{ $dept->id }}" 
                                                {{ (isset($departemenId) && $departemenId == $dept->id) ? 'selected' : '' }}>
                                            {{ $dept->nama_departemen }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endif

                        {{-- Status Filter --}}
                        <div class="col-md-{{ $role === 'administrator' ? '2' : '4' }}">
                            <div class="form-group">
                                <label for="status">
                                    <i class="fas fa-flag mr-1"></i> 
                                    Status
                                </label>
                                <select name="status" id="status" class="form-control">
                                    <option value="all" {{ (isset($statusFilter) && $statusFilter == 'all') ? 'selected' : '' }}>
                                        Semua Status
                                    </option>
                                    <option value="open" {{ (isset($statusFilter) && $statusFilter == 'open') ? 'selected' : '' }}>
                                        Open
                                    </option>
                                    <option value="in_progress" {{ (isset($statusFilter) && $statusFilter == 'in_progress') ? 'selected' : '' }}>
                                        In Progress
                                    </option>
                                    <option value="closed" {{ (!isset($statusFilter) || $statusFilter == 'closed') ? 'selected' : '' }}>
                                        Closed
                                    </option>
                                </select>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="col-md-2">
                            <label>&nbsp;</label>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-search mr-1"></i> Filter
                                </button>
                                <a href="{{ route('tiket.laporan') }}" class="btn btn-secondary btn-block">
                                    <i class="fas fa-redo mr-1"></i> Reset
                                </a>
                            </div>
                        </div>
                        
                    </div>
                </form>
            </div>
        </div>

        {{-- Conditional Content --}}
        @if(isset($showData) && $showData)
            
            {{-- Statistics Cards --}}
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box stat-card bg-info">
                        <div class="inner">
                            <h3>{{ $stats['total'] ?? 0 }}</h3>
                            <p>Total Tiket</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-ticket-alt"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box stat-card bg-success">
                        <div class="inner">
                            <h3>{{ $stats['closed'] ?? 0 }}</h3>
                            <p>Closed</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box stat-card bg-warning">
                        <div class="inner">
                            <h3>{{ $stats['in_progress'] ?? 0 }}</h3>
                            <p>In Progress</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-spinner"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box stat-card bg-danger">
                        <div class="inner">
                            <h3>{{ $stats['open'] ?? 0 }}</h3>
                            <p>Open</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-folder-open"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Data Table --}}
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-table mr-2"></i> 
                        Data Tiket
                        @if($tikets && method_exists($tikets, 'total') && $tikets->total() > 0)
                            <span class="badge badge-info ml-2">{{ $tikets->total() }} tiket</span>
                        @endif
                    </h3>
                    <div class="card-tools">
                        @if($tikets && method_exists($tikets, 'count') && $tikets->count() > 0)
                            <form action="{{ route('tiket.laporan.export') }}" method="GET" class="d-inline">
                                <input type="hidden" name="start_date" value="{{ $startDate ?? '' }}">
                                <input type="hidden" name="end_date" value="{{ $endDate ?? '' }}">
                                @if($role === 'administrator' && isset($departemenId) && $departemenId)
                                    <input type="hidden" name="departemen_id" value="{{ $departemenId }}">
                                @endif
                                <input type="hidden" name="status" value="{{ $statusFilter ?? 'closed' }}">
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="fas fa-file-excel mr-1"></i> Export Excel
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th style="width: 50px;" class="text-center">No</th>
                                    <th style="width: 130px;">Nomor Tiket</th>
                                    <th style="width: 140px;">Tanggal</th>
                                    <th>Judul</th>
                                    <th style="width: 150px;">User</th>
                                    <th style="width: 150px;">Departemen</th>
                                    <th style="width: 100px;">Urgency</th>
                                    <th style="width: 150px;">Teknisi</th>
                                    <th style="width: 100px;">Status</th>
                                    <th style="width: 80px;" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($tikets && method_exists($tikets, 'count') && $tikets->count() > 0)
                                    @foreach($tikets as $tiket)
                                        <tr>
                                            <td class="text-center">
                                                {{ $tikets->firstItem() + $loop->index }}
                                            </td>
                                            <td>
                                                <strong class="text-primary">{{ $tiket->nomor }}</strong>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    {{ $tiket->tanggal ? $tiket->tanggal->format('d/m/Y H:i') : '-' }}
                                                </small>
                                            </td>
                                            <td>
                                                <span data-toggle="tooltip" title="{{ $tiket->judul }}">
                                                    {{ Str::limit($tiket->judul, 40) }}
                                                </span>
                                            </td>
                                            <td>{{ optional($tiket->user)->nama ?? '-' }}</td>
                                            <td>
                                                <span class="badge badge-secondary">
                                                    {{ optional($tiket->departemen)->nama_departemen ?? '-' }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($tiket->urgency)
                                                    @php
                                                        $urgencyClass = [
                                                            'Low' => 'success',
                                                            'Medium' => 'info',
                                                            'High' => 'warning',
                                                            'Critical' => 'danger'
                                                        ][$tiket->urgency->urgency] ?? 'secondary';
                                                    @endphp
                                                    <span class="badge badge-{{ $urgencyClass }}">
                                                        {{ $tiket->urgency->urgency }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>{{ optional($tiket->teknisi)->nama ?? '-' }}</td>
                                            <td>
                                                @php
                                                    $statusClass = [
                                                        'open' => 'danger',
                                                        'in_progress' => 'warning',
                                                        'progress' => 'warning',
                                                        'closed' => 'success'
                                                    ][$tiket->status] ?? 'secondary';
                                                @endphp
                                                <span class="badge badge-status badge-{{ $statusClass }}">
                                                    {{ strtoupper(str_replace('_', ' ', $tiket->status)) }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" 
                                                        class="btn btn-info btn-sm" 
                                                        onclick="showDetail({{ $tiket->id }})" 
                                                        data-toggle="tooltip"
                                                        title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="10" class="text-center">
                                            <div class="empty-state">
                                                <i class="fas fa-inbox"></i>
                                                <h5 class="text-muted">Tidak Ada Data</h5>
                                                <p class="text-muted mb-0">
                                                    Tidak ada tiket dalam periode dan filter yang dipilih
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                
                {{-- Pagination --}}
                @if($tikets && method_exists($tikets, 'hasPages') && $tikets->hasPages())
                    <div class="card-footer clearfix">
                        <div class="float-left">
                            <small class="text-muted">
                                Menampilkan {{ $tikets->firstItem() }} - {{ $tikets->lastItem() }} 
                                dari {{ $tikets->total() }} tiket
                            </small>
                        </div>
                        <div class="float-right">
                            {{ $tikets->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                @endif
            </div>

        @else
            
            {{-- Empty State: No Filter Selected --}}
            <div class="card">
                <div class="card-body">
                    <div class="empty-state">
                        <i class="fas fa-filter"></i>
                        <h4>Pilih Filter Terlebih Dahulu</h4>
                        <p class="mb-0">
                            Silakan pilih periode tanggal pada filter di atas untuk menampilkan data laporan tiket
                        </p>
                    </div>
                </div>
            </div>

        @endif

    </div>
</section>
@endsection

{{-- Modal: Detail Tiket --}}
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
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<!-- Daterangepicker -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script>
$(function() {
    
    // ========================================
    // Initialize Daterangepicker
    // ========================================
    
    $('#dateRangePicker').daterangepicker({
        autoUpdateInput: false,
        locale: {
            format: 'DD/MM/YYYY',
            separator: ' - ',
            applyLabel: 'Terapkan',
            cancelLabel: 'Batal',
            fromLabel: 'Dari',
            toLabel: 'Sampai',
            customRangeLabel: 'Custom',
            daysOfWeek: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
            monthNames: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
        },
        ranges: {
            'Hari Ini': [moment(), moment()],
            'Kemarin': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            '7 Hari Terakhir': [moment().subtract(6, 'days'), moment()],
            '30 Hari Terakhir': [moment().subtract(29, 'days'), moment()],
            'Bulan Ini': [moment().startOf('month'), moment().endOf('month')],
            'Bulan Lalu': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    });

    $('#dateRangePicker').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
        $('#start_date').val(picker.startDate.format('YYYY-MM-DD'));
        $('#end_date').val(picker.endDate.format('YYYY-MM-DD'));
    });

    $('#dateRangePicker').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
        $('#start_date').val('');
        $('#end_date').val('');
    });

});

function showDetail(id) {
    $('#detailBody').html('<div class="text-center py-5"><i class="fas fa-spinner fa-spin fa-3x text-muted"></i><p class="text-muted mt-3">Memuat data tiket...</p></div>');
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
            const isOverdue = deadline < now;
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

        // Analisis C4.5 (HANYA ADMIN & ADMINISTRATOR) - UBAH JADI 1 KOLOM
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
                            <td width="40%"><strong>Rekomendasi Urgency:</strong></td>
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

        // Urutan: Deskripsi -> Gambar -> Solusi -> Analisis C4.5
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
                    <div class="card card-outline card-secondary mb-3">
                        <div class="card-header py-2">
                            <h6 class="mb-0"><i class="fas fa-info-circle"></i> Deskripsi Masalah</h6>
                        </div>
                        <div class="card-body p-3">
                            <p class="mb-0" style="white-space: pre-line;">${t.keterangan || '-'}</p>
                        </div>
                    </div>

                    ${imgHtml}

                    ${t.solusi ? `<div class="card card-outline card-success mb-3">
                        <div class="card-header py-2">
                            <h6 class="mb-0"><i class="fas fa-check-circle"></i> Solusi / Tindakan</h6>
                        </div>
                        <div class="card-body p-3">
                            <p class="mb-0" style="white-space: pre-line;">${t.solusi}</p>
                        </div>
                    </div>` : ''}

                    ${c45Html}

                    ${t.catatan ? `<div class="card card-outline card-warning mb-3">
                        <div class="card-header py-2">
                            <h6 class="mb-0"><i class="fas fa-sticky-note"></i> Catatan Teknisi</h6>
                        </div>
                        <div class="card-body p-3">
                            <p class="mb-0" style="white-space: pre-line;">${t.catatan}</p>
                        </div>
                    </div>` : ''}
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

    }).fail(function() {
        $('#detailBody').html('<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> Gagal memuat data tiket. Silakan coba lagi.</div>');
    });
}
</script>

@endpush