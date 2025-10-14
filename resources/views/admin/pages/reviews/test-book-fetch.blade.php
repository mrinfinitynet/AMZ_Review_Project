@extends('admin.partials.master')

@section('master')
    <!-- Page Header -->
    <div class="top-header">
        <div class="page-title">
            <h2>Book Data Fetcher</h2>
            <p class="page-subtitle">Extract book title and cover from Amazon (Educational Purpose)</p>
        </div>
    </div>

    <!-- Warning Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-warning">
                <h5 class="alert-heading">
                    <i class="mdi mdi-alert"></i> Educational Purpose Only
                </h5>
                <p class="mb-0">
                    This feature is for learning purposes. For production use, please use Amazon's official Product Advertising API.
                </p>
            </div>
        </div>
    </div>

    <!-- Fetch Card -->
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">Fetch Book Data by ASIN</h5>

                    <div class="mb-3">
                        <label for="asin-input" class="form-label">Amazon ASIN</label>
                        <div class="input-group">
                            <input type="text"
                                   class="form-control"
                                   id="asin-input"
                                   placeholder="Enter ASIN (e.g., B0FGW5QTD3)"
                                   value="B0FGW5QTD3">
                            <button class="btn btn-primary" onclick="fetchBookData()">
                                <i class="mdi mdi-cloud-download"></i> Fetch Data
                            </button>
                        </div>
                        <small class="text-muted">
                            The ASIN is a unique identifier for Amazon products, typically 10 characters.
                        </small>
                    </div>

                    <div id="loading" class="text-center py-4" style="display: none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Fetching book data from Amazon...</p>
                    </div>

                    <div id="error-message" class="alert alert-danger" style="display: none;"></div>

                    <div id="result" style="display: none;">
                        <hr>
                        <h5 class="mb-3">Result:</h5>

                        <div class="row">
                            <div class="col-md-4 text-center mb-3" id="image-container" style="display: none;">
                                <img id="book-image"
                                     src=""
                                     alt="Book Cover"
                                     class="img-fluid rounded shadow"
                                     style="max-height: 300px;">
                            </div>
                            <div class="col-md-8" id="details-container">
                                <div class="mb-3">
                                    <strong>Title:</strong>
                                    <p id="book-title" class="mb-0"></p>
                                </div>
                                <div class="mb-3">
                                    <strong>ASIN:</strong>
                                    <p id="book-asin" class="mb-0"></p>
                                </div>
                                <div class="mb-3">
                                    <strong>Amazon URL:</strong>
                                    <p class="mb-0">
                                        <a id="book-url" href="" target="_blank" class="text-primary">
                                            <i class="mdi mdi-open-in-new"></i> View on Amazon
                                        </a>
                                    </p>
                                </div>
                                <div class="mb-3" id="image-url-container" style="display: none;">
                                    <strong>Image URL:</strong>
                                    <p class="mb-0">
                                        <input type="text"
                                               id="image-url"
                                               class="form-control form-control-sm"
                                               readonly>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Sample ASINs</h5>
                    <p class="text-muted small mb-3">Click to try these ASINs from your database:</p>

                    <div class="list-group">
                        <a href="#" class="list-group-item list-group-item-action" onclick="tryAsin('B0FGW5QTD3'); return false;">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">B0FGW5QTD3</h6>
                            </div>
                            <p class="mb-1 small text-muted">CLT Study Guide</p>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action" onclick="tryAsin('B0FCSGDF8V'); return false;">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">B0FCSGDF8V</h6>
                            </div>
                            <p class="mb-1 small text-muted">Project #3-18</p>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action" onclick="tryAsin('B0F2JGFVXD'); return false;">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">B0F2JGFVXD</h6>
                            </div>
                            <p class="mb-1 small text-muted">Project #19-28</p>
                        </a>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="mdi mdi-information"></i> How It Works
                    </h6>
                    <ul class="small mb-0">
                        <li>Fetches data from Amazon product page</li>
                        <li>Extracts title and cover image</li>
                        <li>Results cached for 24 hours</li>
                        <li>Rate-limited to prevent blocking</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
    function tryAsin(asin) {
        document.getElementById('asin-input').value = asin;
        fetchBookData();
    }

    function fetchBookData() {
        const asin = document.getElementById('asin-input').value.trim();

        if (!asin) {
            showError('Please enter an ASIN');
            return;
        }

        // Show loading
        document.getElementById('loading').style.display = 'block';
        document.getElementById('result').style.display = 'none';
        document.getElementById('error-message').style.display = 'none';

        // Fetch data
        fetch('{{ route("admin.review.fetchBookData") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ asin: asin })
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('loading').style.display = 'none';

            if (data.success) {
                displayResult(data);
            } else {
                showError(data.error || 'Failed to fetch book data');
            }
        })
        .catch(error => {
            document.getElementById('loading').style.display = 'none';
            showError('Network error: ' + error.message);
        });
    }

    function displayResult(data) {
        document.getElementById('result').style.display = 'block';

        // Title
        document.getElementById('book-title').textContent = data.title || 'Not found';

        // ASIN
        document.getElementById('book-asin').textContent = data.asin;

        // URL
        const urlElement = document.getElementById('book-url');
        urlElement.href = data.url;
        urlElement.textContent = data.url;

        // Image
        if (data.image) {
            document.getElementById('book-image').src = data.image;
            document.getElementById('image-container').style.display = 'block';
            document.getElementById('image-url').value = data.image;
            document.getElementById('image-url-container').style.display = 'block';
        } else {
            document.getElementById('image-container').style.display = 'none';
            document.getElementById('image-url-container').style.display = 'none';
        }
    }

    function showError(message) {
        const errorDiv = document.getElementById('error-message');
        errorDiv.textContent = message;
        errorDiv.style.display = 'block';
    }

    // Allow Enter key
    document.getElementById('asin-input').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            fetchBookData();
        }
    });
    </script>
@endsection
