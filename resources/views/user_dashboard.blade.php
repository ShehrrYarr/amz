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

            @php
            $userId = auth()->id();
            @endphp
            @if (in_array($userId, [1, 2]))
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




            {{-- <h3 class="mb-4">Installment Calculator</h3>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">Total Amount</label>
                    <input type="number" class="form-control" id="totalAmount" placeholder="Enter total amount" />
                </div>
                <div class="col-md-4">
                    <label class="form-label">Down Payment</label>
                    <input type="number" class="form-control" id="downPayment" placeholder="Enter down payment" />
                </div>
                <div class="col-md-4">
                    <label class="form-label">Remaining Amount</label>
                    <input type="number" class="form-control" id="remainingAmount" placeholder="Auto-calculated"
                        readonly />
                </div>
            </div> --}}
            {{-- <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">Percentage (%)</label>
                    <input type="number" class="form-control" id="percentage" placeholder="Interest %" />
                </div>
                <div class="col-md-4">
                    <label class="form-label">Number of Installments</label>
                    <input type="number" class="form-control" id="numInstallments" min="1" placeholder="e.g. 3" />
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button class="btn btn-info w-100" id="generateInstallments" type="button">Generate
                        Installments</button>
                </div>
            </div>
            <form id="installmentsForm">
                <div id="installmentsContainer"></div>
            </form> --}}

            <style>
           .calculator-box { background: #fff; padding: 30px; border-radius: 15px; box-shadow: 0 2px 10px #eee; max-width: 500px;
            margin: auto;}
            .calculator-box h2 { margin-bottom: 20px; }
            .form-group { margin-bottom: 15px; }
            .form-group label { display: block; margin-bottom: 5px;}
            .form-group input, .form-group select { width: 100%; padding: 8px; font-size: 16px; border-radius: 6px; border: 1px
            solid #ccc;}
            .form-group input[readonly] { background: #f0f0f0; }
            #installments-container { margin-top: 25px; }
            .installment-row { display: flex; gap: 6px; margin-bottom: 10px;}
            .installment-row input { flex: 1;}
            .payable-group {display: flex; gap: 6px; margin-bottom: 5px;}
            .payable-group input {width: 33%;}
            .modal-bg {
            position: fixed; top:0; left:0; width:100vw; height:100vh;
            background:rgba(0,0,0,0.4); display: none; justify-content: center; align-items: center;
            }
            .modal-content {
            background: #fff; padding: 25px 35px; border-radius: 12px; box-shadow: 0 4px 20px #0002; min-width: 260px;
            }
            .close-modal { background: #d00; color: #fff; border: none; padding: 4px 14px; border-radius: 6px; float:right;}
            .split-details {font-size: 17px; margin-bottom: 10px;}
            .bold {font-weight: bold;}
            </style>



          

            <!-- Modal for interest/principal split -->
            <div class="modal-bg" id="splitModal">
                <div class="modal-content">
                    <button class="close-modal"
                        onclick="document.getElementById('splitModal').style.display='none'">X</button>
                    <div id="splitDetails"></div>
                </div>
            </div>



            @endif





        </div>
    </div>
</div>
</div>

<script>
    // Utility function for currency rounding
  

</script>


{{-- <script>
    document.getElementById('downPayment').addEventListener('input', updateRemaining);
    document.getElementById('totalAmount').addEventListener('input', updateRemaining);
    
    function updateRemaining() {
        let total = parseFloat(document.getElementById('totalAmount').value) || 0;
        let down = parseFloat(document.getElementById('downPayment').value) || 0;
        let remaining = total - down;
        document.getElementById('remainingAmount').value = remaining >= 0 ? remaining.toFixed(2) : 0;
    }
    
    document.getElementById('generateInstallments').addEventListener('click', function() {
        let container = document.getElementById('installmentsContainer');
        container.innerHTML = '';
    
        let num = parseInt(document.getElementById('numInstallments').value);
        let percentage = parseFloat(document.getElementById('percentage').value) || 0;
        let remaining = parseFloat(document.getElementById('remainingAmount').value) || 0;
    
        if(isNaN(num) || num < 1 || remaining <= 0) {
            container.innerHTML = `<div class="alert alert-warning">Please enter all values correctly to generate installments.</div>`;
            return;
        }
    
        let rows = '';
        for (let i = 0; i < num; i++) {
            rows += `
            <div class="row installment-row mb-3" data-index="${i}">
                <div class="col-md-3">
                    <label class="form-label">Date</label>
                    <input type="date" class="form-control" name="installment_date_${i}" />
                </div>
                <div class="col-md-3">
                    <label class="form-label">Installment Amount</label>
                    <input type="number" class="form-control installment-amount" name="installment_amount_${i}" readonly />
                </div>
                <div class="col-md-3">
                    <label class="form-label">Pay Amount</label>
                    <input type="number" class="form-control pay-amount" name="pay_amount_${i}" min="0" />
                </div>
                <div class="col-md-3">
                    <label class="form-label">Remaining After Payment</label>
                    <input type="number" class="form-control remaining-after" name="remaining_after_${i}" readonly />
                </div>
            </div>
            `;
        }
        container.innerHTML = rows;
    
        recalculateInstallments();
        document.querySelectorAll('.pay-amount').forEach(input => {
            input.addEventListener('input', recalculateInstallments);
        });
    });
    
    function recalculateInstallments() {
        let num = parseInt(document.getElementById('numInstallments').value) || 0;
        let percentage = parseFloat(document.getElementById('percentage').value) || 0;
        let initialRemaining = parseFloat(document.getElementById('remainingAmount').value) || 0;
    
        let currentRemaining = initialRemaining;
        for (let i = 0; i < num; i++) {
            let interest = currentRemaining * (percentage / 100);
            let installmentAmount = currentRemaining + interest;
    
            let instAmountInput = document.querySelector(`[name="installment_amount_${i}"]`);
            if (instAmountInput) instAmountInput.value = installmentAmount.toFixed(2);
    
            let payInput = document.querySelector(`[name="pay_amount_${i}"]`);
            let pay = parseFloat(payInput && payInput.value) || 0;
    
            let remainingInput = document.querySelector(`[name="remaining_after_${i}"]`);
            let newRemaining = installmentAmount - pay;
            newRemaining = newRemaining < 0 ? 0 : newRemaining;
            if (remainingInput) remainingInput.value = newRemaining.toFixed(2);
    
            currentRemaining = newRemaining;
        }
    }
</script> --}}



@endsection