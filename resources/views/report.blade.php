@extends('user_navbar')
@section('content')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row"></div>
        <div class="content-body">

            <div class="card mb-3">
                <div class="card-body">
                    <form method="GET" action="{{ route('report.fetch') }}" class="row g-3 align-items-end">
                        <div class="col-md-2">
                            <label class="form-label">Start Date</label>
                            <input type="date" name="start_date" class="form-control"
                                value="{{ $filters['start_date'] ?? '' }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">End Date</label>
                            <input type="date" name="end_date" class="form-control"
                                value="{{ $filters['end_date'] ?? '' }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Availability</label>
                            <select name="availability" class="form-control">
                                <option value="">-- Select --</option>
                                <option value="Available" {{ ($filters['availability'] ?? '' )==='Available'
                                    ? 'selected' : '' }}>Available</option>
                                <option value="Pending" {{ ($filters['availability'] ?? '' )==='Pending' ? 'selected'
                                    : '' }}>Pending</option>
                                <option value="Sold" {{ ($filters['availability'] ?? '' )==='Sold' ? 'selected' : '' }}>
                                    Sold</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Company</label>
                            <select name="company_id" class="form-control">
                                <option value="">-- Select Company --</option>
                                @foreach($company as $c)
                                <option value="{{ $c->id }}" {{ (string)($filters['company_id'] ?? '' )===(string)$c->id
                                    ? 'selected':'' }}>{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Group</label>
                            <select name="group_id" class="form-control">
                                <option value="">-- Select Group --</option>
                                @foreach($group as $g)
                                <option value="{{ $g->id }}" {{ (string)($filters['group_id'] ?? '' )===(string)$g->id ?
                                    'selected':'' }}>{{ $g->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-1">
                            <label class="form-label">Per page</label>
                            <select name="per_page" class="form-control">
                                @foreach([25,50,100,200] as $n)
                                <option value="{{ $n }}" {{ (int)($filters['per_page'] ?? 25)===$n ? 'selected' :'' }}>
                                    {{ $n }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-3 mt-1">
                            <button class="btn btn-primary w-100">Generate Report</button>
                        </div>
                    </form>
                </div>
            </div>

            @isset($summary)
            <div class="alert alert-light border">
                <strong>{{ $summary['label'] }}:</strong>
                <span>Rs. {{ number_format($summary['value'] ?? 0, 0) }}</span>
            </div>
            @endisset

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Custom Report</h4>
                    @if($mobiles->total() > 0)
                    <span class="text-muted small">Total results: {{ number_format($mobiles->total()) }}</span>
                    @endif
                </div>

                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Added at</th>
                                <th>Sold at</th>
                                <th>Mobile Name</th>
                                <th>Company</th>
                                <th>Group</th>
                                <th>Vendor Name</th>
                                <th>Customer Name</th>
                                <th>IMEI#</th>
                                <th>SIM Lock</th>
                                <th>Color</th>
                                <th>Storage</th>
                                <th>Battery Health</th>
                                <th>Cost Price</th>
                                <th>Selling Price</th>
                                <th>Availability</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($mobiles as $m)
                            <tr>
                                <td>{{ optional($m->created_at)->format('Y-m-d H:i') }}</td>
                                <td>{{ $m->sold_at ? \Carbon\Carbon::parse($m->sold_at)->format('Y-m-d H:i') : '-' }}
                                </td>
                                <td>{{ $m->mobile_name }}</td>
                                <td>{{ optional($m->company)->name ?? '-' }}</td>
                                <td>{{ optional($m->group)->name ?? '-' }}</td>
                                <td>
                                    {{ optional($m->vendor)->name
                                    ?? optional($m->soldVendor)->name
                                    ?? optional(optional($m->latestVendorTransaction)->vendor)->name
                                    ?? '-' }}
                                </td>
                                <td>
                                    {{ optional($m->latestSaleTransaction)->customer_name
                                    ?? optional(optional($m->latestSaleTransaction)->vendor)->name
                                    ?? '-' }}
                                </td>
                                <td>{{ $m->imei_number }}</td>
                                <td>{{ $m->sim_lock }}</td>
                                <td>{{ $m->color }}</td>
                                <td>{{ $m->storage }}</td>
                                <td>{{ $m->battery_health ?? '-' }}</td>
                                <td>Rs. {{ number_format($m->cost_price ?? 0, 0) }}</td>
                                <td>Rs. {{ number_format($m->selling_price ?? 0, 0) }}</td>
                                <td>{{ $m->availability }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="15" class="text-center text-muted py-4">No data found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="card-body d-flex justify-content-between align-items-center">
                    <div class="small text-muted">
                        @if($mobiles->total() > 0)
                        Showing <strong>{{ $mobiles->firstItem() }}</strong>–<strong>{{ $mobiles->lastItem() }}</strong>
                        of <strong>{{ $mobiles->total() }}</strong>
                        @endif
                    </div>
                    {{ $mobiles->onEachSide(1)->links('pagination::bootstrap-4') }}
                </div>
            </div>

        </div>
    </div>
</div>
@endsection