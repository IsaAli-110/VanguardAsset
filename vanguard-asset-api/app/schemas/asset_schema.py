from datetime import date
from typing import Optional, Dict, Any
from pydantic import BaseModel, Field, model_validator

class AssetRequest(BaseModel):
    asset_id: str
    name: str
    type: str = Field(..., description="Asset type, must be 'physical' or 'digital'")
    purchase_cost: float = Field(..., description="Must be greater than zero")
    purchase_date: date
    
    # Strategy Pattern: pilihan metode depresiasi untuk aset fisik
    depreciation_method: Optional[str] = Field(
        default="straight_line",
        description="Depreciation strategy: 'straight_line', 'declining_balance', or 'sum_of_years'"
    )
    
    # Physical asset fields
    serial_number: Optional[str] = None
    maintenance_interval: Optional[int] = None
    
    # Digital asset fields
    license_key: Optional[str] = None
    expiry_date: Optional[date] = None

    @model_validator(mode='after')
    def validate_type_specific_fields(self) -> 'AssetRequest':
        asset_type = self.type.lower()
        if asset_type == "physical":
            if not self.serial_number or self.serial_number.strip() == "":
                raise ValueError("serial_number is required and cannot be empty for physical assets.")
            if self.maintenance_interval is None or self.maintenance_interval <= 0:
                raise ValueError("maintenance_interval must be a positive integer representing days.")
        elif asset_type == "digital":
            if not self.license_key or self.license_key.strip() == "":
                raise ValueError("license_key is required and cannot be empty for digital assets.")
            if not self.expiry_date:
                raise ValueError("expiry_date is required for digital assets.")
            if self.expiry_date < self.purchase_date:
                raise ValueError("expiry_date must be greater than or equal to purchase_date.")
        else:
            raise ValueError("type must be either 'physical' or 'digital'.")
        return self

class DepreciationResponse(BaseModel):
    asset_id: str
    name: str
    type: str
    purchase_cost: float
    purchase_date: date
    depreciation_amount: float
    remaining_value: float
    audit_trail: Dict[str, Any]
