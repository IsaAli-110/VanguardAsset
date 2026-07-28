from typing import Dict, Any
from datetime import datetime

class AuditListener:
    """
    Listener Observer Pattern yang mencatat setiap event ke log audit.
    
    Kelas ini bertindak sebagai 'Observer' dalam pola Observer.
    Saat didaftarkan ke EventDispatcher, ia akan otomatis mencatat
    setiap event yang dipicu beserta data payload-nya.
    """

    def __init__(self):
        self._audit_records = []

    def on_event(self, data: Dict[str, Any]) -> Dict[str, Any]:
        """
        Callback yang dipanggil saat event dipicu.
        Mencatat event ke daftar audit internal.
        
        Args:
            data: Payload data dari event yang dipicu.
            
        Returns:
            Dict berisi konfirmasi pencatatan audit.
        """
        record = {
            "event": data.get("_event_name", "unknown"),
            "timestamp": data.get("_dispatched_at", datetime.now().strftime("%Y-%m-%d %H:%M:%S")),
            "asset_id": data.get("asset_id"),
            "asset_name": data.get("name"),
            "depreciation_amount": data.get("depreciation_amount"),
            "remaining_value": data.get("remaining_value"),
            "strategy_used": data.get("strategy_used"),
        }
        self._audit_records.append(record)

        return {
            "listener": "AuditListener",
            "status": "recorded",
            "record_id": len(self._audit_records)
        }

    def get_records(self):
        """Ambil semua catatan audit yang telah direkam."""
        return self._audit_records.copy()
