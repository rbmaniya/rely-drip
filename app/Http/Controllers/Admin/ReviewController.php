<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ReviewStatus;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function index(Request $request): View
    {
        $reviews = Review::query()
            ->with(['product', 'customer'])
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.reviews.index', compact('reviews'));
    }

    public function approve(Review $review): RedirectResponse
    {
        $review->update(['status' => ReviewStatus::Approved]);

        ActivityLog::record('review.approved', "Review for \"{$review->product->title}\" approved.");

        return back()->with('success', 'Review approved.');
    }

    public function reject(Review $review): RedirectResponse
    {
        $review->update(['status' => ReviewStatus::Rejected]);

        ActivityLog::record('review.rejected', "Review for \"{$review->product->title}\" rejected.");

        return back()->with('success', 'Review rejected.');
    }

    public function destroy(Review $review): RedirectResponse
    {
        ActivityLog::record('review.deleted', "Review for \"{$review->product->title}\" deleted.");

        $review->delete();

        return back()->with('success', 'Review deleted.');
    }
}
