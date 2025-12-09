@extends('layouts.main')

@section('page-title', 'Dashboard')

@push('styles')
<style>
/* Dashboard Styles */
.info-box {
    min-height: 90px;
    transition: all 0.3s ease;
}

.info-box:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}

.small-box {
    border-radius: 8px;
    transition: all 0.3s ease;
}

.small-box:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.15);
}

.sla-indicator {
    display: inline-block;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    margin-right: 5px;
    animation: pulse 2s infinite;
}

.sla-safe { background: #28a745; }
.sla-warning { background: #ffc107; }
.sla-danger { background: #dc3545; }

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

.sla-timer {
    font-family: 'Courier New', monospace;
    font-weight: bold;
    font-size: 0.9rem;
}

.sla-timer.danger { color: #dc3545; }
.sla-timer.warning { color: #ffc107; }
.sla-timer.safe { color: #28a745; }

.card-header {
    background: linear-gradient(to right, #f8f9fa, #ffffff);
    border-bottom: 2px solid #007bff;
}

.badge {
    font-size: 0.8rem;
    padding: 0.35em 0.6em;
}

/* Timeline Styles untuk Modal Detail */
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

/* Modal max height */
.modal-body-scroll {
    max-height: 70vh;
    overflow-y: auto;
}

/* Icon background colors */
.bg-primary { background-color: #007bff !important; }
.bg-info { background-color: #17a2b8 !important; }
.bg-success { background-color: #28a745 !important; }
.bg-danger { background-color: #dc3545 !important; }
.bg-dark { background-color: #343a40 !important; }

@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.85rem;
    }
    .small-box .inner h3 {
        font-size: 1.5rem;
    }
}
</style>
@endpush

@section('content')

@php
    $role = Auth::user()->role ?? 'user';
@endphp

@if(Auth::user()->role === 'administrator')
    {{-- ========================================
        SLA OVERVIEW CARDS
        ======================================== --}}
    <div class="row">
        
        <div class="col-lg-3 col-md-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $totalTiket }}</h3>
                    <p>Total Tiket</p>
                </div>
                <div class="icon">
                    <i class="fas fa-ticket-alt"></i>
                </div>
                <a href="{{ route('tiket.index') }}" class="small-box-footer">
                    Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $slaCompliance }}%</h3>
                    <p>SLA Compliance</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <a href="#sla-section" class="small-box-footer">
                    On-Time Resolution <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $tiketAtRisk }}</h3>
                    <p>Tiket At-Risk</p>
                </div>
                <div class="icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <a href="#at-risk-section" class="small-box-footer">
                    Perlu Perhatian <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $tiketBreached }}</h3>
                    <p>SLA Breached</p>
                </div>
                <div class="icon">
                    <i class="fas fa-times-circle"></i>
                </div>
                <a href="#breached-section" class="small-box-footer">
                    Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

    </div>
@endif

{{-- ========================================
    STATUS CARDS (Semua Role)
    ======================================== --}}
<div class="row">
    
    <div class="col-lg-3 col-6">
        <div class="info-box">
            <span class="info-box-icon bg-primary elevation-1">
                <i class="fas fa-inbox"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Tiket Baru</span>
                <span class="info-box-number">{{ $tiketOpen }}</span>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="info-box">
            <span class="info-box-icon bg-warning elevation-1">
                <i class="fas fa-spinner"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Dalam Progress</span>
                <span class="info-box-number">{{ $tiketProgress }}</span>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="info-box">
            <span class="info-box-icon bg-success elevation-1">
                <i class="fas fa-check-circle"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Selesai</span>
                <span class="info-box-number">{{ $tiketFinish }}</span>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="info-box">
            <span class="info-box-icon bg-secondary elevation-1">
                <i class="fas fa-lock"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Closed</span>
                <span class="info-box-number">{{ $tiketClosed }}</span>
            </div>
        </div>
    </div>

</div>

{{-- ========================================
    TIKET TERBARU (Semua Role)
    ======================================== --}}
<div class="row">
    <div class="col-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-list"></i>
                    Tiket Terbaru
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>No. Tiket</th>
                                @if(Auth::user()->role === 'administrator' || Auth::user()->role === 'admin')
                                    <th>User</th>
                                @endif
                                <th>Judul</th>
                                <th>Status</th>
                                @if(Auth::user()->role === 'administrator')
                                    <th>Departemen</th>
                                @endif
                                @if(Auth::user()->role === 'user')
                                    <th>Teknisi</th>
                                @endif
                                <th>Dibuat</th>
                                <th style="width: 100px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tiketTerbaru as $index => $tiket)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <strong>TKT{{ str_pad($tiket->id, 3, '0', STR_PAD_LEFT) }}</strong>
                                </td>
                                @if(Auth::user()->role === 'administrator' || Auth::user()->role === 'admin')
                                    <td>{{ $tiket->user->nama ?? '-' }}</td>
                                @endif
                                <td>{{ Str::limit($tiket->judul, 50) }}</td>
                                <td>
                                    @switch($tiket->status)
                                        @case('open') 
                                            <span class="badge badge-primary">Baru</span> 
                                        @break
                                        @case('progress') 
                                            <span class="badge badge-warning">Progress</span> 
                                        @break
                                        @case('finish') 
                                            <span class="badge badge-success">Selesai</span> 
                                        @break
                                        @case('closed') 
                                            <span class="badge badge-secondary">Closed</span> 
                                        @break
                                        @default 
                                            <span class="badge badge-info">Pending</span>
                                    @endswitch
                                </td>
                                @if(Auth::user()->role === 'administrator')
                                    <td>
                                        <span class="badge badge-info">
                                            {{ $tiket->departemen->nama_departemen ?? '-' }}
                                        </span>
                                    </td>
                                @endif
                                @if(Auth::user()->role === 'user')
                                    <td>{{ $tiket->teknisi->nama ?? 'Belum ditugaskan' }}</td>
                                @endif
                                <td>
                                    <small class="text-muted">
                                        <i class="far fa-clock"></i>
                                        {{ $tiket->created_at->diffForHumans() }}
                                    </small>
                                </td>
                                <td>
                                    <button type="button" 
                                       class="btn btn-xs btn-info"
                                       onclick="showDetail({{ $tiket->id }})"
                                       title="Lihat Detail">
                                        <i class="fas fa-eye"></i> Detail
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    <i class="fas fa-inbox"></i>
                                    Belum ada tiket
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($tiketTerbaru->count() >= 10)
            <div class="card-footer clearfix">
                <a href="{{ route('tiket.index') }}" class="btn btn-sm btn-info float-right">
                    <i class="fas fa-list"></i> Lihat Semua Tiket
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

@if(Auth::user()->role === 'administrator')
    {{-- ========================================
        SLA CHARTS
        ======================================== --}}
    <div class="row">
        
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-pie text-primary"></i>
                        SLA Performance
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    anvas id="slaChart" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-bar text-info"></i>
                        Distribusi Prioritas Tiket
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    anvas id="priorityChart" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>

    </div>

    {{-- ========================================
        TIKET AT-RISK
        ======================================== --}}
    <div class="row" id="at-risk-section">
        <div class="col-12">
            <div class="card card-warning card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-exclamation-triangle"></i>
                        Tiket At-Risk (Mendekati Deadline SLA)
                    </h3>
                    <div class="card-tools">
                        <span class="badge badge-warning">{{ $tiketAtRisk }} Tiket</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>No. Tiket</th>
                                    <th>Judul</th>
                                    <th>User</th>
                                    <th>Status</th>
                                    <th>Deadline SLA</th>
                                    <th>Sisa Waktu</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($atRiskTickets as $tiket)
                                <tr>
                                    <td>
                                        <strong class="text-warning">
                                            TKT{{ str_pad($tiket->id, 3, '0', STR_PAD_LEFT) }}
                                        </strong>
                                    </td>
                                    <td>{{ Str::limit($tiket->judul, 40) }}</td>
                                    <td>{{ $tiket->user->nama ?? '-' }}</td>
                                    <td>
                                        @switch($tiket->status)
                                            @case('open') 
                                                <span class="badge badge-primary">Baru</span> 
                                            @break
                                            @case('progress') 
                                                <span class="badge badge-warning">Progress</span> 
                                            @break
                                            @case('pending') 
                                                <span class="badge badge-info">Pending</span> 
                                            @break
                                        @endswitch
                                    </td>
                                    <td>
                                        @php
                                            $deadline = $tiket->created_at->copy()->addHours(24);
                                        @endphp
                                        <small class="text-muted">
                                            <i class="far fa-clock"></i>
                                            {{ $deadline->format('d/m/Y H:i') }}
                                        </small>
                                    </td>
                                    <td>
                                        @php
                                            $deadline = $tiket->created_at->copy()->addHours(24);
                                            $remaining = now()->diffInHours($deadline, false);
                                        @endphp
                                        <span class="sla-timer warning">
                                            <i class="fas fa-hourglass-half"></i>
                                            {{ round($remaining) }} jam
                                        </span>
                                    </td>
                                    <td>
                                        <button type="button" 
                                           class="btn btn-xs btn-warning"
                                           onclick="showDetail({{ $tiket->id }})">
                                            <i class="fas fa-eye"></i> Detail
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-3">
                                        <i class="fas fa-check-circle text-success"></i>
                                        Tidak ada tiket at-risk
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- TIKET BREACHED --}}
<div class="row" id="breached-section">
    <div class="col-12">
        <div class="card card-danger card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-times-circle"></i>
                    Tiket yang Melanggar SLA
                </h3>
                <div class="card-tools">
                    <span class="badge badge-danger">{{ $tiketBreached }} Tiket</span>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-sm">
                        <thead>
                            <tr>
                                <th>No. Tiket</th>
                                <th>Judul</th>
                                <th>User</th>
                                <th>Status</th>
                                <th>Deadline SLA</th>
                                <th>Overdue</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($breachedTickets as $tiket)
                            <tr>
                                <td>
                                    <strong class="text-danger">
                                        TKT{{ str_pad($tiket->id, 3, '0', STR_PAD_LEFT) }}
                                    </strong>
                                </td>
                                <td>{{ Str::limit($tiket->judul, 40) }}</td>
                                <td>{{ $tiket->user->nama ?? '-' }}</td>
                                <td>
                                    @switch($tiket->status)
                                        @case('open') 
                                            <span class="badge badge-primary">Baru</span> 
                                        @break
                                        @case('progress') 
                                            <span class="badge badge-warning">Progress</span> 
                                        @break
                                        @case('pending') 
                                            <span class="badge badge-info">Pending</span> 
                                        @break
                                    @endswitch
                                </td>
                                <td>
                                    @php
                                        $deadline = $tiket->created_at->copy()->addHours(24);
                                    @endphp
                                    <small class="text-muted">
                                        <i class="far fa-clock"></i>
                                        {{ $deadline->format('d/m/Y H:i') }}
                                    </small>
                                </td>
                                <td>
    @php
        $deadline = $tiket->created_at->copy()->addHours(24);
        $overdueHours = abs(floor(now()->diffInHours($deadline, false)));
        
        // Kalau >= 24 jam, tampilkan dalam hari saja (tanpa jam)
        if ($overdueHours >= 24) {
            $overdueDays = floor($overdueHours / 24);
            $overdueText = $overdueDays . ' hari';
        } else {
            $overdueText = $overdueHours . ' jam';
        }
    @endphp
    <span class="text-danger font-weight-bold">
        <i class="fas fa-exclamation-circle"></i>
        {{ $overdueText }}
    </span>
</td>

                                <td>
                                    <button type="button" onclick="showDetail({{ $tiket->id }})" 
                                       class="btn btn-xs btn-danger">
                                        <i class="fas fa-exclamation-triangle"></i> Urgent
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-3">
                                    <i class="fas fa-check-circle text-success"></i>
                                    Tidak ada pelanggaran SLA
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endif

{{-- ========================================
     MODAL DETAIL TIKET
     ======================================== --}}
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

@endsection

@push('scripts')
@if(Auth::user()->role === 'administrator')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

<script>
$(document).ready(function() {
    
    // ========================================
    // SLA Performance Chart
    // ========================================
    const slaCtx = document.getElementById('slaChart');
    if (slaCtx) {
        new Chart(slaCtx, {
            type: 'doughnut',
            data: {
                labels: ['On-Time', 'At-Risk', 'Breached'],
                datasets: [{
                    data: [{{ $tiketOnTime }}, {{ $tiketAtRisk }}, {{ $tiketBreached }}],
                    backgroundColor: ['#28a745', '#ffc107', '#dc3545'],
                    borderWidth: 3,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { 
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            font: {
                                size: 13
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                return `${label}: ${value} tiket (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    }

    // ========================================
    // Priority Distribution Chart
    // ========================================
    const priorityCtx = document.getElementById('priorityChart');
    if (priorityCtx) {
        new Chart(priorityCtx, {
            type: 'bar',
            data: {
                labels: ['High Priority', 'Medium Priority', 'Low Priority'],
                datasets: [{
                    label: 'Jumlah Tiket',
                    data: [{{ $priorityHigh }}, {{ $priorityMedium }}, {{ $priorityLow }}],
                    backgroundColor: [
                        'rgba(220, 53, 69, 0.8)',
                        'rgba(255, 193, 7, 0.8)',
                        'rgba(23, 162, 184, 0.8)'
                    ],
                    borderColor: ['#dc3545', '#ffc107', '#17a2b8'],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { 
                            stepSize: 5, 
                            precision: 0 
                        }
                    }
                },
                plugins: {
                    legend: { 
                        display: false 
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y + ' tiket';
                            }
                        }
                    }
                }
            }
        });
    }

    // ========================================
    // Smooth Scroll
    // ========================================
    $('a[href^="#"]').on('click', function(e) {
        const target = $(this.getAttribute('href'));
        if (target.length) {
            e.preventDefault();
            $('html, body').stop().animate({
                scrollTop: target.offset().top - 100
            }, 800);
        }
    });

});
</script>
@endif

{{-- SCRIPT UNTUK SEMUA ROLE --}}
<script>
// ========================================
// FUNCTION: SHOW DETAIL MODAL TIKET
// ========================================
function showDetail(id) {
    $('#detailBody').html('<div class="text-center py-5"><i class="fas fa-spinner fa-spin fa-3x text-muted"></i><p class="text-muted mt-3">Memuat data tiket...</p></div>');
    
    @if(in_array($role, ['admin','administrator']))
    $('#formAssign').attr('action', '{{ route("tiket.assign", ":id") }}'.replace(':id', id));
    $('#assignFormContainer').hide();
    $('#closeButtonContainer').show();
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

        // Rekomendasi C4.5
        let c45Html = '';
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
                    ${c45Html}

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
                            <h6 class="mb-0"><i class="fas fa-sticky-note"></i> Catatan</h6>
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
            $('#assignFormContainer').show();
            $('#closeButtonContainer').hide();
            
            // Pre-select rekomendasi urgency
            if (t.recommended_urgency_id) {
                $('#assign_urgency_id').val(t.recommended_urgency_id);
            }
        }
        @endif
    }).fail(function(xhr) {
        $('#detailBody').html('<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> Gagal memuat detail tiket. Silakan coba lagi.</div>');
    });
}
</script>
@endpush
