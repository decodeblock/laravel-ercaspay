<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>USSD Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">USSD Payment Details</h4>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info mb-4">
                            <h5 class="alert-heading">Payment Instructions</h5>
                            <p class="mb-1"><strong>USSD Code:</strong> {{ $responseBody['ussdCode'] }}</p>
                            <p class="mb-0"><strong>Dial:</strong> {{ $responseBody['paymentCode'] }}</p>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th width="40%">Amount</th>
                                        <td>{{ number_format($responseBody['amount'], 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Transaction Reference</th>
                                        <td>{{ $responseBody['transactionReference'] }}</td>
                                    </tr>
                                    <tr>
                                        <th>Gateway Reference</th>
                                        <td>{{ $responseBody['gatewayReference'] }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>
                                            <span class="badge bg-success">{{ $responseBody['status'] }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Gateway Message</th>
                                        <td>{{ $responseBody['gatewayMessage'] }}</td>
                                    </tr>
                                    <tr>
                                        <th>Expires In</th>
                                        <td>
                                            <span id="countdown" class="text-danger fw-bold"></span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="text-center mt-4">
                            <button class="btn btn-primary me-2" onclick="window.print()">
                                <i class="bi bi-printer"></i> Print Details
                            </button>
                            <button class="btn btn-secondary" onclick="window.history.back()">
                                Go Back
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Set the initial time from PHP variable
        let timeLeft = {{ $responseBody['expires_in'] }};
        const countdownElement = document.getElementById('countdown');

        function updateCountdown() {
            if (timeLeft <= 0) {
                countdownElement.innerHTML = '<span class="text-danger">Expired!</span>';
                return;
            }

            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;

            // Format the time
            const formattedTime = `${minutes}:${seconds.toString().padStart(2, '0')}`;
            countdownElement.textContent = `${formattedTime}`;

            timeLeft--;
        }

        // Update countdown immediately and then every second
        updateCountdown();
        const countdownInterval = setInterval(() => {
            updateCountdown();
            if (timeLeft < 0) {
                clearInterval(countdownInterval);
            }
        }, 1000);
    </script>
</body>
</html>
