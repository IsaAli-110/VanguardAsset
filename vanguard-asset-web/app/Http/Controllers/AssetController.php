<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\ApprovalRequest;
use App\Services\AssetEngineService;
use App\Services\MaintenanceSchedulerService;
use App\Services\SecurityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class AssetController extends Controller
{
    /**
     * Display the main application dashboard.
     */
    public function dashboard()
    {
        $user = auth()->user();

        // Hitung statistik aset di controller (hindari N+1 query di view)
        $totalAssets = Asset::count();
        $availableAssets = Asset::where('status', 'Available')->count();
        $borrowedAssets = Asset::where('status', 'Borrowed')->count();
        $maintenanceAssets = Asset::where('status', 'Under Maintenance')->count();
        $pendingRequests = ApprovalRequest::where('status', 'Pending')->count();

        // Data chart: distribusi tipe aset
        $physicalCount = Asset::where('type', 'physical')->count();
        $digitalCount = Asset::where('type', 'digital')->count();

        // Analytics: total nilai aset dan aset termahal
        $totalAssetValue = Asset::sum('purchase_cost');
        $topExpensiveAssets = Asset::orderByDesc('purchase_cost')->take(5)->get(['id', 'name', 'type', 'purchase_cost', 'purchase_date']);

        // Estimasi nilai depresiasi (20% garis lurus per tahun untuk aset fisik)
        $physicalAssets = Asset::where('type', 'physical')->get();
        $totalDepreciated = 0;
        $now = now();
        foreach ($physicalAssets as $pa) {
            $years = $pa->purchase_date ? $pa->purchase_date->diffInYears($now) : 0;
            $annualRate = 0.20;
            $depreciated = min($pa->purchase_cost * $annualRate * $years, $pa->purchase_cost);
            $totalDepreciated += $depreciated;
        }

        // Permohonan terbaru berdasarkan role
        $maintenanceDueCount = MaintenanceSchedulerService::getMaintenanceDue()->count();

        // Sparkline data: jumlah aset dibuat per hari (7 hari terakhir)
        $sparklineData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $sparklineData[] = Asset::whereDate('created_at', $date)->count();
        }

        // Persentase ketersediaan aset untuk progress ring
        $availabilityPercent = $totalAssets > 0 ? round(($availableAssets / $totalAssets) * 100) : 0;

        // Overdue borrowings: aset yang belum dikembalikan melebihi jatuh tempo
        $overdueRequests = ApprovalRequest::overdue()
            ->with(['user', 'asset'])
            ->oldest('due_date')
            ->take(5)
            ->get();

        if ($user->isAdmin() || $user->isManager()) {
            $recentRequests = ApprovalRequest::with(['user', 'asset'])
                ->where('status', 'Pending')
                ->latest()
                ->take(6)
                ->get();
        } else {
            $recentRequests = ApprovalRequest::with('asset')
                ->where('user_id', $user->id)
                ->latest()
                ->take(5)
                ->get();
        }

        return view('dashboard', compact(
            'user', 'totalAssets', 'availableAssets', 'borrowedAssets',
            'maintenanceAssets', 'pendingRequests', 'physicalCount',
            'digitalCount', 'recentRequests', 'maintenanceDueCount',
            'totalAssetValue', 'topExpensiveAssets', 'totalDepreciated',
            'sparklineData', 'availabilityPercent', 'overdueRequests'
        ));
    }

    /**
     * Display a listing of the assets based on role.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin() || $user->isManager()) {
            // Admin and Manager can see all assets
            $assets = Asset::with('borrowedBy')->get();
        } else {
            // Staff can see all available assets, and those they are currently borrowing
            $assets = Asset::with('borrowedBy')
                ->where('status', 'Available')
                ->orWhere('borrowed_by_id', $user->id)
                ->get();
        }

        return view('assets.index', compact('assets'));
    }

    /**
     * Show the form for creating a new asset (IT Admin only).
     */
    public function create()
    {
        return view('assets.create');
    }

    /**
     * Store a newly created asset in database (IT Admin only).
     */
    public function store(Request $request)
    {
        $rules = [
            'id' => 'required|string|unique:assets,id|regex:/^[A-Z0-9\-]+$/',
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:physical,digital',
            'purchase_cost' => 'required|numeric|min:0.01',
            'purchase_date' => 'required|date',
        ];

        // Type-specific field validations
        if ($request->input('type') === 'physical') {
            $rules['serial_number'] = 'required|string|max:255';
            $rules['maintenance_interval'] = 'required|integer|min:1';
        } elseif ($request->input('type') === 'digital') {
            $rules['license_key'] = 'required|string|max:255';
            $rules['expiry_date'] = 'required|date|after_or_equal:purchase_date';
        }

        $validated = $request->validate($rules);

        // Package specific details into detail_json
        $detailFields = [];
        if ($validated['type'] === 'physical') {
            $detailFields = [
                'serial_number' => $validated['serial_number'],
                'maintenance_interval' => $validated['maintenance_interval'],
            ];
        } else {
            $detailFields = [
                'license_key' => $validated['license_key'],
                'expiry_date' => $validated['expiry_date'],
            ];
        }

        $asset = Asset::create([
            'id' => strtoupper($validated['id']),
            'name' => $validated['name'],
            'type' => $validated['type'],
            'status' => 'Available',
            'purchase_cost' => $validated['purchase_cost'],
            'purchase_date' => $validated['purchase_date'],
            'detail_json' => $detailFields,
        ]);

        SecurityLogService::log(
            'asset_created',
            "Aset baru '{$asset->name}' ({$asset->id}) dibuat.",
            'info',
            Auth::id(),
            'App\\Models\\Asset',
            $asset->id,
            ['name' => $asset->name, 'type' => $asset->type]
        );

        return redirect()->route('assets.index')->with('success', 'Aset berhasil dibuat.');
    }

    /**
     * Display the specified asset.
     */
    public function show(Asset $asset)
    {
        $asset->load('borrowedBy');
        return view('assets.show', compact('asset'));
    }

    /**
     * Show the edit form for the asset (IT Admin only).
     */
    public function edit(Asset $asset)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized.');
        }

        return view('assets.edit', compact('asset'));
    }

    /**
     * Update the specified asset (IT Admin only).
     */
    public function update(Request $request, Asset $asset)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized.');
        }

        $rules = [
            'name' => 'required|string|max:255',
            'purchase_cost' => 'required|numeric|min:0.01',
            'purchase_date' => 'required|date',
        ];

        // Type-specific validation
        if ($asset->type === 'physical') {
            $rules['serial_number'] = 'required|string|max:255';
            $rules['maintenance_interval'] = 'required|integer|min:1';
        } else {
            $rules['license_key'] = 'required|string|max:255';
            $rules['expiry_date'] = 'required|date|after_or_equal:purchase_date';
        }

        $validated = $request->validate($rules);

        // Package detail fields
        $detailFields = [];
        if ($asset->type === 'physical') {
            $detailFields = [
                'serial_number' => $validated['serial_number'],
                'maintenance_interval' => $validated['maintenance_interval'],
            ];
        } else {
            $detailFields = [
                'license_key' => $validated['license_key'],
                'expiry_date' => $validated['expiry_date'],
            ];
        }

        $asset->update([
            'name' => $validated['name'],
            'purchase_cost' => $validated['purchase_cost'],
            'purchase_date' => $validated['purchase_date'],
            'detail_json' => $detailFields,
        ]);

        SecurityLogService::log(
            'asset_updated',
            "Aset '{$asset->name}' ({$asset->id}) diperbarui oleh Admin.",
            'info',
            Auth::id(),
            'App\\Models\\Asset',
            $asset->id,
            ['name' => $asset->name, 'type' => $asset->type]
        );

        return redirect()->route('assets.show', $asset->id)->with('success', 'Aset berhasil diperbarui.');
    }

    /**
     * Delete the specified asset (IT Admin only).
     */
    public function destroy(Asset $asset)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized.');
        }

        $assetId = $asset->id;
        $assetName = $asset->name;
        $assetType = $asset->type;

        $asset->delete();

        SecurityLogService::log(
            'asset_deleted',
            "Aset '{$assetName}' ({$assetId}) dihapus secara permanen oleh Admin.",
            'warning',
            Auth::id(),
            'App\\Models\\Asset',
            $assetId,
            ['name' => $assetName, 'type' => $assetType]
        );

        return redirect()->route('assets.index')->with('success', 'Aset berhasil dihapus secara permanen.');
    }

    /**
     * Trigger FastAPI OOP Engine to calculate depreciation.
     * Supports Strategy Pattern: user can choose depreciation method.
     */
    public function calculateDepreciation(Request $request, Asset $asset, AssetEngineService $engineService)
    {
        // Restrict to Admin and Manager
        if (!Auth::user()->isAdmin() && !Auth::user()->isManager()) {
            abort(403, 'Unauthorized action.');
        }

        // Ambil pilihan metode depresiasi dari request (default: straight_line)
        $method = $request->input('depreciation_method', 'straight_line');
        $result = $engineService->calculateDepreciation($asset, $method);

        if ($result['success']) {
            return redirect()->route('assets.show', $asset->id)
                ->with('depreciation_results', $result['data']);
        } else {
            return redirect()->route('assets.show', $asset->id)
                ->with('error', $result['error']);
        }
    }

    /**
     * Export all assets to CSV.
     */
    public function exportCsv()
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isManager()) {
            abort(403, 'Unauthorized action.');
        }

        $assets = Asset::all();
        $csvFileName = 'assets_report_' . date('Ymd_His') . '.csv';
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$csvFileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['ID', 'Nama', 'Tipe', 'Status', 'Biaya Pembelian', 'Tanggal Pembelian', 'Detail'];

        $callback = function() use($assets, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($assets as $asset) {
                $detailStr = '';
                if ($asset->type === 'physical') {
                    $detailStr = 'S/N: ' . ($asset->detail_json['serial_number'] ?? '') . ' | Maintenance: ' . ($asset->detail_json['maintenance_interval'] ?? '') . ' days';
                } else {
                    $detailStr = 'Key: ' . ($asset->detail_json['license_key'] ?? '') . ' | Expiry: ' . ($asset->detail_json['expiry_date'] ?? '');
                }

                fputcsv($file, [
                    $asset->id,
                    $asset->name,
                    ucfirst($asset->type),
                    $asset->status,
                    $asset->purchase_cost,
                    $asset->purchase_date,
                    $detailStr
                ]);
            }

            fclose($file);
        };

        SecurityLogService::log(
            'assets_exported',
            "Ekspor laporan aset ke CSV dilakukan oleh Admin/Manajer.",
            'info',
            Auth::id(),
            'App\\Models\\Asset',
            '*'
        );

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Display maintenance scheduler dashboard.
     */
    public function maintenance()
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->isManager()) {
            abort(403, 'Unauthorized action.');
        }

        $dueAssets = MaintenanceSchedulerService::getMaintenanceDue();
        $upcomingAssets = MaintenanceSchedulerService::getMaintenanceUpcoming();
        $totalPhysical = Asset::where('type', 'physical')->count();

        return view('assets.maintenance', compact('dueAssets', 'upcomingAssets', 'totalPhysical'));
    }

    /**
     * Mark an asset as maintained (reset last_maintenance_date to today).
     */
    public function markMaintained(Asset $asset)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $asset->update(['last_maintenance_date' => now()]);

        SecurityLogService::log(
            'asset_maintained',
            "Aset '{$asset->name}' ({$asset->id}) ditandai sudah maintenance.",
            'info',
            auth()->id(),
            'App\\Models\\Asset',
            $asset->id
        );

        return redirect()->route('assets.maintenance')
            ->with('success', "Aset '{$asset->name}' berhasil ditandai sudah maintenance.");
    }

    /**
     * Export all assets to PDF report.
     */
    public function exportPdf()
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isManager()) {
            abort(403, 'Unauthorized action.');
        }

        $assets = Asset::all();
        $totalAssets = $assets->count();
        $availableAssets = $assets->where('status', 'Available')->count();
        $borrowedAssets = $assets->where('status', 'Borrowed')->count();
        $totalValue = $assets->sum('purchase_cost');

        $pdf = Pdf::loadView('reports.assets-pdf', compact(
            'assets', 'totalAssets', 'availableAssets', 'borrowedAssets', 'totalValue'
        ));
        $pdf->setPaper('a4', 'portrait');

        SecurityLogService::log(
            'assets_pdf_exported',
            'Ekspor laporan aset ke PDF dilakukan oleh Admin/Manajer.',
            'info',
            Auth::id(),
            'App\\Models\\Asset',
            '*'
        );

        return $pdf->download('laporan_aset_' . date('Ymd_His') . '.pdf');
    }
}
