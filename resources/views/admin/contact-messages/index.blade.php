@extends('admin.layouts.app')

@section('page-title', 'Contact Messages')
@section('page-subtitle', 'Messages submitted via the storefront contact form')

@section('content')
    <div class="stat-card p-0">
        <div class="p-3 border-bottom">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-6 col-md-4">
                    <label class="form-label small mb-1">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Name, email or subject">
                </div>
                <div class="col-6 col-md-3">
                    <label class="form-label small mb-1">Source</label>
                    <select name="source" class="form-select">
                        <option value="">All</option>
                        <option value="general" @selected(request('source') === 'general')>General</option>
                        <option value="culture_collab" @selected(request('source') === 'culture_collab')>Culture / Collab</option>
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
                        <th>Received</th>
                        <th>From</th>
                        <th>Subject</th>
                        <th>Message</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($messages as $message)
                        <tr>
                            <td class="text-nowrap small text-muted">{{ $message->created_at->format('d M Y, h:i A') }}</td>
                            <td>
                                <strong>{{ $message->name }}</strong><br>
                                <span class="small text-muted">{{ $message->email }}</span>
                                @if ($message->mobile)
                                    <br><span class="small text-muted">{{ $message->mobile }}</span>
                                @endif
                            </td>
                            <td>
                                {{ $message->subject ?: '—' }}
                                @if ($message->country)
                                    <br><span class="small text-muted">{{ $message->country }}</span>
                                @endif
                            </td>
                            <td style="max-width:360px">
                                <span class="small">{{ Str::limit($message->message, 160) }}</span>
                            </td>
                            <td class="text-end">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-outline-secondary" title="View" data-bs-toggle="modal" data-bs-target="#messageModal{{ $message->id }}"><i class="bi bi-eye"></i></button>
                                    <form action="{{ route('admin.contact-messages.destroy', $message) }}" method="POST"
                                          onsubmit="return confirm('Delete this message?');" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <div class="modal fade" id="messageModal{{ $message->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Message from {{ $message->name }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="mb-2"><strong>Email:</strong> {{ $message->email }}</p>
                                        @if ($message->mobile)
                                            <p class="mb-2"><strong>Phone:</strong> {{ $message->mobile }}</p>
                                        @endif
                                        @if ($message->country)
                                            <p class="mb-2"><strong>Country:</strong> {{ $message->country }}</p>
                                        @endif
                                        @if ($message->subject)
                                            <p class="mb-2"><strong>Subject:</strong> {{ $message->subject }}</p>
                                        @endif
                                        <p class="mb-0"><strong>Message:</strong><br>{{ $message->message }}</p>
                                        <p class="small text-muted mt-3 mb-0">Received {{ $message->created_at->format('d M Y, h:i A') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-5">No messages received yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($messages->hasPages())
            <div class="p-3 border-top">{{ $messages->links() }}</div>
        @endif
    </div>
@endsection
