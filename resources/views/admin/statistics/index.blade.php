@extends('admin.layouts.app')

@section('title', 'Statistic — Admin')
@section('page_title', 'Statistic')
@section('page_subtitle', 'Ringkasan KPI dan grafik')

@section('content')
    <div class="row g-3">
        <div class="col-12">
            <div class="admin-card">
                <div class="admin-card-body d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <form method="get" action="{{ route('admin.statistics.index') }}" class="d-flex flex-wrap align-items-center gap-2">
                        <label class="fw-semibold mb-0" for="year">Tahun</label>
                        <select class="form-select" id="year" name="year" onchange="this.form.submit()" style="width: auto; min-width: 7rem;">
                            @for ($y = (int) now()->format('Y'); $y >= ((int) now()->format('Y') - 7); $y--)
                                <option value="{{ $y }}" @selected((int) $year === $y)>{{ $y }}</option>
                            @endfor
                        </select>
                    </form>
                    <span class="badge badge-soft-yellow">Chart.js</span>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="admin-card h-100">
                <div class="admin-card-body">
                    <div class="text-muted small">Total Orasi</div>
                    <div class="admin-kpi-value">{{ number_format($kpis['total_orasi']) }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="admin-card h-100">
                <div class="admin-card-body">
                    <div class="text-muted small">Guru Besar</div>
                    <div class="admin-kpi-value">{{ number_format($kpis['total_guru_besar']) }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="admin-card h-100">
                <div class="admin-card-body">
                    <div class="text-muted small">Fakultas</div>
                    <div class="admin-kpi-value">{{ number_format($kpis['fakultas_terlibat']) }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="admin-card h-100">
                <div class="admin-card-body">
                    <div class="text-muted small">Orasi {{ $year }}</div>
                    <div class="admin-kpi-value">{{ number_format($kpis['orasi_tahun_berjalan']) }}</div>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-8">
            <div class="admin-card h-100">
                <div class="admin-card-body">
                    <div class="fw-semibold mb-2">Tren Orasi per Bulan ({{ $year }})</div>
                    <div class="admin-chart-wrap">
                        <canvas id="trendChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-4">
            <div class="admin-card h-100">
                <div class="admin-card-body">
                    <div class="fw-semibold mb-2">Top Fakultas ({{ $year }})</div>
                    <div class="admin-chart-wrap" style="min-height: 260px;">
                        <canvas id="facultyChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            const brandYellow = '#F8B803';

            new Chart(document.getElementById('trendChart'), {
                type: 'line',
                data: {
                    labels: @json($trend['labels']),
                    datasets: [{
                        label: 'Orasi',
                        data: @json($trend['series']),
                        borderColor: brandYellow,
                        backgroundColor: 'rgba(248, 184, 3, .15)',
                        fill: true,
                        tension: .35,
                        pointRadius: 3,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
                }
            });

            new Chart(document.getElementById('facultyChart'), {
                type: 'doughnut',
                data: {
                    labels: @json($perFakultas->pluck('label')),
                    datasets: [{
                        data: @json($perFakultas->pluck('total')),
                        backgroundColor: ['#F8B803', '#111827', '#22C55E', '#3B82F6', '#A855F7', '#F97316', '#06B6D4', '#EF4444'],
                        borderWidth: 0,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '65%',
                    plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } } }
                }
            });
        </script>
    @endpush
@endsection
