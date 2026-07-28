from dataclasses import dataclass, field
from typing import Dict, Any

@dataclass(frozen=True)
class AuditTrailEntry:
    """
    Sebuah Value Object yang merepresentasikan catatan log audit.
    Menggunakan frozen=True agar objek bersifat Immutable (tidak bisa diubah setelah dibuat),
    menjamin integritas data audit.
    """
    asset_id: str
    name: str
    asset_type: str
    calculated_at: str
    purchase_cost: float
    depreciation_amount: float
    remaining_value: float
    details: Dict[str, Any] = field(default_factory=dict)

    def to_dict(self) -> Dict[str, Any]:
        """Konversi data log audit ke standard dictionary untuk respon JSON."""
        return {
            "asset_id": self.asset_id,
            "name": self.name,
            "asset_type": self.asset_type,
            "calculated_at": self.calculated_at,
            "purchase_cost": self.purchase_cost,
            "depreciation_amount": self.depreciation_amount,
            "remaining_value": self.remaining_value,
            "details": self.details
        }
