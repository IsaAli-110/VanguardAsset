"""
Test Polimorfisme: Menguji AssetFactory menghasilkan tipe yang benar.
Membuktikan pilar Polimorfisme — satu factory, berbagai tipe objek.
"""
import pytest
from datetime import date
from app.abstractions.base_asset import CompanyAsset
from app.inheritance.physical_asset import PhysicalAsset
from app.inheritance.digital_asset import DigitalAsset
from app.polymorphism.asset_factory import AssetFactory


def test_factory_creates_physical_asset():
    """Factory menghasilkan PhysicalAsset saat type='physical'."""
    asset = AssetFactory.create_asset({
        "type": "physical",
        "asset_id": "POL-001",
        "name": "Server Rack",
        "purchase_cost": 25000000.0,
        "purchase_date": date(2024, 1, 1),
        "serial_number": "SR-001",
        "maintenance_interval": 90,
        "depreciation_method": "straight_line"
    })
    assert isinstance(asset, PhysicalAsset)
    assert isinstance(asset, CompanyAsset)


def test_factory_creates_digital_asset():
    """Factory menghasilkan DigitalAsset saat type='digital'."""
    asset = AssetFactory.create_asset({
        "type": "digital",
        "asset_id": "POL-002",
        "name": "Slack Enterprise",
        "purchase_cost": 5000000.0,
        "purchase_date": date(2024, 1, 1),
        "license_key": "SLACK-PRO-2024",
        "expiry_date": date(2027, 1, 1)
    })
    assert isinstance(asset, DigitalAsset)
    assert isinstance(asset, CompanyAsset)


def test_factory_rejects_unknown_type():
    """Factory menolak tipe yang tidak dikenal."""
    with pytest.raises(ValueError, match="Unsupported"):
        AssetFactory.create_asset({
            "type": "virtual",
            "asset_id": "POL-003",
            "name": "Unknown",
            "purchase_cost": 1000.0,
            "purchase_date": date(2024, 1, 1)
        })


def test_polymorphic_calculate_depreciation():
    """
    Kedua tipe aset bisa dipanggil calculate_depreciation() dari interface yang sama.
    Ini adalah inti dari polimorfisme — satu method, perilaku berbeda.
    """
    physical = AssetFactory.create_asset({
        "type": "physical", "asset_id": "POL-004", "name": "PC",
        "purchase_cost": 10000000.0, "purchase_date": date(2020, 1, 1),
        "serial_number": "PC-001", "maintenance_interval": 180,
        "depreciation_method": "straight_line"
    })
    digital = AssetFactory.create_asset({
        "type": "digital", "asset_id": "POL-005", "name": "License",
        "purchase_cost": 2000000.0, "purchase_date": date(2024, 1, 1),
        "license_key": "LIC-001", "expiry_date": date(2027, 1, 1)
    })

    # Keduanya bisa dipanggil method yang sama
    phys_depr = physical.calculate_depreciation()
    digi_depr = digital.calculate_depreciation()

    assert isinstance(phys_depr, float)
    assert isinstance(digi_depr, float)
    assert phys_depr > 0  # Aset lama sudah pasti ada depresiasi
