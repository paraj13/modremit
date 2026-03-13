@extends('layouts.agent')

@section('page_title', 'Agent Dashboard')

@section('content')
<div class="row align-items-center mb-4">
    <div class="col-md-6">
        <h5 class="fw-bold text-brand-dark mb-0">Performance Overview</h5>
        <p class="text-muted small mb-0">
            Viewing data for {{ date('F', mktime(0, 0, 0, $params['month'], 1)) }} {{ $params['year'] }}
        </p>
    </div>

    <div class="col-md-6">
        <form action="{{ route('agent.dashboard') }}" method="GET"
              class="d-flex justify-content-md-end align-items-center gap-2 flex-wrap">

            <!-- Month -->
            <select name="month"
                class="form-select form-select-sm shadow-sm rounded-3"
                style="width:140px;">
                @foreach(range(1, 12) as $m)
                    <option value="{{ sprintf('%02d', $m) }}"
                        {{ $params['month'] == sprintf('%02d', $m) ? 'selected' : '' }}>
                        {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                    </option>
                @endforeach
            </select>

            <!-- Year -->
            <select name="year"
                class="form-select form-select-sm shadow-sm rounded-3"
                style="width:110px;">
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
        <div class="card card-premium p-4 border-0 shadow-sm h-100">
            <div class="d-flex align-items-center mb-2">
                <div class="bg-primary bg-opacity-10 p-2 rounded-3 text-primary me-3">
                    <i class="bi bi-arrow-left-right fs-5"></i>
                </div>
                <h6 class="text-muted mb-0 small fw-bold">TOTAL TRANSFERS</h6>
            </div>
            <h3 class="fw-bold mb-0 text-brand-dark">{{ $stats['total'] }}</h3>
            <p class="text-muted small mt-2 mb-0">This period</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-premium p-4 border-0 shadow-sm h-100">
            <div class="d-flex align-items-center mb-2">
                <div class="bg-success bg-opacity-10 p-2 rounded-3 text-success me-3">
                    <i class="bi bi-wallet2 fs-5"></i>
                </div>
                <h6 class="text-muted mb-0 small fw-bold">CHF RECEIVED</h6>
            </div>
            <h3 class="fw-bold mb-0 text-brand-dark">{{ number_format($stats['total_chf'], 2) }}</h3>
            <p class="text-muted small mt-2 mb-0">From customers</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-premium p-4 border-0 shadow-sm h-100">
            <div class="d-flex align-items-center mb-2">
                <div class="bg-info bg-opacity-10 p-2 rounded-3 text-info me-3">
                    <i class="bi bi-graph-up-arrow fs-5"></i>
                </div>
                <h6 class="text-muted mb-0 small fw-bold">COMMISSION EARNED</h6>
            </div>
            <h3 class="fw-bold mb-0 text-success">{{ number_format($stats['agent_commission'], 2) }} CHF</h3>
            <p class="text-muted small mt-2 mb-0">Your earnings</p>
        </div>
    </div>
    <div class="col-md-3">
        <a href="{{ route('agent.wallet.index') }}" class="text-decoration-none">
            <div class="card card-premium p-4 border-0 shadow-sm h-100 bg-brand-dark text-white border-brand-lime border-opacity-25 border">
                <div class="d-flex align-items-center mb-2">
                    <div class="bg-white bg-opacity-10 p-2 rounded-3 text-brand-lime me-3">
                        <i class="bi bi-bank fs-5"></i>
                    </div>
                    <h6 class="text-brand-lime mb-0 small fw-bold">WALLET BALANCE</h6>
                </div>
                <h3 class="fw-bold mb-0">{{ number_format($wallet->chf_balance, 2) }} CHF</h3>
                <p class="text-white-50 small mt-2 mb-0">Available for transfers</p>
            </div>
        </a>
    </div>
</div>

<!-- Charts Section -->
<div class="row g-4 mb-4">
    <div class="col-md-8">
        <div class="card card-premium border-0 shadow-sm p-4 h-100">
            <h6 class="fw-bold text-brand-dark mb-4">My Performance Trend</h6>
            <div id="performanceChart" style="min-height: 300px;"></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-premium border-0 shadow-sm p-4 h-100">
            <h5 class="mb-4 fw-bold text-brand-dark">Wallet Summary</h5>
            <div class="space-y-4">
                <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                    <span class="text-muted uppercase small fw-bold">Total Received</span>
                    <span class="fw-bold text-brand-dark">{{ number_format($wallet->total_received, 2) }} CHF</span>
                </div>
                <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                    <span class="text-muted uppercase small fw-bold">Total Commissions</span>
                    <span class="fw-bold text-success">{{ number_format($wallet->total_commission, 2) }} CHF</span>
                </div>
                <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                    <span class="text-muted">Total Customers</span>
                    <span class="fw-bold text-brand-dark">{{ $customerCount }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center py-3">
                    <span class="text-muted text-uppercase small fw-bold">Success Rate</span>
                    @php
                        $rate = $stats['total'] > 0 ? ($stats['completed'] / $stats['total']) * 100 : 0;
                    @endphp
                    <span class="badge bg-brand-mint text-brand-dark px-3">{{ round($rate, 1) }}%</span>
                </div>
            </div>
            <div class="mt-auto pt-4">
                <a href="{{ route('agent.wallet.topup') }}" class="btn btn-brand w-100 py-2">
                    <i class="bi bi-plus-lg me-1"></i> Top-up Wallet
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="table-premium-container">
            <div class="d-flex justify-content-between align-items-center mb-4 px-3">
                <h5 class="mb-0 fw-bold text-brand-dark">Recent Activity</h5>
                <a href="{{ route('agent.transfers.create') }}" class="btn btn-brand-outline btn-sm px-4 rounded-pill">
                    <i class="bi bi-plus-lg me-1"></i> New Transfer
                </a>
            </div>
            
            @if($recentTransactions->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle table-premium">
                        <thead>
                            <tr>
                                <th class="border-0 small fw-bold ps-3">Customer</th>
                                <th class="border-0 small fw-bold">Beneficiary</th>
                                <th class="border-0 small fw-bold text-end">Send Amount</th>
                                <th class="border-0 small fw-bold text-end">Commission</th>
                                <th class="border-0 small fw-bold text-center">Status</th>
                                <th class="border-0 small fw-bold text-end pe-3">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentTransactions as $tx)
                                <tr>
                                    <td class="ps-3">
                                        <div class="fw-bold">{{ $tx->customer->name }}</div>
                                        <div class="small text-muted">{{ $tx->customer->email }}</div>
                                    </td>
                                    <td>{{ $tx->recipient->name }}</td>
                                    <td class="text-end fw-bold text-brand-dark">{{ number_format($tx->send_amount, 2) }} CHF</td>
                                    <td class="text-end text-success fw-bold">{{ number_format($tx->agent_commission, 2) }} CHF</td>
                                    <td class="text-center">
                                        <span class="badge bg-{{ $tx->status_badge }} px-3 rounded-pill">{{ ucfirst($tx->status) }}</span>
                                    </td>
                                    <td class="text-end pe-3 small text-muted">{{ $tx->created_at->format('M d, H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <p class="text-muted mb-0">No transactions found for this period.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const chartData = @json($stats['chart_data']);
        
        var options = {
            series: [{
                name: 'Volume (CHF)',
                data: chartData.volume
            }, {
                name: 'Commission (CHF)',
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
            colors: ['#D3FF8A', '#25330F'],
            dataLabels: { enabled: false },
            stroke: { show: true, width: 2, colors: ['transparent'] },
            xaxis: {
                type: 'datetime',
                categories: chartData.labels
            },
            yaxis: [
                {
                    title: { text: 'Volume (CHF)' },
                    seriesName: 'Volume (CHF)'
                },
                {
                    opposite: true,
                    title: { text: 'Commission (CHF)' },
                    seriesName: 'Commission (CHF)'
                }
            ],
            tooltip: {
                x: { format: 'dd MMM yyyy' }
            }
        };

        var chart = new ApexCharts(document.querySelector("#performanceChart"), options);
        chart.render();
    });
</script>
@endpush
