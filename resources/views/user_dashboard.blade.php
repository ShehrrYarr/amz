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

            <style>
                label {
                    display: block;
                    margin-bottom: 5px;
                    font-weight: bold;
                }

                input[type="number"],
                input[type="text"],
                input[type="date"] {
                    padding: 7px;
                    width: 100%;
                    border-radius: 5px;
                    border:
                        1px solid #ccc;
                }

                input[readonly] {
                    background: #eee;
                }

                button {
                    padding: 10px 20px;
                    background: #4CAF50;
                    color: #fff;
                    border: none;
                    border-radius: 6px;
                    font-size: 15px;
                }

                .installments-section {
                    margin-top: 25px;
                }

                .installment-group {
                    padding: 12px;
                    background: #f8f8f8;
                    margin-bottom: 15px;
                    border-radius: 7px;
                }

                .row {
                    display: flex;
                    gap: 12px;
                }

                .row>div {
                    flex: 1;
                }
            </style>


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

            {{-- <div class="container mt-5">

                <h2>Custom Installment Calculator</h2>
                <div class="field">
                    <label>Total Payment</label>
                    <input type="number" id="totalPayment" min="0" step="0.01" placeholder="Total payment"
                        oninput="updateRemaining()">
                </div>
                <div class="field">
                    <label>Down Payment</label>
                    <input type="number" id="downPayment" min="0" step="0.01" placeholder="Down payment"
                        oninput="updateRemaining()">
                </div>
                <div class="field">
                    <label>Remaining Payment</label>
                    <input type="number" id="remainingPayment" readonly>
                </div>
                <div class="field">
                    <label>Percentage (%)</label>
                    <input type="number" id="percentage" min="0" step="0.01" placeholder="Enter percentage">
                </div>
                <div class="field">
                    <label>Number of Installments</label>
                    <input type="number" id="numInstallments" min="1" max="12" oninput="showInstallmentFields()"
                        placeholder="How many installments?">
                </div>
                <form id="installmentsForm">
                    <div id="installmentsContainer" class="installments-section"></div>
                </form>
                <div id="calculationResults"></div>

            </div> --}}

            @endif





        </div>
    </div>
</div>
</div>

<script>
    function updateRemaining() {
    const total = parseFloat(document.getElementById('totalPayment').value) || 0;
    const down = parseFloat(document.getElementById('downPayment').value) || 0;
    const remaining = Math.max(0, total - down);
    document.getElementById('remainingPayment').value = remaining.toFixed(2);
    }
    
    
    function showInstallmentFields() {
    const num = parseInt(document.getElementById('numInstallments').value) || 0;
    const container = document.getElementById('installmentsContainer');
    container.innerHTML = '';
    for (let i = 1; i <= num; i++) { container.innerHTML +=` <div class="installment-group" id="installment${i}">
        <h4>Installment #${i}</h4>
        <div class="row">
            <div>
                <label>Calendar Date</label>
                <input type="date" id="date${i}">
            </div>
            <div>
                <label>Pay Amount</label>
                <input type="number" min="0" step="0.01" id="payAmount${i}" placeholder="Enter amount">
            </div>
            <div>
                <label>Remaining Amount</label>
                <input type="number" id="remain${i}" readonly>
            </div>
        </div>
        </div>`;
        }
    
        if (num > 0) {
        container.innerHTML += `<button type="button" onclick="calculateInstallments()">Calculate Installments</button>`;
        }
        }
    
        // Step 7–8: Custom Calculation Logic
        function calculateInstallments() {
        // Read main inputs
        let principle = parseFloat(document.getElementById('remainingPayment').value) || 0;
        let percentage = parseFloat(document.getElementById('percentage').value) || 0;
        let num = parseInt(document.getElementById('numInstallments').value) || 0;
        let perDayRate = percentage / 30; // As per your logic
        let resultsHtml = '<h3>Calculation Results:</h3>';
        let profit = 0;
    
        let lastDate = new Date();
        let profitRemaining = 0;
    
        for (let i = 1; i <= num; i++) { const payAmount=parseFloat(document.getElementById(`payAmount${i}`).value) || 0;
            const dateVal=document.getElementById(`date${i}`).value; let days=30;  if (dateVal) { const
            currentDate=new Date(dateVal); if (i===1) { const today=new Date(); days=Math.round((currentDate - today) /
            (1000 * 60 * 60 * 24)); lastDate=currentDate; } else { days=Math.round((currentDate - lastDate) / (1000 * 60 *
            60 * 24)); lastDate=currentDate; } if (days < 1) days=30; }  let
            percentForThis=perDayRate * days; let profitThis=principle * (percentForThis / 100); let totalThis=principle +
            profitThis;  let payInfo='' ; if (payAmount> 0) {
            if (payAmount >= profitThis) {
            // Profit will be cleared, remaining will go to principle
            let toPrinciple = payAmount - profitThis;
            if (toPrinciple > 0) {
            principle = Math.max(0, principle - toPrinciple);
            profitRemaining = 0;
            }
            else {
            profitRemaining = Math.abs(toPrinciple);
            }
            } else {
            // Not enough to clear profit, keep principle same, reduce profit
            profitRemaining = profitThis - payAmount;
            }
            }
            // Next month: Always apply percentage on principle only (not on profit!)
            document.getElementById(`remain${i}`).value = (principle + profitRemaining).toFixed(2);
    
            resultsHtml += `
            <div>
                <b>Installment #${i}:</b> Principle: ${principle.toFixed(2)},
                Profit: ${profitThis.toFixed(2)},
                Total Due: ${(principle + profitThis).toFixed(2)},
                Paid: ${payAmount.toFixed(2)},
                Remaining Principle: ${principle.toFixed(2)},
                Remaining Profit: ${profitRemaining.toFixed(2)}
            </div>
            `;
            }
            document.getElementById('calculationResults').innerHTML = resultsHtml;
            }
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