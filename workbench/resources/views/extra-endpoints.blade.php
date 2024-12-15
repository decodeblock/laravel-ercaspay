<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Verification Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Transaction Verification</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('call.endpoint') }}" method="POST">
                            @csrf

                            <!-- Endpoint Radio Buttons -->
                            <div class="mb-4">
                                <label class="form-label d-block">Which endpoint do you want to call?</label>
                                <div class="border rounded p-3">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input endpoint-radio" type="radio"
                                            name="endpoint" id="verifyTransaction" value="verifyTransaction" required>
                                        <label class="form-check-label" for="verifyTransaction">
                                            Verify Transaction
                                        </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input endpoint-radio" type="radio"
                                            name="endpoint" id="fetchTransactionDetails" value="fetchTransactionDetails">
                                        <label class="form-check-label" for="fetchTransactionDetails">
                                            Fetch Transaction Details
                                        </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input endpoint-radio" type="radio"
                                            name="endpoint" id="fetchTransactionStatus" value="fetchTransactionStatus">
                                        <label class="form-check-label" for="fetchTransactionStatus">
                                            Fetch Transaction Status
                                        </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input endpoint-radio" type="radio"
                                            name="endpoint" id="cancelTransaction" value="cancelTransaction">
                                        <label class="form-check-label" for="cancelTransaction">
                                            Cancel Transaction
                                        </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input endpoint-radio" type="radio"
                                            name="endpoint" id="getCardDetails" value="getCardDetails">
                                        <label class="form-check-label" for="getCardDetails">
                                            Get Card Details
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input endpoint-radio" type="radio"
                                            name="endpoint" id="verifyCardTransaction" value="verifyCardTransaction">
                                        <label class="form-check-label" for="verifyCardTransaction">
                                            Verify Card Transaction
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Dynamic Fields Container -->
                            <div id="dynamicFields">
                                <!-- Transaction Reference Field (Always visible) -->
                                <div class="mb-3">
                                    <label for="transactionRef" class="form-label">Transaction Reference</label>
                                    <input type="text" class="form-control" id="transactionRef" name="transactionRef" required>
                                </div>

                                <!-- Fields for Fetch Transaction Status -->
                                <div id="fetchStatusFields" style="display: none;">
                                    <div class="mb-3">
                                        <label for="paymentRef" class="form-label">Payment Reference</label>
                                        <input type="text" class="form-control" id="paymentRef" name="paymentRef">
                                    </div>

                                    <div class="mb-4">
                                        <label for="paymentMethod" class="form-label">Payment Method</label>
                                        <select class="form-select" id="paymentMethod" name="paymentMethod">
                                            <option value="" selected disabled>Select payment method</option>
                                            <option value="bank-transfer">Bank Transfer</option>
                                            <option value="ussd">USSD</option>
                                            <option value="card">Card</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    Submit Request
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const radioButtons = document.querySelectorAll('.endpoint-radio');
            const fetchStatusFields = document.getElementById('fetchStatusFields');
            const paymentRef = document.getElementById('paymentRef');
            const paymentMethod = document.getElementById('paymentMethod');

            radioButtons.forEach(radio => {
                radio.addEventListener('change', function() {
                    // Hide fields by default
                    fetchStatusFields.style.display = 'none';
                    paymentRef.required = false;
                    paymentMethod.required = false;

                    // Show fields only for Fetch Transaction Status
                    if (this.value === 'fetchTransactionStatus') {
                        fetchStatusFields.style.display = 'block';
                        paymentRef.required = true;
                        paymentMethod.required = true;
                    }
                });
            });
        });
    </script>
</body>
</html>
