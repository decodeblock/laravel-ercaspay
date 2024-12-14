<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Initiate a Payment</h4>
                    </div>

                    <div class="card-body">
                        <form id="paymentForm" action="{{ route('initiate.pay') }}" method="POST">
                            @csrf

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="amount" class="form-label">Amount:</label>
                                    <input type="number" name="amount" id="amount" value="2000"
                                        class="form-control" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="currency" class="form-label">Currency:</label>
                                    <select name="currency" id="currency" class="form-select">
                                        <option value="NGN" selected>NGN</option>
                                    </select>
                                </div>

                                <div class="col-12">
                                    <label for="description" class="form-label">Description:</label>
                                    <textarea name="description" id="description" class="form-control"
                                        required>Payment for services</textarea>
                                </div>

                                <div class="col-12">
                                    <label for="paymentMethodsInput" class="form-label">Payment Methods:</label>
                                    <select id="paymentMethodsInput" name="paymentMethods[]"
                                        class="form-select" multiple>
                                        <option value="card" selected>Card</option>
                                        <option value="bank-transfer" selected>Bank Transfer</option>
                                        <option value="ussd" selected>USSD</option>
                                    </select>
                                    <div class="form-text">Hold Ctrl/Cmd to select multiple options</div>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Checkout Type:</label>
                                    <div class="form-check">
                                        <input type="radio" id="ercaspayCheckout" name="checkoutType"
                                            value="ercaspay" class="form-check-input">
                                        <label class="form-check-label" for="ercaspayCheckout">
                                            Ercaspay checkout url
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input type="radio" id="customCheckout" name="checkoutType"
                                            value="custom" checked class="form-check-input">
                                        <label class="form-check-label" for="customCheckout">
                                            Custom Checkout Page
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="customerName" class="form-label">Customer Name:</label>
                                    <input type="text" name="customerName" id="customerName"
                                        value="John Doe" class="form-control" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="customerEmail" class="form-label">Customer Email:</label>
                                    <input type="email" name="customerEmail" id="customerEmail"
                                        value="john@example.com" class="form-control" required>
                                </div>

                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Pay Now</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const paymentMethodsInput = document.getElementById('paymentMethodsInput');

        document.getElementById('paymentForm').addEventListener('submit', (e) => {
            e.preventDefault();
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'paymentMethods';
            hiddenInput.value = [...paymentMethodsInput.selectedOptions]
                .map(option => option.value)
                .join(',');
            e.target.appendChild(hiddenInput);
            e.target.submit();
        });
    </script>
</body>
</html>
