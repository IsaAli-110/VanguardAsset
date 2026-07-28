from typing import Dict, Any
from datetime import datetime

class NotificationListener:
    """
    Listener Observer Pattern yang menghasilkan pesan notifikasi
    setiap kali event penting terjadi pada aset.
    
    Kelas ini bertindak sebagai 'Observer' dalam pola Observer.
    Berguna untuk mengirim notifikasi ke admin, manajer, atau sistem eksternal
    ketika ada perhitungan depresiasi atau perubahan status aset.
    """

    def __init__(self):
        self._notifications = []

    def on_event(self, data: Dict[str, Any]) -> Dict[str, Any]:
        """
        Callback yang dipanggil saat event dipicu.
        Menghasilkan pesan notifikasi berdasarkan jenis event.
        
        Args:
            data: Payload data dari event yang dipicu.
            
        Returns:
            Dict berisi pesan notifikasi yang dihasilkan.
        """
        event_name = data.get("_event_name", "unknown")
        asset_name = data.get("name", "Unknown Asset")
        asset_id = data.get("asset_id", "N/A")
        
        # Generate pesan notifikasi berdasarkan jenis event
        if event_name == "asset_depreciated":
            depreciation = data.get("depreciation_amount", 0)
            remaining = data.get("remaining_value", 0)
            strategy = data.get("strategy_used", "default")
            message = (
                f"[DEPRESIASI] Aset '{asset_name}' ({asset_id}) telah dihitung depresiasinya. "
                f"Penyusutan: Rp {depreciation:,.0f}, Nilai Sisa: Rp {remaining:,.0f}. "
                f"Strategi: {strategy}."
            )
        elif event_name == "asset_audit_logged":
            message = (
                f"[AUDIT] Log audit baru dicatat untuk aset '{asset_name}' ({asset_id}). "
                f"Integritas data terjamin (immutable AuditTrailEntry)."
            )
        elif event_name == "asset_created":
            message = (
                f"[ASET BARU] Aset '{asset_name}' ({asset_id}) berhasil dibuat "
                f"dan terdaftar dalam sistem."
            )
        else:
            message = f"[EVENT] Event '{event_name}' terjadi pada aset '{asset_name}' ({asset_id})."

        notification = {
            "message": message,
            "event": event_name,
            "timestamp": data.get("_dispatched_at", datetime.now().strftime("%Y-%m-%d %H:%M:%S")),
            "priority": "info"
        }

        # Tandai prioritas tinggi jika depresiasi besar
        if event_name == "asset_depreciated":
            depreciation = data.get("depreciation_amount", 0)
            cost = data.get("purchase_cost", 1)
            if cost > 0 and (depreciation / cost) > 0.5:
                notification["priority"] = "warning"

        self._notifications.append(notification)

        return {
            "listener": "NotificationListener",
            "status": "notified",
            "message": message
        }

    def get_notifications(self):
        """Ambil semua notifikasi yang telah dihasilkan."""
        return self._notifications.copy()
