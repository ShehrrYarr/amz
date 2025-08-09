@extends('user_navbar')
@section('content')

<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row"></div>
        <div class="content-body">

            <div class="container-fluid py-3">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h3 class="mb-0">All Sales</h3>
                    <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary btn-sm">Reset Filters</a>
                </div>

                {{-- Filters (optional) --}}
               <form class="row g-3 align-items-end mb-4" method="get" action="{{ route('sales.index') }}">
                    <div class="col-md-3">
                        <label class="form-label">Vendor</label>
                        <select name="vendor_id" id="vendor_id" class="form-select form-control">
                            <option value="">All</option>
                            @foreach($vendors as $v)
                            <option value="{{ $v->id }}" {{ request('vendor_id')==$v->id?'selected':'' }}>
                                {{ $v->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Sold By</label>
                        <select name="sold_by" class="form-select form-control">
                            <option value="">All</option>
                            @foreach($users as $u)
                            <option value="{{ $u->id }}" {{ request('sold_by')==$u->id?'selected':'' }}>
                                {{ $u->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">From</label>
                        <input type="date" name="from" value="{{ request('from') }}" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">To</label>
                        <input type="date" name="to" value="{{ request('to') }}" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary w-100">Filter</button>
                    </div>
                </form>

                <div class="card shadow">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered" id="sales-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Customer / Vendor</th>
                                        <th>Items</th>
                                        <th>Total</th>
                                        <th>Discount</th>
                                        <th>Grand Total</th>
                                        <th>Paid</th>
                                        <th>Balance</th>
                                        <th>Sold By</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sales as $sale)
                                    <tr>
                                        <td>{{ $sale->id }}</td>
                                        <td>{{ $sale->created_at->format('d M Y, H:i') }}</td>
                                        <td>
                                            @if($sale->vendor)
                                            <span class="badge bg-info">Vendor</span> {{ $sale->vendor->name }}
                                            @elseif($sale->customer_name)
                                            <span class="badge bg-secondary">Customer</span> {{ $sale->customer_name }}
                                            @else
                                            -
                                            @endif
                                        </td>
                                        <td>{{ $sale->mobiles_count }}</td>
                                        <td>{{ number_format($sale->total_amount, 0) }}</td>
                                        <td>{{ number_format($sale->discount, 0) }}</td>
                                        <td>{{ number_format($sale->grand_total, 0) }}</td>
                                        <td>{{ number_format($sale->paid_amount, 0) }}</td>
                                        <td>
                                            @php $bal = $sale->balance; @endphp
                                            <span class="badge {{ $bal>0?'bg-danger':'bg-success' }}">
                                                {{ number_format($bal, 0) }}
                                            </span>
                                        </td>
                                        <td>{{ $sale->seller->name ?? '-' }}</td>
                                        <td>
                                            <a class="btn btn-sm btn-outline-primary" target="_blank"
                                                href="{{ route('sales.receipt', $sale->id) }}">
                                                Receipt
                                            </a>
                                            {{-- Add a Show button if you later build details page --}}
                                            {{-- <a class="btn btn-sm btn-outline-secondary"
                                                href="{{ route('sales.show',$sale->id) }}">View</a> --}}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="8" class="text-end fw-bold">Overall Profit:</td>
                                        <td colspan="3" class="fw-bold text-success">{{ number_format($overallProfit, 0) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="mt-2">
                            {{ $sales->withQueryString()->links() }}
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- DataTables (optional UI enhancement) --}}

<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
<script>
    $(function(){
    $('#sales-table').DataTable({
      order: [[0,'desc']],
      pageLength: 25
    });
  });

  $(document).ready(function () {
    $('#vendor_id').select2({
    placeholder: "Select a vendor",
    allowClear: true,
    width: '100%' // Optional to make it responsive
    });
    });
</script>
@endsection

