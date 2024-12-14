<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Card Payment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Card Payment</h4>
                    </div>

                    <div class="card-body">
                        <div class="mb-4">
                            <div class="row">
                                <div class="col-12">
                                    <small class="text-muted">Transaction Reference:</small>
                                    <p class="mb-0">{{ $transactionRef }}</p>
                                </div>
                            </div>
                        </div>

                        <form id="cardPaymentForm" method="POST" action="{{ route('process.card.payment') }}">
                            @csrf
                            <input type="hidden" name="transactionRef" value="{{ $transactionRef }}">

                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="cardNumber" class="form-label">Card Number</label>
                                    <input type="text" class="form-control @error('cardNumber') is-invalid @enderror"
                                        id="cardNumber" name="cardNumber" placeholder="1234 5678 9012 3456"
                                        maxlength="16" required>
                                    @error('cardNumber')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="cardExpiryMonth" class="form-label">Month</label>
                                    <select class="form-select @error('cardExpiryMonth') is-invalid @enderror"
                                        id="cardExpiryMonth" name="cardExpiryMonth" required>
                                        <option value="">Month</option>
                                        @for($i = 1; $i <= 12; $i++) <option value="{{ sprintf('%02d', $i) }}">{{
                                            sprintf('%02d', $i) }}</option>
                                            @endfor
                                    </select>
                                    @error('cardExpiryMonth')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="cardExpiryYear" class="form-label">Year</label>
                                    <select class="form-select @error('cardExpiryYear') is-invalid @enderror"
                                        id="cardExpiryYear" name="cardExpiryYear" required>
                                        <option value="">Year</option>
                                        @for($i = date('Y'); $i <= date('Y') + 10; $i++) <option value="{{ $i }}">{{ $i
                                            }}</option>
                                            @endfor
                                    </select>
                                    @error('cardExpiryYear')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="cardCvv" class="form-label">CVV</label>
                                    <input type="password" class="form-control @error('cardCvv') is-invalid @enderror"
                                        id="cardCvv" name="cardCvv" placeholder="123" maxlength="4" required>
                                    @error('cardCvv')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="pin" class="form-label">Card PIN</label>
                                    <input type="password" class="form-control @error('pin') is-invalid @enderror"
                                        id="pin" name="pin" placeholder="Enter your 4-digit PIN" maxlength="4" required>
                                    @error('pin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary w-100">
                                        Pay Now
                                    </button>
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
        document.getElementById('cardPaymentForm').addEventListener('submit', function(e) {
            // Disable the submit button to prevent double submission
            const submitButton = this.querySelector('button[type="submit"]');
            submitButton.disabled = true;
            submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';
        });

        // Format card number with spaces
        document.getElementById('cardNumber').addEventListener('input', function(e) {
            let value = this.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
            if (value.length > 16) value = value.substr(0, 16);
            this.value = value;
        });

        // Only allow numbers in CVV and PIN
        ['cardCvv', 'pin'].forEach(id => {
            document.getElementById(id).addEventListener('input', function(e) {
                this.value = this.value.replace(/[^0-9]/g, '');
            });
        });
    </script>
</body>

</html>
