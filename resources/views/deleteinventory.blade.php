@extends('user_navbar')
@section('content')



<style>
    .gradient-button3 {
        background: linear-gradient(to right, #74a8e0, #1779e2);
        border-color: #007bff;
        color: white;
    }

    .gradient-button4 {
        background: linear-gradient(to right, rgb(224, 116, 116), rgb(226, 23, 23));
        border-color: rgb(255, 0, 0);
        color: white;
    }

    ,
    .select2-container .select2-selection--single {
        height: 38px !important;
        padding: 5px 10px;
    }
</style>


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

            
            




            <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-12 latest-update-tracking mt-1">
                <div class="card">
                    <div class="card-header latest-update-heading d-flex justify-content-between">
                        <h4 class="latest-update-heading-title text-bold-500">Deleted Mobiles</h4>

                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered zero-configuration" id="mobileTable">
                            <thead>
                                <tr>
                                    {{-- <th>ID</th> --}}
                                    <th>Added at</th>
                                    <th>Added By</th>
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
                                    <th>Mobile History</th>
                                    <th>Availability</th>
                                    <!-- <th>Transfer</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($mobile as $key)
                                <tr>
                                    {{-- <td>{{ $key->id }}</td> --}}
                                    {{-- <td>{{ $key->created_at }}</td> --}}
                                    <!--<td>{{ \Carbon\Carbon::parse($key->created_at)->tz('Asia/Karachi')->format('d h:i A, M ,Y') }}</td>-->
                                    <td>{{ \Carbon\Carbon::parse($key->created_at)->format(' Y-m-d / h:i ') }}</td>
                                    <!--<td>{{ \Carbon\Carbon::parse($key->created_at)->diffForHumans() }}</td>-->






                                    <td>{{ $key->creator->name ?? 'N/A' }}</td>

                                    <td>{{ $key->mobile_name }}</td>
                                    <td>{{ $key->company->name ?? 'N/A' }}</td>
                                    <td>{{ $key->group->name ?? 'N/A' }}</td>
                                    <td>{{ $key->latestVendorTransaction->vendor->name ?? 'N/A' }}</td>


                                    <td>{{ $key->imei_number }}</td>
                                    <td>{{ $key->sim_lock }}</td>
                                    <td>{{ $key->color }}</td>
                                    <td>{{ $key->storage }}</td>
                                    <td>{{ $key->battery_health }}</td>
                                    <td>{{ $key->cost_price }}</td>
                                    <td>{{ $key->selling_price }}</td>
                                    <td>
                                        <a href="{{ route('showHistory', $key->id) }}" class="btn btn-sm btn-warning">
                                            <i class="fa fa-eye"></i>

                                        </a>

                                    </td>
                                    <td>
                                        <a href="" onclick="sold({{ $key->id }})" data-toggle="modal"
                                            data-target="#exampleModal3">
                                            <span class="badge badge-danger">{{ $key->availability }}</span>

                                        </a>

                                    </td>
                                    <!-- <td><a href="" onclick="transfer({{ $key->id }})" data-toggle="modal"
                                                    data-target="#exampleModal2">
                                                    <i class="fa fa-exchange" style="font-size: 20px"></i></a></td> -->
                                   
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

           

            

        </div>
    </div>
</div>
<script>
    //Start dataTable

        $(document).ready(function () {
            $('#mobileTable').DataTable({
                order: [
                    [0, 'desc']
                ]
            });
        });
        //End dataTable
        

     

        //Message Time Out
        setTimeout(function () {
            document.getElementById('successMessage').style.display = 'none';
        }, 5000); // 15 seconds in milliseconds
        //End Message Time Out
        //Message Time Out
        setTimeout(function () {
            document.getElementById('dangerMessage').style.display = 'none';
        }, 5000); // 15 seconds in milliseconds
        //End Message Time Out

       


</script>
@endsection