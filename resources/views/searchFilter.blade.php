@extends('user_navbar')
@section('content')



    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                @if (session('success'))
                    <div class="alert alert-success" id="successMessage">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('danger'))
                    <div class="alert alert-danger" id="dangerMessage" style="color: red;">
                        {{ session('danger') }}
                    </div>
                @endif

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label>Company</label>
                        <select id="filterCompany" class="form-control">
                            <option value="">All Companies</option>
                            @foreach($company as $comp)
                                <option value="{{ $comp->id }}">{{ $comp->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Group</label>
                        <select id="filterGroup" class="form-control">
                            <option value="">All Groups</option>
                            @foreach($group as $grp)
                                <option value="{{ $grp->id }}">{{ $grp->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>&nbsp;</label>
                        <button id="searchBtn" class="btn btn-primary btn-block">Search</button>
                    </div>
                </div>



                <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-12 latest-update-tracking mt-1">
                    <div class="card">
                        <div class="card-header latest-update-heading d-flex justify-content-between">
                            <h4 class="latest-update-heading-title text-bold-500">Filter Mobiles</h4>

                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered zero-configuration" id="mobileTable">
                                <thead>
                                    <tr>
                                        {{-- <th>ID</th> --}}
                                        <th>Added at</th>
                                        <th>Mobile Name</th>
                                        <th>Company</th>
                                        <th>Group</th>
                                        <th>Vendor</th>
                                        <th>IMEI#</th>
                                        <th>SIM Lock</th>
                                        <th>Color</th>
                                        <th>Storage</th>
                                        <th>Battery Health</th>
                                        <th>Cost Price</th>
                                        <th>Selling Price</th>
                                        <th>Availability</th>
                                        <th>Transfer</th>
                                        <th>Mobile History</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>





            </div>
        </div>
    </div>
    <script>
$(document).ready(function() {
    $('#searchBtn').on('click', function () {
        let company = $('#filterCompany').val();
        let group = $('#filterGroup').val();

        $.ajax({
            url: '{{ route("api.searchMobiles") }}',
            method: 'GET',
            data: {
                company_id: company,
                group_id: group
            },
            success: function(response) {
                let tbody = $('#mobileTable tbody');
                tbody.empty();

                if (response.length > 0) {
                    $.each(response, function(index, mobile) {
                        tbody.append(`
                            <tr>
                                <td>${mobile.created_at}</td>
                                <td>${mobile.mobile_name}</td>
                                <td>${mobile.company_name || '-'}</td>
                                <td>${mobile.group_name || '-'}</td>
                                <td>${mobile.vendor_name || '-'}</td>
                                <td>${mobile.imei_number}</td>
                                <td>${mobile.sim_lock}</td>
                                <td>${mobile.color}</td>
                                <td>${mobile.storage}</td>
                                <td>${mobile.battery_health || '-'}</td>
                                <td>${mobile.cost_price}</td>
                                <td>${mobile.selling_price}</td>
                                <td>${mobile.availability}</td>
                                <td>${mobile.is_transfer ? 'Yes' : 'No'}</td>
                               
                                <td><a href="history/${mobile.id}" class="btn btn-sm btn-warning"> <i class="fa fa-eye"></i></a></td>
                            </tr>
                        `);
                    });
                } else {
                    tbody.append('<tr><td colspan="16" class="text-center">No records found.</td></tr>');
                }
            }
        });
    });
});
</script>


@endsection