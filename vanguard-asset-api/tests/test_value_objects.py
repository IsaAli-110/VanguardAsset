"""
Test Value Objects: Menguji immutability AuditTrailEntry.
Membuktikan bahwa frozen dataclass tidak bisa dimodifikasi setelah dibuat.
"""
import pytest
from dataclasses import FrozenInstanceError
from app.value_objects.audit_trail_entry import AuditTrailEntry


def test_create_audit_trail_entry():
    """AuditTrailEntry bisa dibuat dengan field yang benar."""
    entry = AuditTrailEntry(
        asset_id="VO-001",
        name="Laptop",
        asset_type="PhysicalAsset",
        calculated_at="2026-06-13 10:00:00",
        purchase_cost=10000000.0,
        depreciation_amount=2000000.0,
        remaining_value=8000000.0,
        details={"serial_number": "SN123"}
    )
    assert entry.asset_id == "VO-001"
    assert entry.name == "Laptop"
    assert entry.depreciation_amount == 2000000.0


def test_frozen_cannot_modify_field():
    """AuditTrailEntry yang frozen tidak bisa dimodifikasi field-nya."""
    entry = AuditTrailEntry(
        asset_id="VO-002", name="Server", asset_type="PhysicalAsset",
        calculated_at="2026-06-13 10:00:00", purchase_cost=50000000.0,
        depreciation_amount=10000000.0, remaining_value=40000000.0
    )
    with pytest.raises(FrozenInstanceError):
        entry.name = "Modified Server"


def test_frozen_cannot_add_field():
    """AuditTrailEntry yang frozen tidak bisa ditambah field baru."""
    entry = AuditTrailEntry(
        asset_id="VO-003", name="License", asset_type="DigitalAsset",
        calculated_at="2026-06-13 10:00:00", purchase_cost=2000000.0,
        depreciation_amount=500000.0, remaining_value=1500000.0
    )
    with pytest.raises(FrozenInstanceError):
        entry.new_field = "unexpected"


def test_to_dict_conversion():
    """to_dict() mengembalikan dictionary dengan semua field."""
    entry = AuditTrailEntry(
        asset_id="VO-004", name="Router", asset_type="PhysicalAsset",
        calculated_at="2026-06-13 10:00:00", purchase_cost=5000000.0,
        depreciation_amount=1000000.0, remaining_value=4000000.0,
        details={"serial_number": "RTR-001", "maintenance_interval_days": 90}
    )
    d = entry.to_dict()
    assert d["asset_id"] == "VO-004"
    assert d["name"] == "Router"
    assert d["details"]["serial_number"] == "RTR-001"
    assert isinstance(d, dict)


def test_equality_of_identical_entries():
    """Dua AuditTrailEntry dengan nilai yang sama dianggap equal (dataclass behavior)."""
    entry1 = AuditTrailEntry(
        asset_id="VO-005", name="Switch", asset_type="PhysicalAsset",
        calculated_at="2026-06-13 10:00:00", purchase_cost=3000000.0,
        depreciation_amount=600000.0, remaining_value=2400000.0
    )
    entry2 = AuditTrailEntry(
        asset_id="VO-005", name="Switch", asset_type="PhysicalAsset",
        calculated_at="2026-06-13 10:00:00", purchase_cost=3000000.0,
        depreciation_amount=600000.0, remaining_value=2400000.0
    )
    assert entry1 == entry2


def test_default_empty_details():
    """Field details default ke empty dict."""
    entry = AuditTrailEntry(
        asset_id="VO-006", name="Test", asset_type="TestType",
        calculated_at="2026-06-13", purchase_cost=1000.0,
        depreciation_amount=100.0, remaining_value=900.0
    )
    assert entry.details == {}
