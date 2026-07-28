<?php

namespace App\Services;

use App\Models\Asset;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class MaintenanceSchedulerService
{
    /**
     * Hitung aset fisik mana yang sudah waktunya atau terlambat maintenance.
     *
     * Logika:
     * - Ambil semua aset fisik
     * - Hitung hari sejak last_maintenance_date (atau purchase_date jika belum pernah)
     * - Bandingkan dengan maintenance_interval
     *
     * @return Collection
     */
    public static function getMaintenanceDue(): Collection
    {
        $physicalAssets = Asset::where('type', 'physical')->get();
        $today = Carbon::now();

        return $physicalAssets->filter(function ($asset) use ($today) {
            $interval = (int) ($asset->detail_json['maintenance_interval'] ?? 0);
            if ($interval <= 0) return false;

            // Gunakan last_maintenance_date jika ada, fallback ke purchase_date
            $lastMaintenance = $asset->last_maintenance_date
                ? Carbon::parse($asset->last_maintenance_date)
                : Carbon::parse($asset->purchase_date);

            $daysSinceMaintenance = $lastMaintenance->diffInDays($today);

            return $daysSinceMaintenance >= $interval;
        })->values();
    }

    /**
     * Hitung aset fisik yang akan mendekati jadwal maintenance (dalam 14 hari ke depan).
     *
     * @return Collection
     */
    public static function getMaintenanceUpcoming(): Collection
    {
        $physicalAssets = Asset::where('type', 'physical')->get();
        $today = Carbon::now();

        return $physicalAssets->filter(function ($asset) use ($today) {
            $interval = (int) ($asset->detail_json['maintenance_interval'] ?? 0);
            if ($interval <= 0) return false;

            $lastMaintenance = $asset->last_maintenance_date
                ? Carbon::parse($asset->last_maintenance_date)
                : Carbon::parse($asset->purchase_date);

            $daysSinceMaintenance = $lastMaintenance->diffInDays($today);
            $daysUntilDue = $interval - $daysSinceMaintenance;

            // Akan due dalam 1-14 hari (tapi belum overdue)
            return $daysUntilDue > 0 && $daysUntilDue <= 14;
        })->values();
    }

    /**
     * Hitung sisa hari hingga maintenance berikutnya untuk sebuah aset.
     *
     * @param Asset $asset
     * @return int (negatif = terlambat)
     */
    public static function daysUntilMaintenance(Asset $asset): int
    {
        $interval = (int) ($asset->detail_json['maintenance_interval'] ?? 0);
        if ($interval <= 0) return 0;

        $today = Carbon::now();
        $lastMaintenance = $asset->last_maintenance_date
            ? Carbon::parse($asset->last_maintenance_date)
            : Carbon::parse($asset->purchase_date);

        $daysSinceMaintenance = $lastMaintenance->diffInDays($today);

        return $interval - $daysSinceMaintenance;
    }
}
