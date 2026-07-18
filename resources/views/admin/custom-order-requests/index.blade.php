@extends('admin.layouts.app')

@section('page-title', 'Custom Order Requests')
@section('page-subtitle', 'Leads submitted via the storefront custom order form')

@section('content')
    <div class="stat-card p-0">
        <div class="p-3 border-bottom">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-6 col-md-4">
                    <label class="form-label small mb-1">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Name or email">
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
                        <th>From</th>
                        {{-- <th>Received</th> --}}
                        <th>Reference</th>
                        <th>Budget</th>
                        <th>Design Details</th>
                        <th>Date</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($requests as $item)
                        <tr>
                            <td>
                                <strong>{{ $item->name }}</strong><br>
                                <span class="small text-muted">{{ $item->email }}</span><br>
                                <span class="small text-muted">{{ $item->whatsapp }}</span>
                            </td>
                            <td>
                                @if ($item->design_reference)
                                    <a href="{{ asset('storage/'.$item->design_reference) }}" target="_blank">
                                        <img src="{{ asset('storage/'.$item->design_reference) }}" class="thumb-lg" alt="Design reference">
                                    </a>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td>{{ $item->estimated_price ? '₹'.number_format($item->estimated_price, 2) : '—' }}</td>
                            <td style="max-width:320px">
                                <span class="small">{{ Str::limit($item->vision, 160) }}</span>
                            </td>
                            <td class="text-nowrap small text-muted">{{ $item->created_at->format('d M Y') }}</td>
                            <td class="text-end">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-outline-secondary" title="View" data-bs-toggle="modal" data-bs-target="#customOrderModal{{ $item->id }}"><i class="bi bi-eye"></i></button>
                                    <form action="{{ route('admin.custom-order-requests.destroy', $item) }}" method="POST"
                                          onsubmit="return confirm('Delete this request?');" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <div class="modal fade" id="customOrderModal{{ $item->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Custom Order — {{ $item->name }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        @if ($item->design_reference)
                                            <img src="{{ asset('storage/'.$item->design_reference) }}" class="img-fluid mb-3" alt="Design reference">
                                        @endif
                                        <p class="mb-2"><strong>Email:</strong> {{ $item->email }}</p>
                                        <p class="mb-2"><strong>Phone:</strong> {{ $item->whatsapp }}</p>
                                        <p class="mb-2"><strong>Budget:</strong> {{ $item->estimated_price ? '₹'.number_format($item->estimated_price, 2) : '—' }}</p>
                                        <p class="mb-0"><strong>Design Details:</strong><br>{{ $item->vision }}</p>
                                        <p class="small text-muted mt-3 mb-0">Submitted {{ $item->created_at->format('d M Y, h:i A') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-5">No custom order requests yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($requests->hasPages())
            <div class="p-3 border-top">{{ $requests->links() }}</div>
        @endif
    </div>
@endsection
