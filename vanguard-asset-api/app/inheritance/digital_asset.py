from datetime import date
from typing import Dict, Any
from app.abstractions.base_asset import CompanyAsset

class DigitalAsset(CompanyAsset):
    """
    Subclass yang mewarisi CompanyAsset.
    Merepresentasikan aset digital (software/lisensi) dengan penyusutan proporsional
    terhadap sisa hari masa aktif lisensi.
    
    Catatan: DigitalAsset menggunakan logika depresiasi berbasis expiry_date yang unik,
    bukan Strategy Pattern, karena formula depresiasi digital terikat pada masa lisensi.
    """
    def __init__(self, asset_id: str, name: str, purchase_cost: float, purchase_date: date, 
                 license_key: str, expiry_date: date):
        # Memanggil konstruktor superclass
        super().__init__(asset_id, name, purchase_cost, purchase_date)
        self.license_key = license_key
        self.expiry_date = expiry_date

    def calculate_depreciation(self) -> float:
        """
        Penyusutan linier proporsional terhadap sisa waktu lisensi.
        Semakin mendekati tanggal expired, semakin besar depresiasinya.
        """
        current_date = date.today()
        
        total_days = (self.expiry_date - self.purchase_date).days
        if total_days <= 0:
            return self.purchase_cost
            
        if current_date >= self.expiry_date:
            return self.purchase_cost
            
        if current_date <= self.purchase_date:
            return 0.0
            
        remaining_days = (self.expiry_date - current_date).days
        remaining_value = self.purchase_cost * (remaining_days / total_days)
        depreciation_amount = self.purchase_cost - remaining_value
        return round(min(self.purchase_cost, max(0.0, depreciation_amount)), 2)

    def get_audit_details(self) -> Dict[str, Any]:
        """Implementasi spesifik untuk DigitalAsset."""
        current_date = date.today()
        total_days = (self.expiry_date - self.purchase_date).days
        remaining_days = (self.expiry_date - current_date).days if current_date < self.expiry_date else 0
        return {
            "license_key_masked": f"{self.license_key[:4]}****" if len(self.license_key) > 4 else "****",
            "expiry_date": self.expiry_date.strftime("%Y-%m-%d"),
            "total_license_days": max(0, total_days),
            "remaining_license_days": max(0, remaining_days),
            "depreciation_strategy": "license_proportional",
            "strategy_description": "Linier proporsional terhadap sisa masa lisensi"
        }
