@extends('layouts.admin')

@section('page_title', 'Admin Dashboard')

@section('content')
<div class="row align-items-center mb-4">
    <div class="col-md-6">
        <h5 class="fw-bold text-brand-dark mb-0">System Performance Metrics</h5>
        <p class="text-muted small mb-0">
            Viewing data for {{ date('F', mktime(0, 0, 0, $params['month'], 1)) }} {{ $params['year'] }}
        </p>
    </div>

    <div class="col-md-6">
        <form action="{{ route('admin.dashboard') }}" method="GET"
              class="d-flex justify-content-md-end gap-2 align-items-center flex-wrap">

            <!-- Month -->
            <select name="month"
                class="form-select form-select-sm shadow-sm rounded-3 chart-sparkline-lg">
                @foreach(range(1, 12) as $m)
                    <option value="{{ sprintf('%02d', $m) }}"
                        {{ $params['month'] == sprintf('%02d', $m) ? 'selected' : '' }}>
                        {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                    </option>
                @endforeach
            </select>

            <!-- Year -->
            <select name="year"
                class="form-select form-select-sm shadow-sm rounded-3 chart-sparkline-md">
                @php
                    $startYear = date('Y') - 1;
                    $endYear = date('Y') + 1;
                @endphp
                @foreach(range($startYear, $endYear) as $y)
                    <option value="{{ $y }}" {{ $params['year'] == $y ? 'selected' : '' }}>
                        {{ $y }}
                    </option>
                @endforeach
            </select>

            <!-- Button -->
            <button type="submit" class="btn btn-brand btn-sm px-3 rounded-pill no-loader">
                Filter
            </button>

        </form>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card card-premium p-4 h-100">
            <div class="d-flex align-items-center mb-2">
                <div class="bg-opacity-10 p-2 rounded-3 me-3">
                    <i class="bi bi-bank fs-5"></i>
                </div>
                <h6 class="text-muted mb-0 small fw-bold text-uppercase">Platform Volume</h6>
            </div>
            <h3 class="fw-bold mb-0">CHF {{ number_format($stats['total_chf'], 0) }}</h3>
            <p class="text-muted small mt-2 mb-0">Total volume this period</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-premium p-4 h-100">
            <div class="d-flex align-items-center mb-2">
                <div class="bg-opacity-10 p-2 rounded-3 me-3">
                    <i class="bi bi-graph-up-arrow fs-5"></i>
                </div>
                <h6 class="text-muted mb-0 small fw-bold text-uppercase">Platform Earnings</h6>
            </div>
            <h3 class="fw-bold mb-0">CHF {{ number_format($stats['admin_commissions'], 2) }}</h3>
            <p class="text-muted small mt-2 mb-0">Net platform profit</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-premium p-4 h-100">
            <div class="d-flex align-items-center mb-2">
                <div class="bg-opacity-10 p-2 rounded-3 me-3">
                    <i class="bi bi-people fs-5"></i>
                </div>
                <h6 class="text-muted mb-0 small fw-bold text-uppercase">Total Customers</h6>
            </div>
            <h3 class="fw-bold mb-0">{{ number_format($totalCustomers) }}</h3>
            <p class="text-muted small mt-2 mb-0">Registered user base</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-premium p-4 h-100">
            <div class="d-flex align-items-center mb-2">
                <div class="bg-opacity-10 p-2 rounded-3 me-3">
                    <i class="bi bi-arrow-left-right fs-5"></i>
                </div>
                <h6 class="text-muted mb-0 small fw-bold text-uppercase">Total Transfers</h6>
            </div>
            <h3 class="fw-bold mb-0">{{ $stats['total'] }}</h3>
            <p class="text-muted small mt-2 mb-0">Successful transactions</p>
        </div>
    </div>
</div>

{{-- Commission Breakdown Cards --}}
<div class="row g-4 mb-4">
    <div class="col-12">
        <h6 class="fw-bold text-brand-dark mb-3"><i class="bi bi-bar-chart-line-fill me-2 text-brand-dark"></i> Commission Breakdown</h6>
    </div>
    <div class="col-md-4">
        <div class="card card-premium p-4 h-100 card-accent-dark">
            <div class="d-flex align-items-center mb-2">
                <div class="bg-primary bg-opacity-10 p-2 rounded-3 me-3 text-primary">
                    <i class="bi bi-person-workspace fs-5"></i>
                </div>
                <h6 class="text-muted mb-0 small fw-bold">TOTAL AGENT EARNINGS (60%)</h6>
            </div>
            <h3 class="fw-bold mb-0 text-brand-dark">CHF {{ number_format($stats['agent_commissions'] ?? 0, 2) }}</h3>
            <p class="text-muted small mt-2 mb-0">Sum of agent_commission</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-premium p-4 h-100 card-accent-lime">
            <div class="d-flex align-items-center mb-2">
                <div class="bg-success bg-opacity-10 p-2 rounded-3 me-3 text-success">
                    <i class="bi bi-building fs-5"></i>
                </div>
                <h6 class="text-muted mb-0 small fw-bold">PLATFORM FROM AGENT (40%)</h6>
            </div>
            <h3 class="fw-bold mb-0 text-success">CHF {{ number_format($stats['platform_from_agent'] ?? 0, 2) }}</h3>
            <p class="text-muted small mt-2 mb-0">Platform 40% share</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-premium p-4 h-100 card-accent-lime">
            <div class="d-flex align-items-center mb-2">
                <div class="bg-warning bg-opacity-10 p-2 rounded-3 me-3 text-warning">
                    <i class="bi bi-people-fill fs-5"></i>
                </div>
                <h6 class="text-muted mb-0 small fw-bold">PLATFORM DIRECT (100%)</h6>
            </div>
            <h3 class="fw-bold mb-0 text-warning">CHF {{ number_format($stats['platform_from_customer'] ?? 0, 2) }}</h3>
            <p class="text-muted small mt-2 mb-0">100% platform earnings</p>
        </div>
    </div>
</div>


<!-- Charts Section -->
<div class="row g-4 mb-4">
    <div class="col-md-8">
        <div class="card card-premium border-0 shadow-sm p-4 h-100">
            <h6 class="fw-bold text-brand-dark mb-4">Volume & Commissions Trend</h6>
            <div id="volumeChart" class="chart-container"></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-premium border-0 shadow-sm p-4 h-100">
            <h6 class="fw-bold text-brand-dark mb-4">Transaction Distribution</h6>
            <div id="statusChart" class="chart-container"></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="table-premium-container">
            <div class="d-flex justify-content-between align-items-center mb-4 px-3">
                <h5 class="mb-0 fw-bold text-brand-dark">Recent System Activity</h5>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.transactions.index') }}" class="btn btn-light btn-sm px-3 rounded-pill">View All</a>
                    @if($pendingAgents > 0)
                        <a href="{{ route('admin.agents.pending') }}" class="btn btn-outline-warning btn-sm px-3 rounded-pill no-loader">
                            <i class="bi bi-person-plus me-1"></i> Pending Agents: {{ $pendingAgents }}
                        </a>
                    @endif
                    @if($pendingCompliance > 0)
                        <a href="{{ route('admin.compliance.index') }}" class="btn btn-outline-danger btn-sm px-3 rounded-pill no-loader">
                            <i class="bi bi-exclamation-octagon me-1"></i> Reviews Needed: {{ $pendingCompliance }}
                        </a>
                    @endif
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle table-premium">
                    <thead>
                        <tr>
                            <th class="border-0 small fw-bold">Agent</th>
                            <th class="border-0 small fw-bold">Customer</th>
                            <th class="border-0 small fw-bold text-end">Actual Send</th>
                            <th class="border-0 small fw-bold text-end">Commission</th>
                            <th class="border-0 small fw-bold text-end">Total Paid</th>
                            <th class="border-0 small fw-bold text-center">Status</th>
                            <th class="border-0 small fw-bold">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentTransactions as $tx)
                            <tr>
                                <td>
                                    @if($tx->agent)
                                        <div class="fw-bold">{{ $tx->agent->name }}</div>
                                        <div class="small text-muted">{{ $tx->agent->email }}</div>
                                    @else
                                        <div class="fw-bold text-primary">Direct Customer</div>
                                        <div class="small text-muted">No Agent involved</div>
                                    @endif
                                </td>
                                <td>{{ $tx->customer->name ?? 'N/A' }}</td>
                                <td class="text-end fw-bold text-brand-dark">{{ number_format($tx->send_amount, 2) }} CHF</td>
                                <td class="text-end">
                                    <div class="fw-bold text-success">{{ number_format($tx->commission, 2) }} CHF</div>
                                    <div class="small text-muted user-info-text">A: {{ number_format($tx->agent_commission, 2) }} | P: {{ number_format($tx->admin_commission, 2) }}</div>
                                </td>
                                <td class="text-end fw-bold text-primary">{{ number_format($tx->chf_amount, 2) }} CHF</td>
                                <td class="text-center">
                                    <span class="status-pill status-{{ $tx->status }}">{{ ucfirst($tx->status) }}</span>
                                </td>
                                <td class="small text-muted">{{ $tx->created_at->format('M d, H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">No transaction activity found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const chartData = @json($stats['chart_data']);
        
        // Volume Chart
        var volumeOptions = {
            series: [{
                name: 'Volume (CHF)',
                type: 'bar',
                data: chartData.volume
            }, {
                name: 'Commissions (CHF)',
                type: 'bar',
                data: chartData.commissions
            }],
            chart: {
                height: 350,
                type: 'bar',
                toolbar: { show: false },
                fontFamily: 'Inter, sans-serif',
                stacked: false
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    borderRadius: 4
                },
            },
            colors: ['#ffde42', '#142472'],
            dataLabels: { enabled: false },
            stroke: { show: true, width: 2, colors: ['transparent'] },
            labels: chartData.labels,
            xaxis: { type: 'datetime' },
            yaxis: [
                {
                    title: { text: 'Volume (CHF)' },
                    labels: { style: { colors: '#142472' } }
                },
                {
                    opposite: true,
                    title: { text: 'Commission (CHF)' },
                    labels: { style: { colors: '#142472' } }
                }
            ],
            tooltip: { shared: true, intersect: false }
        };

        var volumeChart = new ApexCharts(document.querySelector("#volumeChart"), volumeOptions);
        volumeChart.render();

        // Status Chart (Donut)
        var statusOptions = {
            series: [{{ $stats['completed'] }}, {{ $stats['pending'] }}, {{ $stats['failed'] }}],
            chart: {
                type: 'donut',
                height: 350
            },
            labels: ['Completed', 'Pending', 'Failed'],
            colors: ['#D3FF8A', '#FFB020', '#FF4842'],
            legend: { position: 'bottom' },
            plotOptions: {
                pie: {
                    donut: {
                        size: '70%',
                        labels: {
                             show: true,
                             total: {
                                 show: true,
                                 label: 'TOTAL',
                                 formatter: function (w) { return {{ $stats['total'] }} }
                             }
                        }
                    }
                }
            }
        };

        var statusChart = new ApexCharts(document.querySelector("#statusChart"), statusOptions);
        statusChart.render();
    });
</script>
@endpush
