@extends('user_navbar')
@section('content')

    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <!-- Grouped multiple cards for statistics starts here -->
                <div class="row grouped-multiple-statistics-card">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6 col-xl-3 col-sm-6 col-12">
                                        <div
                                            class="d-flex align-items-start mb-sm-1 mb-xl-0 border-right-blue-grey border-right-lighten-5">
                                            <span class="card-icon primary d-flex justify-content-center mr-3">
                                                <i class="icon p-1 fa fa-mobile customize-icon font-large-5 p-1"></i>
                                            </span>
                                            <div class="stats-amount mr-3">
                                                <h3 class="heading-text text-bold-600">{{$userMobileCount}}</h3>
                                                <p class="sub-heading">My Mobiles</p>
                                            </div>
                                            <!-- <span class="inc-dec-percentage">
                                                    <small class="success"><i class="fa fa-long-arrow-up"></i> 5.2%</small>
                                                </span> -->
                                        </div>
                                    </div>
                                   
                                    <div class="col-lg-6 col-xl-3 col-sm-6 col-12">
                                        <div class="d-flex align-items-start border-right-blue-grey border-right-lighten-5">
                                            <span class="card-icon success d-flex justify-content-center mr-3">
                                                <i class="icon p-1 fa fa-mobile customize-icon font-large-5 p-1"></i>
                                            </span>
                                            <div class="stats-amount mr-3">
                                                <h3 class="heading-text text-bold-600">{{$soldMobile}}</h3>
                                                <p class="sub-heading">Sold Mobiles</p>
                                            </div>
                                            <!-- <span class="inc-dec-percentage">
                                                    <small class="success"><i class="fa fa-long-arrow-up"></i> 10.0%</small>
                                                </span> -->
                                        </div>
                                    </div>

                                     <div class="col-lg-6 col-xl-3 col-sm-6 col-12">
                                        <div class="d-flex align-items-start">
                                            <span class="card-icon warning d-flex justify-content-center mr-3">
                                                <i class="icon p-1 fa fa-mobile customize-icon font-large-5 p-1"></i>
                                            </span>
                                            <div class="stats-amount mr-3">
                                                <h3 class="heading-text text-bold-600">{{ $pendingMobiles }}</h3>
                                                <p class="sub-heading">Pending Mobiles</p>
                                            </div>
                                            <!-- <span class="inc-dec-percentage">
                                                        <small class="danger"><i class="fa fa-long-arrow-down"></i> 13.6%</small>
                                                    </span> -->
                                        </div>
                                    </div>
                                     
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                 <!-- <div class="row grouped-multiple-statistics-card">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                   
                                   

                                </div>

                            </div>
                        </div>
                    </div>
                </div> -->
                <div class="row grouped-multiple-statistics-card">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6 col-xl-3 col-sm-6 col-12">
                                        <div
                                            class="d-flex align-items-start mb-sm-1 mb-xl-0 border-right-blue-grey border-right-lighten-5">

                                            <div class="stats-amount mr-3">
                                                <h3 class="heading-text text-bold-600">Rs.{{$totalCostPrice}}</h3>
                                                <p class="sub-heading">Total Mobiles Cost</p>
                                            </div>
                                            <!-- <span class="inc-dec-percentage">
                                                    <small class="success"><i class="fa fa-long-arrow-up"></i> 5.2%</small>
                                                </span> -->
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-xl-3 col-sm-6 col-12">
                                        <div
                                            class="d-flex align-items-start mb-sm-1 mb-xl-0 border-right-blue-grey border-right-lighten-5">

                                            <div class="stats-amount mr-3">
                                                <h3 class="heading-text text-bold-600"> Rs.{{$totalSellingPrice}}</h3>
                                                <p class="sub-heading">Total Sold Mobile Sellings</p>
                                            </div>
                                            <!-- <span class="inc-dec-percentage">
                                                    <small class="danger"><i class="fa fa-long-arrow-down"></i> 2.0%</small>
                                                </span> -->
                                        </div>
                                    </div>
                                     <div class="col-lg-6 col-xl-3 col-sm-6 col-12">
                                        <div
                                            class="d-flex align-items-start mb-sm-1 mb-xl-0 border-right-blue-grey border-right-lighten-5">

                                            <div class="stats-amount mr-3">
                                                <h3 class="heading-text text-bold-600"> Rs.{{$pendingMobilesCost}}</h3>
                                                <p class="sub-heading">Total Pending Mobile Cost</p>
                                            </div>
                                            <!-- <span class="inc-dec-percentage">
                                                    <small class="danger"><i class="fa fa-long-arrow-down"></i> 2.0%</small>
                                                </span> -->
                                        </div>
                                    </div>
                                   
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Grouped multiple cards for statistics ends here -->

             
                <!-- Grouped multiple cards for statistics ends here -->

                <!-- Minimal modern charts for power consumption, region statistics and sales etc. starts here -->
                <div class="row minimal-modern-charts">


                    <!-- latest update tracking chart-->
                    {{-- <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-12 latest-update-tracking">
                        <div class="card">
                            <div class="card-header latest-update-heading d-flex justify-content-between">
                                <h4 class="latest-update-heading-title text-bold-500">Available Publications</h4>

                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered zero-configuration">
                                    <thead>
                                        <tr>
                                            <th>Publication Title</th>
                                            <th>Publication Number</th>
                                            <th>Description</th>
                                            <th>File/Document</th>
                                            <th>Start</th>
                                            <th>End</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($publications as $key)
                                            <tr>
                                                <td>{{ $key->name }}</td>
                                                <td>{{ $key->publication_number }}</td>
                                                <td>{{ $key->description }}</td>
                                                <td><a
                                                        href="{{ route('downloadpublication', $key->id) }}">{{ $key->file_name }}</a>
                                                </td>
                                                <td>{{ $key->start_date }}</td>
                                                <td>{{ $key->end_date }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div> --}}

                </div>
            </div>
        </div>
    </div>
    {{-- <script>
        function getPublicationDetails(pub_id) {
            var request = XMLHttpRequest();
            request.open("GET", "/publicationdetails/" + pub_id, false);
            request.send();
            console.log(request.response);
        }
    </script> --}}
@endsection
