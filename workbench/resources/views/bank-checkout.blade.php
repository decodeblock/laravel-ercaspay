<!DOCTYPE html>
<html>
<head>
    <title>Transaction Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-group {
            margin-bottom: 1rem;
        }
        .readonly-field {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Transaction Details</h4>
                    </div>
                    <div class="card-body">
                        <form>
                            <div class="form-group">
                                <label for="status">Status</label>
                                <input type="text" class="form-control readonly-field" id="status"
                                    value="{{ $responseBody['status'] }}" readonly>
                            </div>

                            <div class="form-group">
                                <label for="gatewayMessage">Gateway Message</label>
                                <input type="text" class="form-control readonly-field" id="gatewayMessage"
                                    value="{{ $responseBody['gatewayMessage'] }}" readonly>
                            </div>

                            <div class="form-group">
                                <label for="transactionReference">Transaction Reference</label>
                                <input type="text" class="form-control readonly-field" id="transactionReference"
                                    value="{{ $responseBody['transactionReference'] }}" readonly>
                            </div>

                            <div class="form-group">
                                <label for="amount">Amount</label>
                                <input type="text" class="form-control readonly-field" id="amount"
                                    value="{{ number_format($responseBody['amount'], 2) }}" readonly>
                            </div>

                            <div class="form-group">
                                <label for="accountNumber">Account Number</label>
                                <input type="text" class="form-control readonly-field" id="accountNumber"
                                    value="{{ $responseBody['accountNumber'] }}" readonly>
                            </div>

                            <div class="form-group">
                                <label for="accountEmail">Account Email</label>
                                <input type="email" class="form-control readonly-field" id="accountEmail"
                                    value="{{ $responseBody['accountEmail'] }}" readonly>
                            </div>

                            <div class="form-group">
                                <label for="accountName">Account Name</label>
                                <input type="text" class="form-control readonly-field" id="accountName"
                                    value="{{ $responseBody['accountName'] }}" readonly>
                            </div>

                            <div class="form-group">
                                <label for="accountReference">Account Reference</label>
                                <input type="text" class="form-control readonly-field" id="accountReference"
                                    value="{{ $responseBody['accountReference'] }}" readonly>
                            </div>

                            <div class="form-group">
                                <label for="bankName">Bank Name</label>
                                <input type="text" class="form-control readonly-field" id="bankName"
                                    value="{{ $responseBody['bankName'] }}" readonly>
                            </div>

                            <div class="form-group">
                                <label for="expires_in">Expires In (seconds)</label>
                                <input type="text" class="form-control readonly-field" id="expires_in"
                                    value="{{ $responseBody['expires_in'] }}" readonly>
                            </div>
                        </form>

                        <div class="mt-3">
                            <div class="alert alert-info">
                                This transaction will expire in <span id="countdown">{{ $responseBody['expires_in'] }}</span> seconds
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Countdown timer
        let timeLeft = {{ $responseBody['expires_in'] }};
        const countdownElement = document.getElementById('countdown');

        const timer = setInterval(() => {
            timeLeft--;
            countdownElement.textContent = timeLeft;

            if (timeLeft <= 0) {
                clearInterval(timer);
                countdownElement.parentElement.classList.remove('alert-info');
                countdownElement.parentElement.classList.add('alert-danger');
                countdownElement.parentElement.textContent = 'Transaction has expired!';
            }
        }, 1000);
    </script>
</body>
</html>
