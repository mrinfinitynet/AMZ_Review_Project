@extends('admin.partials.master')
@section('master')
<div class="col-12 equel-grid">
    <div class="grid">

        @php
            $projectId = $lastProject->project_id ?? 0;
        @endphp

        {{-- Top error summary --}}
        @if ($errors->any())
            <div class="alert alert-danger mb-3">
                <strong>There were some problems with your input:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <p class="grid-header">Add New Project</p>
        <div class="grid-body">
            <div class="item-wrapper">
                <form action="" method="POST" id="project-form">
                    @csrf

                    {{-- keep type around, prefer old() on validation bounce --}}
                    <input type="hidden" name="type" value="{{ old('type', $type) }}">

                    <div class="form-group">
                        <label>Project ID (Last: {{ $projectId }})</label>
                        <input type="number"
                               name="project_id"
                               class="form-control custom-input @error('project_id') is-invalid @enderror"
                               value="{{ old('project_id', $projectId + 1) }}">
                        @error('project_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label>Book ASIN</label>
                        <input type="text"
                               name="book_asin"
                               class="form-control custom-input @error('book_asin') is-invalid @enderror"
                               placeholder="e.g., B0FCSGDF8V"
                               value="">
                        @error('book_asin')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <small class="form-text text-muted">
                            Enter the Amazon product ASIN (e.g., B0FCSGDF8V)
                        </small>
                    </div>

                    <div class="form-group">
                        <label>Review Json - {{$lastProject->account_id}}</label>
                        <textarea name="review_json" class="form-control custom-input @error('review_json') is-invalid @enderror" id="" style="height: 400px" ></textarea>
                        @error('review_json')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <hr>

                    <div class="d-none align-items-center justify-content-between mb-2">
                        <h5 class="mb-0">Reviews</h5>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="add-review-btn">
                            + Add review
                        </button>
                    </div>

                    {{-- Start the next review-id from the server --}}
                    <div class="d-none" id="reviews-wrap" data-next-id="{{ ($lastProject->account_id ?? 0) + 1 }}">
                        {{-- (Optionally render old() rows here if you want server-side repopulation) --}}
                    </div>

                    {{-- Hidden template for JS cloning --}}

                    
                    <template class="d-none" id="review-template">
                        <div class="card mb-3 review-item" style="
                            border: 1px solid #dddd;
                            border-radius: 15px;
                            padding: 5px;
                            margin-bottom: 10px;
                            box-shadow: 5px 5px 15px #dddddd;
                        ">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <strong class="review-heading">Review</strong>
                                    <button type="button" class="btn btn-sm btn-outline-danger remove-review-btn">Remove</button>
                                </div>

                                <div class="form-group">
                                    <label>Review ID</label>
                                    <input type="text" name="review_ids[]" class="form-control custom-input">
                                </div>

                                <div class="form-group">
                                    <label>Review Title</label>
                                    <input type="text" name="review_titles[]" class="form-control custom-input">
                                </div>

                                <div class="form-group">
                                    <label>Review Description</label>
                                    <textarea name="review_descriptions[]" class="form-control custom-input" rows="3"></textarea>
                                </div>

                                <div class="form-group">
                                    <label>Review Rating (1–5)</label>
                                    <input type="number" name="ratings[]" min="1" max="5" step="1" value="5" class="form-control custom-input">
                                </div>
                            </div>
                        </div>
                    </template>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-sm btn-primary">Confirmed</button>
                        <a class="btn btn-sm btn-danger" href="{{ route('admin.review.accounts', ['type' => $type]) }}">Back</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Lightweight JS to add/remove review blocks --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const wrap = document.getElementById('reviews-wrap');
    const addBtn = document.getElementById('add-review-btn');
    const tmpl = document.getElementById('review-template');

    // Start from server-provided next id (falls back to 1)
    let nextReviewId = Number(wrap.dataset.nextId || 1);

    function renumberHeadings() {
        const items = wrap.querySelectorAll('.review-item');
        items.forEach((el, idx) => {
            const head = el.querySelector('.review-heading');
            if (head) head.textContent = 'Review #' + (idx + 1);
        });
    }

    function addReview() {
        const frag = tmpl.content.cloneNode(true);
        const item = frag.querySelector('.review-item');

        // Assign unique, incrementing review id
        const idInput = item.querySelector('input[name="review_ids[]"]');
        if (idInput) idInput.value = String(nextReviewId++);

        wrap.appendChild(frag);
        renumberHeadings();
    }

    addBtn.addEventListener('click', addReview);

    wrap.addEventListener('click', (e) => {
        if (e.target.classList.contains('remove-review-btn')) {
            const card = e.target.closest('.review-item');
            if (card) {
                const total = wrap.querySelectorAll('.review-item').length;
                // Keep at least one review block
                if (total > 1) {
                    card.remove();
                    renumberHeadings();
                }
            }
        }
    });

    // Ensure at least one review item exists on load
    if (wrap.querySelectorAll('.review-item').length === 0) {
        addReview();
    } else {
        renumberHeadings();
    }
});
</script>
@endsection
