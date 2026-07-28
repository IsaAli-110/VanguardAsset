from app.abstractions.base_asset import CompanyAsset
from app.inheritance.physical_asset import PhysicalAsset
from app.inheritance.digital_asset import DigitalAsset
from app.strategies.depreciation_strategy import DepreciationStrategy
from app.strategies.straight_line_strategy import StraightLineStrategy
from app.strategies.declining_balance_strategy import DecliningBalanceStrategy
from app.strategies.sum_of_years_strategy import SumOfYearsStrategy

class AssetFactory:
    """
    Menerapkan konsep Polimorfisme dengan Strategy Pattern.
    
    Method static create_asset mengembalikan objek dengan tipe superclass CompanyAsset,
    tetapi secara dinamis berisi instansi konkrit PhysicalAsset atau DigitalAsset.
    
    Untuk aset fisik, metode depresiasi dapat dipilih melalui parameter 'depreciation_method'
    yang akan di-inject sebagai Strategy Pattern ke PhysicalAsset.
    """

    # Registry strategi depresiasi yang tersedia
    STRATEGY_REGISTRY = {
        "straight_line": StraightLineStrategy,
        "declining_balance": DecliningBalanceStrategy,
        "sum_of_years": SumOfYearsStrategy,
    }

    @staticmethod
    def _resolve_strategy(method: str) -> DepreciationStrategy:
        """
        Factory Method untuk strategi depresiasi.
        Mengembalikan instance strategy berdasarkan nama metode.
        """
        strategy_class = AssetFactory.STRATEGY_REGISTRY.get(method)
        if strategy_class is None:
            raise ValueError(f"Unsupported depreciation method: {method}. "
                           f"Available: {list(AssetFactory.STRATEGY_REGISTRY.keys())}")
        return strategy_class()

    @staticmethod
    def create_asset(data: dict) -> CompanyAsset:
        asset_type = data.get("type", "").lower()
        
        # Ekstrak field dasar
        asset_id = data.get("asset_id")
        name = data.get("name")
        purchase_cost = float(data.get("purchase_cost", 0.0))
        purchase_date = data.get("purchase_date")
        
        if asset_type == "physical":
            # Resolve strategy dari parameter 'depreciation_method'
            method = data.get("depreciation_method", "straight_line")
            strategy = AssetFactory._resolve_strategy(method)

            return PhysicalAsset(
                asset_id=asset_id,
                name=name,
                purchase_cost=purchase_cost,
                purchase_date=purchase_date,
                serial_number=data.get("serial_number"),
                maintenance_interval=int(data.get("maintenance_interval", 0)),
                depreciation_strategy=strategy
            )
        elif asset_type == "digital":
            return DigitalAsset(
                asset_id=asset_id,
                name=name,
                purchase_cost=purchase_cost,
                purchase_date=purchase_date,
                license_key=data.get("license_key"),
                expiry_date=data.get("expiry_date")
            )
        else:
            raise ValueError(f"Unsupported asset type for polymorphic creation: {asset_type}")
