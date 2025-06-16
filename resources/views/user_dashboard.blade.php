@extends('user_navbar')
@section('content')

<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
        </div>
        <div class="content-body">

            <div class="content-header row">
            </div>

            {{-- Image Banner --}}
            <div class="mb-2">
                <img src="{{ asset('images/amz.png') }}" alt="AMZ Banner" class="img-fluid shadow rounded"
                    style="width: 100%; max-height: 250px; object-fit: cover;">
            </div>

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
                                            <a href="/manageinventory"> <i
                                                    class="icon p-1 fa fa-mobile customize-icon font-large-5 p-1"></i></a>
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
                                            <a href="/soldinventory"> <i
                                                    class="icon p-1 fa fa-mobile customize-icon font-large-5 p-1"></i></a>
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
                                            <a href="/pendinginventory"><i
                                                    class="icon p-1 fa fa-mobile customize-icon font-large-5 p-1"></i></a>
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
            @if($userId == 1 && 2)
            <div class="row grouped-multiple-statistics-card">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6 col-xl-3 col-sm-6 col-12">
                                    <div
                                        class="d-flex align-items-start mb-sm-1 mb-xl-0 border-right-blue-grey border-right-lighten-5">

                                        <div class="stats-amount mr-3">
                                            <h3 class="heading-text text-bold-600">Rs.{{number_format($totalCostPrice)}}
                                            </h3>
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
                                            <h3 class="heading-text text-bold-600">
                                                Rs.{{number_format($totalSellingPrice)}}</h3>
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
                                            <h3 class="heading-text text-bold-600">
                                                Rs.{{number_format($pendingMobilesCost)}}</h3>
                                            <p class="sub-heading">Total Pending Mobile Cost</p>
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
                                            <h3 class="heading-text text-bold-600">
                                                Rs.{{ number_format($totalReceivable) }}</h3>
                                            <p class="sub-heading">Total Receivable</p>
                                        </div>
                                        <!-- <span class="inc-dec-percentage">
                                                                                        <small class="success"><i class="fa fa-long-arrow-up"></i> 5.2%</small>
                                                                                    </span> -->
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container mt-4">
                <h2>Installment Calculator</h2>
                <form id="installmentForm" onsubmit="return false;">
                    <!-- Total Amount -->
                    <div class="mb-3">
                        <label for="totalAmount" class="form-label">Total Amount</label>
                        <input type="number" class="form-control" id="totalAmount" min="0" required>
                    </div>
                    <!-- Down Payment -->
                    <div class="mb-3">
                        <label for="downPayment" class="form-label">Down Payment</label>
                        <input type="number" class="form-control" id="downPayment" min="0" required>
                    </div>
                    <!-- Remaining Amount -->
                    <div class="mb-3">
                        <label for="remainingAmount" class="form-label">Remaining Amount</label>
                        <input type="number" class="form-control" id="remainingAmount" readonly>
                    </div>
                    <!-- Monthly Profit Percentage -->
                    <div class="mb-3">
                        <label for="percentage" class="form-label">Monthly Profit Percentage (%)</label>
                        <input type="number" class="form-control" id="percentage" min="0" required>
                    </div>
                    <!-- Number of Installments -->
                    <div class="mb-3">
                        <label for="numInstallments" class="form-label">Number of Installments</label>
                        <input type="number" class="form-control" id="numInstallments" min="1" required>
                    </div>
                    <!-- Installment Dates -->
                    <div class="mb-3" id="datesDiv" style="display:none;">
                        <label class="form-label">Select Installment Dates</label>
                        <div id="datesInputs"></div>
                    </div>
                    <!-- Pay Installment -->
                    <div class="mb-3">
                        <label for="payAmount" class="form-label">Pay Installment Amount</label>
                        <input type="number" class="form-control" id="payAmount" min="0">
                    </div>
                    <!-- Updated Balance -->
                    <div class="mb-3">
                        <label for="updatedBalance" class="form-label">Updated Remaining Amount</label>
                        <input type="number" class="form-control" id="updatedBalance" readonly>
                    </div>
                    <button type="button" class="btn btn-primary" id="calcInstallment">Calculate First
                        Installment</button>
                    <button type="button" class="btn btn-success mt-2" id="checkDates">Check Installments (Add
                        Profit)</button>
                </form>
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
            @endif
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
                                        <td><a href="{{ route('downloadpublication', $key->id) }}">{{ $key->file_name
                                                }}</a>
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


<script>
    $(document).ready(function(){

// Calculate Remaining Amount when total or down payment changes
$('#totalAmount, #downPayment').on('input', function(){
let total = parseFloat($('#totalAmount').val()) || 0;
let down = parseFloat($('#downPayment').val()) || 0;
let remaining = total - down;
$('#remainingAmount').val(remaining > 0 ? remaining : 0);
$('#updatedBalance').val(remaining > 0 ? remaining : 0);
});

// Show date inputs based on number of installments
$('#numInstallments').on('input', function(){
let n = parseInt($(this).val());
let $datesDiv = $('#datesDiv');
let $inputs = $('#datesInputs');
$inputs.empty();
if(n > 0){
$datesDiv.show();
for(let i=1; i<=n; i++){ $inputs.append(`<input type="date" class="form-control mb-2" name="installment_dates[]"
    required>`);
    }
    } else {
    $datesDiv.hide();
    }
    });

    // Calculate first installment (add profit, deduct pay)
    $('#calcInstallment').click(function(){
    let rem = parseFloat($('#remainingAmount').val()) || 0;
    let percent = parseFloat($('#percentage').val()) || 0;
    let pay = parseFloat($('#payAmount').val()) || 0;

    // Profit calculation
    let profit = (rem * percent) / 100;
    let balanceWithProfit = rem + profit;

    // Deduct user pay
    let updatedBalance = balanceWithProfit - pay;

    $('#updatedBalance').val(updatedBalance >= 0 ? updatedBalance.toFixed(2) : 0);
    });

    // Check all installment dates, add profit for each past date
    $('#checkDates').click(function(){
    let rem = parseFloat($('#remainingAmount').val()) || 0;
    let percent = parseFloat($('#percentage').val()) || 0;
    let today = new Date();
    let count = 0;

    $('#datesInputs input[type="date"]').each(function(){
    let dateVal = $(this).val();
    if(dateVal){
    let dueDate = new Date(dateVal);
    // Only if date is in the past (not today or future)
    if(dueDate < today){ let profit=(rem * percent) / 100; rem +=profit; count++; } } }); $('#updatedBalance').val(rem>=
        0 ? rem.toFixed(2) : 0);
        if(count > 0){
        alert(`${count} due date(s) passed. Profit added for each. New balance: ${rem.toFixed(2)}`);
        }else{
        alert('No past due dates yet.');
        }
        });

        });
</script>

@endsection