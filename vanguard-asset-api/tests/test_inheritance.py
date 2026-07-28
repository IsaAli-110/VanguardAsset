"""
Test Pewarisan: Menguji PhysicalAsset dan DigitalAsset.
Membuktikan pilar Pewarisan — subclass mewarisi dan memperluas kelas induk.
"""
import pytest
from datetime import date
from app.abstractions.base_asset import CompanyAsset
from app.inheritance.physical_asset import PhysicalAsset
from app.inheritance.digital_asset import DigitalAsset


def test_physical_asset_is_company_asset():
    """PhysicalAsset adalah subclass dari CompanyAsset."""
    asset = PhysicalAsset(
        asset_id="INH-001", name="Server", purchase_cost=50000000.0,
        purchase_date=date(2024, 1, 1), serial_number="SRV-001",
        maintenance_interval=180
    )
    assert isinstance(asset, CompanyAsset)
    assert isinstance(asset, PhysicalAsset)


def test_digital_asset_is_company_asset():
    """DigitalAsset adalah subclass dari CompanyAsset."""
    asset = DigitalAsset(
        asset_id="INH-002", name="Office License", purchase_cost=2000000.0,
        purchase_date=date(2024, 1, 1), license_key="ABCD-1234-EFGH",
        expiry_date=date(2027, 1, 1)
    )
    assert isinstance(asset, CompanyAsset)
    assert isinstance(asset, DigitalAsset)


def test_physical_asset_has_extra_attributes():
    """PhysicalAsset punya atribut serial_number dan maintenance_interval."""
    asset = PhysicalAsset(
        asset_id="INH-003", name="Printer", purchase_cost=5000000.0,
        purchase_date=date(2024, 6, 1), serial_number="PRT-555",
        maintenance_interval=90
    )
    assert asset.serial_number == "PRT-555"
    assert asset.maintenance_interval == 90


def test_digital_asset_has_extra_attributes():
    """DigitalAsset punya atribut license_key dan expiry_date."""
    asset = DigitalAsset(
        asset_id="INH-004", name="Adobe CC", purchase_cost=3000000.0,
        purchase_date=date(2024, 1, 1), license_key="ADOBE-XXXX-YYYY",
        expiry_date=date(2026, 1, 1)
    )
    assert asset.license_key == "ADOBE-XXXX-YYYY"
    assert asset.expiry_date == date(2026, 1, 1)


def test_physical_asset_depreciation_future_date():
    """Aset dengan purchase_date di masa depan = depresiasi 0."""
    asset = PhysicalAsset(
        asset_id="INH-005", name="Future Laptop", purchase_cost=10000000.0,
        purchase_date=date(2099, 1, 1), serial_number="FTR-001",
        maintenance_interval=365
    )
    assert asset.calculate_depreciation() == 0.0


def test_digital_asset_depreciation_expired():
    """Aset digital yang sudah expired = depresiasi penuh (sama dengan purchase_cost)."""
    asset = DigitalAsset(
        asset_id="INH-006", name="Old License", purchase_cost=1000000.0,
        purchase_date=date(2020, 1, 1), license_key="OLD-1234",
        expiry_date=date(2022, 1, 1)
    )
    assert asset.calculate_depreciation() == 1000000.0


def test_audit_details_contain_strategy_info():
    """get_audit_details() mengembalikan info strategi depresiasi."""
    asset = PhysicalAsset(
        asset_id="INH-007", name="Router", purchase_cost=2000000.0,
        purchase_date=date(2024, 1, 1), serial_number="RTR-001",
        maintenance_interval=180
    )
    details = asset.get_audit_details()
    assert "depreciation_strategy" in details
    assert "strategy_description" in details
