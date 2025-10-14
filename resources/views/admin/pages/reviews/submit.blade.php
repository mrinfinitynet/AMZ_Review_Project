@extends('admin.partials.master')

@push('css')
<style>
    .submit-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        padding: 30px;
        color: white;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    }

    .submit-header h2 {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .submit-header p {
        opacity: 0.9;
        font-size: 14px;
    }

    .client-tabs {
        display: flex;
        gap: 15px;
        margin-bottom: 30px;
        flex-wrap: wrap;
    }

    .client-tab {
        flex: 1;
        min-width: 150px;
        padding: 15px 25px;
        background: white;
        border: 2px solid #e0e7ff;
        border-radius: 12px;
        text-align: center;
        text-decoration: none;
        color: #4f46e5;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .client-tab:hover {
        background: #4f46e5;
        color: white;
        border-color: #4f46e5;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
    }

    .client-tab.active {
        background: #4f46e5;
        color: white;
        border-color: #4f46e5;
    }

    .control-panel {
        background: white;
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 30px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    }

    .pending-queue {
        background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        border-left: 4px solid #f59e0b;
    }

    .pending-queue h5 {
        color: #92400e;
        font-weight: 600;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .pending-ids {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .pending-id {
        background: white;
        padding: 8px 15px;
        border-radius: 8px;
        font-weight: 600;
        color: #1f2937;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        font-size: 13px;
    }

    .action-buttons {
        display: flex;
        gap: 15px;
        margin-top: 20px;
    }

    .btn-submit {
        flex: 1;
        padding: 15px 30px;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border: none;
        border-radius: 12px;
        color: white;
        font-weight: 600;
        font-size: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
    }

    .btn-submit.running {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    }

    .btn-clear {
        padding: 15px 30px;
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        border: none;
        border-radius: 12px;
        color: white;
        font-weight: 600;
        font-size: 16px;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
    }

    .btn-clear:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
        color: white;
    }

    .history-section {
        margin-top: 40px;
    }

    .history-header {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid #e5e7eb;
    }

    .history-header h3 {
        font-size: 22px;
        font-weight: 700;
        color: white;
        margin: 0;
    }

    .history-badge {
        background: #4f46e5;
        color: white;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 600;
    }

    .review-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        border: 1px solid #f3f4f6;
        transition: all 0.3s ease;
    }

    .review-card:hover {
        box-shadow: 0 4px 20px rgba(0,0,0,0.12);
        transform: translateY(-2px);
    }

    .review-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 1px solid #f3f4f6;
    }

    .review-title {
        font-size: 16px;
        font-weight: 700;
        color: #4f46e5;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .review-actions {
        display: flex;
        gap: 8px;
    }

    .btn-action {
        padding: 8px 15px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        border: none;
        display: flex;
        align-items: center;
        gap: 5px;
        transition: all 0.2s ease;
    }

    .btn-check {
        background: #eff6ff;
        color: #2563eb;
    }

    .btn-check:hover {
        background: #2563eb;
        color: white;
    }

    .btn-edit {
        background: #f0fdf4;
        color: #16a34a;
    }

    .btn-edit:hover {
        background: #16a34a;
        color: white;
    }

    .review-message {
        color: #6b7280;
        font-size: 14px;
        line-height: 1.6;
        margin-bottom: 15px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .review-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }

    .review-info {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
    }

    .info-item {
        font-size: 13px;
        color: #6b7280;
    }

    .info-item strong {
        color: #1f2937;
        font-weight: 600;
    }

    .rating-stars {
        color: #f59e0b;
        font-size: 16px;
    }

    .status-badge {
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-approved {
        background: #d1fae5;
        color: #065f46;
    }

    .status-pending {
        background: #fef3c7;
        color: #92400e;
    }

    .status-rejected {
        background: #fee2e2;
        color: #991b1b;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #9ca3af;
    }

    .empty-state i {
        font-size: 64px;
        margin-bottom: 20px;
        opacity: 0.5;
    }

    .spinner {
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
</style>
@endpush

@section('master')

<!-- Page Header -->
<div class="submit-header">
    <h2><i class="mdi mdi-send-check"></i> Start Posting Reviews</h2>
    <p>Submit and manage your Amazon review campaigns efficiently</p>
</div>

<!-- Client Selection Tabs -->
<div class="client-tabs">
    @foreach($clients as $client)
        <a href="{{ route('admin.review.submit', ['type' => $client->code]) }}"
           class="client-tab {{ $type === $client->code ? 'active' : '' }}">
            <i class="mdi mdi-briefcase{{ $loop->first ? '' : '-outline' }}"></i> {{ $client->name }}
        </a>
    @endforeach
    <a href="{{ route('admin.clients.index') }}" class="client-tab" style="background: rgba(99, 102, 241, 0.1); border-color: var(--primary);">
        <i class="mdi mdi-cog"></i> Manage Clients
    </a>
</div>

<!-- Control Panel -->
<div class="control-panel">
    <!-- Pending Queue -->
    <div class="pending-queue">
        <h5>
            <i class="mdi mdi-clock-outline"></i>
            Pending Reviews Queue
            <span class="badge bg-warning text-dark">{{ count($ids) }}</span>
        </h5>
        <div class="pending-ids" id="ids">
            @if(count($ids) > 0)
                @foreach ($ids as $item)
                    @php
                        $project = \App\Models\AmazonReviewProject::find($item);
                    @endphp
                    @if($project)
                        <span class="pending-id">
                            <i class="mdi mdi-account-circle"></i> Account #{{ $project->account_id }}
                        </span>
                    @endif
                @endforeach
            @else
                <p class="text-muted mb-0">No pending reviews in queue</p>
            @endif
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="action-buttons">
        <button class="btn-submit" id="submitReview">
            <i class="mdi mdi-play-circle"></i>
            <span>Start Submission</span>
        </button>
        <a onclick="return confirm('Are you sure you want to clear all history?')"
           href="{{ route('admin.review.clearHistory', ['type' => $type]) }}"
           class="btn-clear">
            <i class="mdi mdi-delete-sweep"></i>
            <span>Clear History</span>
        </a>
    </div>
</div>

<!-- History Section -->
<div class="history-section">
    <div class="history-header">
        <h3><i class="mdi mdi-history"></i> Submission History</h3>
        <span class="history-badge">{{ count($histories) }} Records</span>
    </div>

    <div class="row" id="history-list">
        @forelse ($histories as $item)
            <div class="col-lg-6 col-12">
                <div class="review-card">
                    <div class="review-header">
                        <h6 class="review-title">
                            <i class="mdi mdi-package-variant"></i>
                            Project #{{ $item->project_id }} - Review #{{ $item->review_id }}
                        </h6>
                        <div class="review-actions">
                            <a href="{{ route('admin.review.startReview', ['review_id' => $item->review_id]) }}"
                               class="btn-action btn-check">
                                <i class="mdi mdi-refresh"></i> Check
                            </a>
                            <button data-bs-toggle="modal"
                                    data-bs-target="#editReview"
                                    class="btn-action btn-edit edit-project"
                                    data-amazon-id="{{ $item->account_id }}"
                                    data-project-id="{{ $item->review_id }}">
                                <i class="mdi mdi-pencil"></i> Edit
                            </button>
                        </div>
                    </div>

                    <div class="review-message">
                        {{ $item->msg }}
                    </div>

                    <div class="review-footer">
                        <div class="review-info">
                            <div class="info-item">
                                <strong>Account:</strong> #{{ $item->account_id }}
                            </div>
                            <div class="info-item rating-stars">
                                <strong>Rating:</strong>
                                {!! str_repeat('⭐', $item->rating ?? 0) !!}
                            </div>
                        </div>
                        <span class="status-badge status-{{ $item->status ?? 'pending' }}">
                            {{ ucfirst($item->status ?? 'pending') }}
                        </span>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="empty-state">
                    <i class="mdi mdi-clipboard-text-outline"></i>
                    <h4>No Submission History</h4>
                    <p>Click "Start Submission" to begin posting reviews</p>
                </div>
            </div>
        @endforelse
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editReview" tabindex="-1" aria-labelledby="editReviewLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 15px; border: none;">
            <form action="" method="post">
                @csrf
                <div class="modal-header" style="background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%); color: white; border-radius: 15px 15px 0 0;">
                    <h5 class="modal-title" id="editReviewLabel">
                        <i class="mdi mdi-pencil-box"></i> Edit Review
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="padding: 30px;">
                    <div class="form-group">
                        <label for="account_id" style="font-weight: 600; color: #1f2937; margin-bottom: 10px;">
                            <i class="mdi mdi-account-circle"></i> Account ID
                        </label>
                        <input type="text"
                               class="form-control"
                               id="account_id"
                               name="account_id"
                               placeholder="Enter Account ID..."
                               style="border-radius: 10px; border: 2px solid #e5e7eb; padding: 12px;">
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid #f3f4f6; padding: 20px 30px;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 10px; padding: 10px 20px;">
                        <i class="mdi mdi-close"></i> Close
                    </button>
                    <button type="submit" class="btn btn-primary" style="border-radius: 10px; padding: 10px 20px; background: #4f46e5; border: none;">
                        <i class="mdi mdi-check"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('js')
<script>
    $("#submitReview").click(function(){
        let $btn = $(this);
        let $span = $btn.find('span');
        let $icon = $btn.find('i');

        if($span.text() === "Start Submission"){
            $span.text("Running...");
            $icon.removeClass('mdi-play-circle').addClass('mdi-loading spinner');
            $btn.addClass('running');
            runEvent();
        } else {
            $span.text("Start Submission");
            $icon.removeClass('mdi-loading spinner').addClass('mdi-play-circle');
            $btn.removeClass('running');
        }
    });

    const runEvent = () => {
        $.ajax({
            url: '/admin/review/start-review',
            type: 'GET',
            data: {
                'type': '{{ $type }}'
            },
            success: function (data) {
                let $btn = $("#submitReview");
                let $span = $btn.find('span');

                if ($span.text().trim() === "Running...") {
                    if(!data.status){
                        $span.text("Start Submission");
                        $btn.find('i').removeClass('mdi-loading spinner').addClass('mdi-play-circle');
                        $btn.removeClass('running');

                        toastr.info(data.msg);
                    } else {
                        runEvent();
                    }
                }

                // Update pending IDs
                if(data.ids && data.ids.length > 0) {
                    let idsHtml = '';
                    data.ids.forEach(id => {
                        idsHtml += `<span class="pending-id"><i class="mdi mdi-account-circle"></i> Account #${id}</span>`;
                    });
                    $("#ids").html(idsHtml);
                } else {
                    $("#ids").html('<p class="text-muted mb-0">No pending reviews in queue</p>');
                }

                updateHistory(data.histories);
            },
            error: function() {
                let $btn = $("#submitReview");
                $btn.find('span').text("Start Submission");
                $btn.find('i').removeClass('mdi-loading spinner').addClass('mdi-play-circle');
                $btn.removeClass('running');
                toastr.error('Failed to submit review. Please try again.');
            }
        });
    }

    const updateHistory = (histories) => {
        if (!histories || histories.length === 0) {
            $("#history-list").html(`
                <div class="col-12">
                    <div class="empty-state">
                        <i class="mdi mdi-clipboard-text-outline"></i>
                        <h4>No Submission History</h4>
                        <p>Click "Start Submission" to begin posting reviews</p>
                    </div>
                </div>
            `);
            return;
        }

        $("#history-list").html("");
        histories.forEach(item => {
            const stars = '⭐'.repeat(item.rating || 0);
            const statusClass = item.status === 'approved' ? 'approved' : (item.status === 'rejected' ? 'rejected' : 'pending');

            $("#history-list").append(`
                <div class="col-lg-6 col-12">
                    <div class="review-card">
                        <div class="review-header">
                            <h6 class="review-title">
                                <i class="mdi mdi-package-variant"></i>
                                Project #${item.project_id} - Review #${item.review_id}
                            </h6>
                            <div class="review-actions">
                                <a href="/admin/review/start-review?review_id=${item.review_id}" class="btn-action btn-check">
                                    <i class="mdi mdi-refresh"></i> Check
                                </a>
                                <button class="btn-action btn-edit edit-project"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editReview"
                                        data-amazon-id="${item.account_id}"
                                        data-project-id="${item.review_id}">
                                    <i class="mdi mdi-pencil"></i> Edit
                                </button>
                            </div>
                        </div>
                        <div class="review-message">${item.msg}</div>
                        <div class="review-footer">
                            <div class="review-info">
                                <div class="info-item">
                                    <strong>Account:</strong> #${item.account_id}
                                </div>
                                <div class="info-item rating-stars">
                                    <strong>Rating:</strong> ${stars}
                                </div>
                            </div>
                            <span class="status-badge status-${statusClass}">
                                ${item.status ? item.status.charAt(0).toUpperCase() + item.status.slice(1) : 'Pending'}
                            </span>
                        </div>
                    </div>
                </div>
            `);
        });

        // Re-bind edit button events
        bindEditButtons();
    };

    function bindEditButtons() {
        $(".edit-project").off('click').on('click', function(){
            let amazonAcc = $(this).attr("data-amazon-id");
            let id = $(this).attr("data-project-id");
            $("#account_id").val(amazonAcc);
            $("#editReview form").attr("action", `/admin/review/update-project/${id}`);
        });
    }

    // Initial bind
    bindEditButtons();
</script>
@endpush
