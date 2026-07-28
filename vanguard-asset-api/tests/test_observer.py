"""
Test Observer Pattern: Menguji dispatch event dan pemanggilan listener.
Membuktikan bahwa EventDispatcher memanggil semua listener yang terdaftar.
"""
import pytest
from app.observers.event_dispatcher import EventDispatcher
from app.observers.audit_listener import AuditListener
from app.observers.notification_listener import NotificationListener
from app.observers.asset_events import AssetDepreciated, AssetAuditLogged


def test_subscribe_and_dispatch():
    """Listener yang terdaftar dipanggil saat event dipicu."""
    dispatcher = EventDispatcher()
    called = []

    def my_listener(data):
        called.append(data.get("_event_name"))
        return {"ok": True}

    dispatcher.subscribe("test_event", my_listener)
    results = dispatcher.dispatch("test_event", {"key": "value"})

    assert len(called) == 1
    assert called[0] == "test_event"
    assert results[0]["ok"] is True


def test_multiple_listeners():
    """Multiple listener dipanggil untuk event yang sama."""
    dispatcher = EventDispatcher()
    call_count = {"count": 0}

    def listener_a(data):
        call_count["count"] += 1

    def listener_b(data):
        call_count["count"] += 1

    dispatcher.subscribe("multi_event", listener_a)
    dispatcher.subscribe("multi_event", listener_b)
    dispatcher.dispatch("multi_event")

    assert call_count["count"] == 2


def test_unsubscribe():
    """Listener yang di-unsubscribe tidak dipanggil lagi."""
    dispatcher = EventDispatcher()
    called = []

    def my_listener(data):
        called.append(True)

    dispatcher.subscribe("unsub_event", my_listener)
    dispatcher.unsubscribe("unsub_event", my_listener)
    dispatcher.dispatch("unsub_event")

    assert len(called) == 0


def test_audit_listener_records_event():
    """AuditListener mencatat event ke records."""
    listener = AuditListener()
    result = listener.on_event({
        "_event_name": "asset_depreciated",
        "_dispatched_at": "2026-06-13 10:00:00",
        "asset_id": "OBS-001",
        "name": "Test Asset",
        "depreciation_amount": 500000.0,
        "remaining_value": 9500000.0,
        "strategy_used": "straight_line"
    })

    assert result["listener"] == "AuditListener"
    assert result["status"] == "recorded"
    assert len(listener.get_records()) == 1
    assert listener.get_records()[0]["asset_id"] == "OBS-001"


def test_notification_listener_generates_message():
    """NotificationListener menghasilkan pesan notifikasi."""
    listener = NotificationListener()
    result = listener.on_event({
        "_event_name": "asset_depreciated",
        "_dispatched_at": "2026-06-13 10:00:00",
        "asset_id": "OBS-002",
        "name": "Server",
        "purchase_cost": 50000000.0,
        "depreciation_amount": 10000000.0,
        "remaining_value": 40000000.0,
        "strategy_used": "declining_balance"
    })

    assert "DEPRESIASI" in result["message"]
    assert "Server" in result["message"]
    assert result["status"] == "notified"


def test_notification_high_priority_for_large_depreciation():
    """Notifikasi prioritas warning jika depresiasi > 50% dari cost."""
    listener = NotificationListener()
    listener.on_event({
        "_event_name": "asset_depreciated",
        "_dispatched_at": "2026-06-13 10:00:00",
        "asset_id": "OBS-003",
        "name": "Old Server",
        "purchase_cost": 10000000.0,
        "depreciation_amount": 8000000.0,
        "remaining_value": 2000000.0,
        "strategy_used": "sum_of_years"
    })

    notifications = listener.get_notifications()
    assert notifications[0]["priority"] == "warning"


def test_event_log_recorded():
    """EventDispatcher mencatat riwayat setiap dispatch."""
    dispatcher = EventDispatcher()
    dispatcher.dispatch("event_a", {"x": 1})
    dispatcher.dispatch("event_b", {"y": 2})

    log = dispatcher.get_event_log()
    assert len(log) == 2
    assert log[0]["event"] == "event_a"
    assert log[1]["event"] == "event_b"


def test_full_observer_integration():
    """Integrasi penuh: dispatcher + audit + notification listeners."""
    dispatcher = EventDispatcher()
    audit = AuditListener()
    notif = NotificationListener()

    dispatcher.subscribe(AssetDepreciated, audit.on_event)
    dispatcher.subscribe(AssetDepreciated, notif.on_event)

    dispatcher.dispatch(AssetDepreciated, {
        "asset_id": "OBS-INT", "name": "Integration Test",
        "purchase_cost": 5000000.0, "depreciation_amount": 1000000.0,
        "remaining_value": 4000000.0, "strategy_used": "straight_line"
    })

    assert len(audit.get_records()) == 1
    assert len(notif.get_notifications()) == 1
