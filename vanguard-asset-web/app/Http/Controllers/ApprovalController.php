<?php

namespace App\Http\Controllers;

use App\Models\ApprovalRequest;
use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class ApprovalController extends Controller
{
    /**
     * Display a listing of requests based on roles.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->isManager() || $user->isAdmin()) {
            $requests = ApprovalRequest::with(['user', 'asset', 'reviewedByUser'])->latest()->get();
        } else {
            $requests = ApprovalRequest::with(['asset', 'reviewedByUser'])->where('user_id', $user->id)->latest()->get();
        }

        return view('approvals.index', compact('requests'));
    }

    /**
     * Store a borrowing ticket request (Staff/Employee only).
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user->isStaff() && !$user->isAdmin()) {
            return redirect()->back()->with('error', 'Only employees or admins can request assets.');
        }

        $validated = $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'duration' => 'required|integer|min:1|max:365',
            'reason' => 'required|string|max:1000',
        ]);

        try {
            DB::transaction(function () use ($validated, $user) {
                $asset = Asset::where('id', $validated['asset_id'])->lockForUpdate()->firstOrFail();

                if ($asset->status !== 'Available') {
                    throw new Exception("Asset is currently not available for borrowing (Current Status: {$asset->status}).");
                }

                ApprovalRequest::create([
                    'user_id' => $user->id,
                    'asset_id' => $asset->id,
                    'duration' => $validated['duration'],
                    'reason' => $validated['reason'],
                    'status' => 'Pending'
                ]);
            });

            return redirect()->route('assets.index')->with('success', 'Borrowing request successfully submitted. Waiting for Manager approval.');

        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Approve borrowing request (Manager only).
     */
    public function approve(ApprovalRequest $approvalRequest)
    {
        if (!Auth::user()->isManager() && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        if ($approvalRequest->status !== 'Pending') {
            return redirect()->back()->with('error', 'Request has already been processed.');
        }

        try {
            DB::transaction(function () use ($approvalRequest) {
                $asset = Asset::where('id', $approvalRequest->asset_id)->lockForUpdate()->firstOrFail();

                if ($asset->status !== 'Available') {
                    throw new Exception("Cannot approve. The asset is no longer available.");
                }

                // Update request with borrowing dates
                $approvalRequest->update([
                    'status' => 'Approved',
                    'borrowed_at' => now(),
                    'due_date' => now()->addDays($approvalRequest->duration),
                    'reviewed_by' => Auth::id(),
                ]);

                // Mutate asset status and set borrower
                $asset->update([
                    'status' => 'Borrowed',
                    'borrowed_by_id' => $approvalRequest->user_id
                ]);
            });

            return redirect()->route('approvals.index')->with('success', 'Request approved. Asset has been checked out.');

        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Reject borrowing request (Manager only).
     */
    public function reject(Request $request, ApprovalRequest $approvalRequest)
    {
        if (!Auth::user()->isManager() && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        if ($approvalRequest->status !== 'Pending') {
            return redirect()->back()->with('error', 'Request has already been processed.');
        }

        $validated = $request->validate([
            'reject_reason' => 'nullable|string|max:500',
        ]);

        $approvalRequest->update([
            'status' => 'Rejected',
            'reject_reason' => $validated['reject_reason'] ?? null,
            'reviewed_by' => Auth::id(),
        ]);

        return redirect()->route('approvals.index')->with('success', 'Request rejected successfully.');
    }

    /**
     * Return asset (IT Admin/Borrower only).
     */
    public function returnAsset(Asset $asset)
    {
        $user = Auth::user();

        if (!$user->isAdmin() && $asset->borrowed_by_id !== $user->id) {
            return redirect()->back()->with('error', 'You are not authorized to return this asset.');
        }

        if ($asset->status !== 'Borrowed') {
            return redirect()->back()->with('error', 'Asset is not currently borrowed.');
        }

        try {
            DB::transaction(function () use ($asset) {
                // Update the active approval request with return timestamp
                $activeRequest = ApprovalRequest::where('asset_id', $asset->id)
                    ->where('status', 'Approved')
                    ->whereNull('returned_at')
                    ->latest('borrowed_at')
                    ->first();

                if ($activeRequest) {
                    $activeRequest->update(['returned_at' => now()]);
                }

                $asset->update([
                    'status' => 'Available',
                    'borrowed_by_id' => null
                ]);
            });

            return redirect()->back()->with('success', 'Asset successfully returned and marked as Available.');

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to return asset: ' . $e->getMessage());
        }
    }
}
