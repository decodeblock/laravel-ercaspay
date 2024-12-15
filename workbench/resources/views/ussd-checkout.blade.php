<!-- resources/views/ussd-checkout.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>USSD Checkout</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">USSD Checkout</h4>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('process.ussd') }}">
                            @csrf
                            <div class="form-group">
                                <label for="transactionRef">Transaction Reference</label>
                                <input type="text" name="transactionRef" class="form-control readonly-field" id="transactionRef"
                                    value="{{ $transactionRef }}" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="bank" class="form-label">Select Bank:</label>
                                <select name="bankName" id="bank" class="form-select" required>
                                    <option value="">Choose a bank...</option>
                                    @foreach($banks as $bank)
                                        <option value="{{ $bank }}">
                                            {{ $bank }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    Proceed with USSD
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
