@extends('layouts.main')

@section('title', 'Analisis & Pola Tiket')

@push('styles')
<style>
.info-box {
    min-height: 90px;
}

.info-box-icon {
    width: 90px;
}

.chart-container {
    position: relative;
    height: 300px;
}

.accuracy-circle {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2em;
    font-weight: bold;
    margin: 0 auto;
}

.accuracy-high {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
}

.accuracy-medium {
    background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
    color: white;
}

.accuracy-low {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    color: white;
}
</style>
@endpush

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Analisis & Pola Tiket</h1>
                <small class="text-muted">Evaluasi akurasi prediksi C4.5 Decision Tree</small>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Analytics</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">

        {{-- Filter Section --}}
        <div class="row mb-3">
            <div class="col-12">
                <div class="card card-secondary collapsed-card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-filter"></i> Filter Data</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('tiket.analytics') }}" method="GET" id="filterForm">
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label><i class="fas fa-calendar"></i> Tanggal</label>
                                        <input type="text" name="filter_date" id="dateRangePicker" 
                                               class="form-control" 
                                               value="{{ $filterDate ?? '' }}"
                                               placeholder="Pilih rentang tanggal">
                                        <small class="text-muted">Kosongkan untuk semua tanggal</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><i class="fas fa-check-circle"></i> Status Prediksi</label>
                                        <select name="filter_match" class="form-control">
                                            <option value="">Semua</option>
                                            <option value="1" {{ $filterMatch == '1' ? 'selected' : '' }}>
                                                ✓ Match (Sesuai)
                                            </option>
                                            <option value="0" {{ $filterMatch == '0' ? 'selected' : '' }}>
                                                ✗ Tidak Match
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label>&nbsp;</label>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-block">
                                            <i class="fas fa-search"></i> Filter
                                        </button>
                                        <a href="{{ route('tiket.analytics') }}" class="btn btn-secondary btn-block">
                                            <i class="fas fa-redo"></i> Reset
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Summary Cards --}}
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="info-box bg-gradient-info">
                    <span class="info-box-icon"><i class="fas fa-robot"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Tiket</span>
                        <span class="info-box-number">{{ $total }}</span>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="info-box bg-gradient-success">
                    <span class="info-box-icon"><i class="fas fa-check-circle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Prediksi Match</span>
                        <span class="info-box-number">{{ $byUrgency->sum('correct') }}</span>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="info-box bg-gradient-warning">
                    <span class="info-box-icon"><i class="fas fa-times-circle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Tidak Match</span>
                        <span class="info-box-number">{{ $total - $byUrgency->sum('correct') }}</span>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="info-box bg-gradient-primary">
                    <span class="info-box-icon"><i class="fas fa-percentage"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Akurasi</span>
                        <span class="info-box-number">{{ number_format($accuracy, 1) }}%</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Akurasi & Chart --}}
        <div class="row">
            {{-- Overall Accuracy --}}
            <div class="col-md-4">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-bullseye"></i> Akurasi Keseluruhan</h3>
                    </div>
                    <div class="card-body text-center">
                        @php
                            $accuracyClass = 'accuracy-high';
                            if ($accuracy < 50) $accuracyClass = 'accuracy-low';
                            elseif ($accuracy < 75) $accuracyClass = 'accuracy-medium';
                        @endphp
                        <div class="accuracy-circle {{ $accuracyClass }} mb-3">
                            {{ number_format($accuracy, 1) }}%
                        </div>
                        <p class="text-muted mb-2">
                            <strong>{{ $byUrgency->sum('correct') }}</strong> dari <strong>{{ $total }}</strong> tiket match
                        </p>
                        <div class="progress mb-2">
                            <div class="progress-bar bg-success" style="width: {{ $accuracy }}%"></div>
                        </div>
                        <small class="text-muted">
                            @if($accuracy >= 75)
                                <i class="fas fa-check-circle text-success"></i> Model bekerja dengan baik
                            @elseif($accuracy >= 50)
                                <i class="fas fa-exclamation-triangle text-warning"></i> Model perlu perbaikan
                            @else
                                <i class="fas fa-times-circle text-danger"></i> Model perlu training ulang
                            @endif
                        </small>
                    </div>
                </div>
            </div>

            {{-- Akurasi per Urgency --}}
            <div class="col-md-8">
                <div class="card card-success card-outline">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-chart-bar"></i> Akurasi per Level Urgency</h3>
                    </div>
                    <div class="card-body">
                        @forelse($byUrgency as $item)
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="font-weight-bold">{{ $item->urgency }}</span>
                                    <span class="text-muted">
                                        {{ $item->correct }}/{{ $item->total }} 
                                        (<strong>{{ number_format($item->accuracy, 1) }}%</strong>)
                                    </span>
                                </div>
                                <div class="progress" style="height: 25px;">
                                    @php
                                        $progressClass = 'bg-success';
                                        if ($item->accuracy < 50) $progressClass = 'bg-danger';
                                        elseif ($item->accuracy < 75) $progressClass = 'bg-warning';
                                    @endphp
                                    <div class="progress-bar {{ $progressClass }} progress-bar-striped" 
                                         style="width: {{ $item->accuracy }}%">
                                        {{ number_format($item->accuracy, 1) }}%
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> Belum ada data evaluasi
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabel Semua Evaluasi --}}
        <div class="row">
            <div class="col-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-table"></i> Semua Data Evaluasi</h3>
                        <div class="card-tools">
                            <span class="badge badge-info">{{ $evaluations->total() }} tiket</span>
                        </div>
                    </div>
                    <div class="card-body table-responsive p-0" style="max-height: 600px;">
                        <table class="table table-hover table-striped table-head-fixed text-nowrap">
                            <thead class="thead-light">
                                <tr>
                                    <th style="width: 50px;">No</th>
                                    <th>Tanggal</th>
                                    <th>Nomor Tiket</th>
                                    <th>Judul</th>
                                    <th>User</th>
                                    <th>Tipe Masalah</th>
                                    <th>Dept Terdampak</th>
                                    <th>Rekomendasi</th>
                                    <th>Aktual</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($evaluations as $index => $eval)
                                    <tr class="{{ $eval->is_match ? 'table-success' : 'table-warning' }}">
                                        <td>{{ $evaluations->firstItem() + $index }}</td>
                                        <td>
                                            <small>{{ \Carbon\Carbon::parse($eval->tanggal)->format('d/m/Y H:i') }}</small>
                                        </td>
                                        <td>
                                            <a href="{{ route('tiket.show', $eval->id) }}" class="text-primary font-weight-bold">
                                                {{ $eval->nomor }}
                                            </a>
                                        </td>
                                        <td>
                                            {{ \Illuminate\Support\Str::limit($eval->judul, 40) }}
                                        </td>
                                        <td>
                                            <small>{{ $eval->username }}</small>
                                        </td>
                                        <td>
                                            <span class="badge badge-secondary">{{ $eval->tipe_masalah ?? '-' }}</span>
                                        </td>
                                        <td>
                                            <small>{{ $eval->dept_terdampak ?? '-' }}</small>
                                        </td>
                                        <td>
                                            <span class="badge badge-info">{{ $eval->recommended }}</span>
                                        </td>
                                        <td>
                                            <span class="badge badge-primary">{{ $eval->actual }}</span>
                                        </td>
                                        <td>
                                            @if($eval->is_match)
                                                <span class="badge badge-success">
                                                    <i class="fas fa-check"></i> Match
                                                </span>
                                            @else
                                                <span class="badge badge-danger">
                                                    <i class="fas fa-times"></i> Tidak Match
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-4 text-muted">
                                            <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                            Belum ada data evaluasi
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        {{ $evaluations->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Info Cards --}}
        <div class="row">
            <div class="col-md-6">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-info-circle"></i> Tentang C4.5 Decision Tree</h3>
                    </div>
                    <div class="card-body">
                        <p>
                            Algoritma C4.5 digunakan untuk memprediksi tingkat urgency tiket berdasarkan:
                        </p>
                        <ul>
                            <li><strong>Kata Kunci:</strong> Analisis teks pada deskripsi masalah</li>
                            <li><strong>Tipe Masalah:</strong> Kategori masalah (hardware, software, network, dll)</li>
                            <li><strong>Departemen Terdampak:</strong> Seberapa luas dampak masalah</li>
                        </ul>
                        <p class="mb-0">
                            <small class="text-muted">
                                Model akan terus belajar dari data historis untuk meningkatkan akurasi prediksi.
                            </small>
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card card-warning card-outline">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-lightbulb"></i> Rekomendasi</h3>
                    </div>
                    <div class="card-body">
                        @if($accuracy >= 75)
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle"></i> 
                                <strong>Model bekerja dengan baik!</strong><br>
                                Akurasi di atas 75% menunjukkan prediksi yang reliable.
                            </div>
                        @elseif($accuracy >= 50)
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i> 
                                <strong>Model perlu perbaikan</strong><br>
                                Pertimbangkan untuk menambah data training atau review fitur yang digunakan.
                            </div>
                        @else
                            <div class="alert alert-danger">
                                <i class="fas fa-times-circle"></i> 
                                <strong>Model perlu training ulang</strong><br>
                                Akurasi di bawah 50% memerlukan review menyeluruh pada model dan data.
                            </div>
                        @endif
                        
                        <p class="mb-0">
                            <small>
                                <i class="fas fa-chart-line"></i> 
                                Total <strong>{{ $total }}</strong> tiket telah dievaluasi
                            </small>
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

@endsection

@push('scripts')
<!-- Daterangepicker -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script>
$(function() {
    // Initialize daterangepicker
    $('#dateRangePicker').daterangepicker({
        autoUpdateInput: false,
        locale: {
            cancelLabel: 'Clear',
            format: 'YYYY-MM-DD',
            applyLabel: 'Terapkan',
            cancelLabel: 'Hapus',
            daysOfWeek: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
            monthNames: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember']
        }
    });

    $('#dateRangePicker').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('YYYY-MM-DD') + ' to ' + picker.endDate.format('YYYY-MM-DD'));
    });

    $('#dateRangePicker').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });
});
</script>
@endpush

