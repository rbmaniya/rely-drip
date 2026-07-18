@extends('admin.layouts.app')

@section('page-title', 'Reviews')
@section('page-subtitle', 'Moderate customer product reviews')

@section('content')
    <div class="stat-card p-0">
        <div class="p-3 border-bottom">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-6 col-md-3">
                    <label class="form-label small mb-1">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All</option>
                        <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                        <option value="approved" @selected(request('status') === 'approved')>Approved</option>
                        <option value="rejected" @selected(request('status') === 'rejected')>Rejected</option>
                    </select>
                </div>
                <div class="col-6 col-md-2 d-grid">
                    <button type="submit" class="btn btn-outline-secondary">Filter</button>
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Customer</th>
                        <th>Rating</th>
                        <th>Review</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($reviews as $review)
                        <tr>
                            <td>{{ $review->product->title }}</td>
                            <td>{{ $review->customer->name }}</td>
                            <td>{{ $review->rating }} <i class="bi bi-star-fill text-warning"></i></td>
                            <td style="max-width:320px">
                                @if ($review->title)<strong>{{ $review->title }}</strong><br>@endif
                                <span class="small text-muted">{{ Str::limit($review->description, 120) }}</span>
                            </td>
                            <td><span class="badge {{ $review->status->badgeClass() }}">{{ $review->status->label() }}</span></td>
                            <td class="text-end">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-outline-secondary" title="View" data-bs-toggle="modal" data-bs-target="#reviewModal{{ $review->id }}"><i class="bi bi-eye"></i></button>
                                    @if ($review->status !== \App\Enums\ReviewStatus::Approved)
                                        <form action="{{ route('admin.reviews.approve', $review) }}" method="POST" class="d-inline">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-success" title="Approve"><i class="bi bi-check-lg"></i></button>
                                        </form>
                                    @endif
                                    @if ($review->status !== \App\Enums\ReviewStatus::Rejected)
                                        <form action="{{ route('admin.reviews.reject', $review) }}" method="POST" class="d-inline">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-warning" title="Reject"><i class="bi bi-x-lg"></i></button>
                                        </form>
                                    @endif
                                    <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <div class="modal fade" id="reviewModal{{ $review->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Review by {{ $review->customer->name }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="mb-2"><strong>Product:</strong> {{ $review->product->title }}</p>
                                        <p class="mb-2">
                                            <strong>Rating:</strong>
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i class="bi {{ $i <= $review->rating ? 'bi-star-fill text-warning' : 'bi-star' }}"></i>
                                            @endfor
                                        </p>
                                        @if ($review->title)
                                            <p class="mb-2"><strong>Title:</strong> {{ $review->title }}</p>
                                        @endif
                                        <p class="mb-2"><strong>Status:</strong> <span class="badge {{ $review->status->badgeClass() }}">{{ $review->status->label() }}</span></p>
                                        <p class="mb-0"><strong>Description:</strong><br>{{ $review->description }}</p>
                                        <p class="small text-muted mt-3 mb-0">Submitted {{ $review->created_at->format('d M Y, h:i A') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-5">No reviews found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($reviews->hasPages())
            <div class="p-3 border-top">{{ $reviews->links() }}</div>
        @endif
    </div>
@endsection
