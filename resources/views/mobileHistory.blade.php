@extends('user_navbar')
@section('content')


    <style>
        .card {
            border-radius: 12px;

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



                <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-12 latest-update-tracking mt-1 ">
                    <div class="card ">
                        <div class="card-header latest-update-heading d-flex justify-content-between">
                            <h4 class="latest-update-heading-title text-bold-500"> Mobile History of {{ $mobileName }}</h4>

                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered zero-configuration" id="mobileTable">
                                <thead>
                                    <tr>
                                        <th>Created At</th>

                                        <th>Added By</th>
                                        <th>Mobile Name</th>
                                        <th>Group</th>
                                        <th>Customer Name</th>
                                        <th>Availability Status</th>
                                        <th>Battery Health</th>
                                        <th>Cost price</th>
                                        <th>Selling price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($history as $key)
                                        <tr>
                                            <td>{{ $key->created_at }}</td>
                                            <td>{{ $key->created_by }}</td>
                                            <td>{{ $key->mobile_name }}</td>
                                            <td>{{ $key->group }}</td>
                                            <td>{{ $key->customer_name }}</td>
                                            <td>{{ $key->availability_status }}</td>
                                            <td>{{ $key->battery_health }}</td>
                                            <td>{{ $key->cost_price }}</td>
                                            <td>{{ $key->selling_price }}</td>

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
        $(document).ready(function () {
            $('#mobileTable').DataTable({
                order: [
                    [0, 'desc']
                ]
            });
        });
    </script>



@endsection