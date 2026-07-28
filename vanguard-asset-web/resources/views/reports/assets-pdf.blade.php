<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Aset VanguardAsset</title>
    <style>
        body { font-family: 'Helvetica Neue', Arial, sans-serif; color: #1e293b; margin: 0; padding: 20px; font-size: 12px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 3px solid #6366f1; padding-bottom: 15px; }
        .header h1 { color: #6366f1; margin: 0; font-size: 22px; letter-spacing: -0.5px; }
        .header p { color: #64748b; margin: 5px 0 0; font-size: 11px; }
        .summary { display: flex; justify-content: space-between; margin-bottom: 20px; }
        .summary-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 15px; text-align: center; flex: 1; margin: 0 5px; }
        .summary-box .label { font-size: 9px; text-transform: uppercase; color: #64748b; font-weight: 700; letter-spacing: 0.5px; }
        .summary-box .value { font-size: 18px; font-weight: 800; color: #1e293b; margin-top: 3px; }
        .summary-box .value.purple { color: #6366f1; }
        .summary-box .value.green { color: #10b981; }
        .summary-box .value.amber { color: #f59e0b; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th { background: #6366f1; color: white; padding: 8px 10px; text-align: left; font-size: 10px; text-transform: uppercase; letter-spacing: 0.5px; }
        td { padding: 7px 10px; border-bottom: 1px solid #e2e8f0; font-size: 11px; }
        tr:nth-child(even) td { background: #f8fafc; }
        .badge { padding: 2px 8px; border-radius: 10px; font-size: 9px; font-weight: 700; display: inline-block; }
        .badge-available { background: #dcfce7; color: #16a34a; }
        .badge-borrowed { background: #fef3c7; color: #d97706; }
        .badge-maintenance { background: #fee2e2; color: #dc2626; }
        .footer { margin-top: 30px; text-align: center; color: #94a3b8; font-size: 9px; border-top: 1px solid #e2e8f0; padding-top: 10px; }
        .date-info { text-align: right; color: #64748b; font-size: 10px; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>VanguardAsset</h1>
        <p>Laporan Inventaris Aset Perusahaan</p>
    </div>

    <div class="date-info">
        Dicetak pada: {{ now()->locale('id')->isoFormat('dddd, D MMMM Y — HH:mm') }} WIB
    </div>

    <div class="summary">
        <div class="summary-box">
            <div class="label">Total Aset</div>
            <div class="value purple">{{ $totalAssets }}</div>
        </div>
        <div class="summary-box">
            <div class="label">Tersedia</div>
            <div class="value green">{{ $availableAssets }}</div>
        </div>
        <div class="summary-box">
            <div class="label">Dipinjam</div>
            <div class="value amber">{{ $borrowedAssets }}</div>
        </div>
        <div class="summary-box">
            <div class="label">Total Nilai</div>
            <div class="value">Rp {{ number_format($totalValue, 0, ',', '.') }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 8%;">No</th>
                <th style="width: 12%;">ID Aset</th>
                <th style="width: 20%;">Nama Aset</th>
                <th style="width: 8%;">Tipe</th>
                <th style="width: 10%;">Status</th>
                <th style="width: 14%;">Biaya Perolehan</th>
                <th style="width: 12%;">Tgl Pembelian</th>
                <th style="width: 16%;">Detail</th>
            </tr>
        </thead>
        <tbody>
            @foreach($assets as $index => $asset)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td style="font-family: monospace; font-size: 10px;">{{ $asset->id }}</td>
                <td style="font-weight: 600;">{{ $asset->name }}</td>
                <td>{{ ucfirst($asset->type) }}</td>
                <td>
                    @if($asset->status === 'Available')
                        <span class="badge badge-available">Tersedia</span>
                    @elseif($asset->status === 'Borrowed')
                        <span class="badge badge-borrowed">Dipinjam</span>
                    @else
                        <span class="badge badge-maintenance">Perawatan</span>
                    @endif
                </td>
                <td>Rp {{ number_format($asset->purchase_cost, 0, ',', '.') }}</td>
                <td>{{ $asset->purchase_date->format('d/m/Y') }}</td>
                <td style="font-size: 10px;">
                    @if($asset->type === 'physical')
                        S/N: {{ $asset->detail_json['serial_number'] ?? '-' }}
                    @else
                        Exp: {{ $asset->detail_json['expiry_date'] ?? '-' }}
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dokumen ini dihasilkan secara otomatis oleh VanguardAsset OOP Logic Engine. Hak Cipta Dilindungi &copy; 2026.</p>
    </div>
</body>
</html>
