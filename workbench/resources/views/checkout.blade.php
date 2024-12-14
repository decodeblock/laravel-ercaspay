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
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Select Payment Method</h4>
                    </div>

                    <div class="card-body">
                        <div class="mb-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <small class="text-muted">Transaction Ref:</small>
                                    <p class="mb-0">{{ $transactionRef }}</p>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted">Payment Ref:</small>
                                    <p class="mb-0">{{ $paymentRef }}</p>
                                </div>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('checkout.process') }}">
                            @csrf
                            <input type="hidden" name="transactionRef" value="{{ $transactionRef }}" required>
                            <input type="hidden" name="paymentRef" value="{{ $paymentRef }}" required>

                            <div class="d-grid gap-2">
                                @foreach ($paymentMethods as $paymentMethod)
                                    <button type="submit"
                                            name="paymentMethod"
                                            value="{{ $paymentMethod }}"
                                            class="btn btn-primary">
                                        Pay with {{ ucwords(str_replace('_', ' ', $paymentMethod)) }}
                                    </button>
                                @endforeach
                            </div>
                        </form>

                        @if(session('error'))
                            <div class="alert alert-danger mt-3">
                                {{ session('error') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
