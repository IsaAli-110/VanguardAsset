from fastapi import FastAPI, HTTPException, status
from fastapi.middleware.cors import CORSMiddleware
from app.schemas.asset_schema import AssetRequest, DepreciationResponse
from app.polymorphism.asset_factory import AssetFactory
from app.observers.event_dispatcher import EventDispatcher
from app.observers.asset_events import AssetDepreciated, AssetAuditLogged
from app.observers.audit_listener import AuditListener
from app.observers.notification_listener import NotificationListener

app = FastAPI(
    title="VanguardAsset OOP Logic Engine",
    description="Microservice dedicated to advanced OOP operations for Company Assets",
    version="1.0.0"
)

# Allow CORS for Laravel app integration
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

# ─── Observer Pattern: Inisialisasi Event Dispatcher dan Listener ───────────
event_dispatcher = EventDispatcher()
audit_listener = AuditListener()
notification_listener = NotificationListener()

# Daftarkan listener ke event
event_dispatcher.subscribe(AssetDepreciated, audit_listener.on_event)
event_dispatcher.subscribe(AssetDepreciated, notification_listener.on_event)
event_dispatcher.subscribe(AssetAuditLogged, audit_listener.on_event)
event_dispatcher.subscribe(AssetAuditLogged, notification_listener.on_event)


@app.get("/")
def read_root():
    return {
        "status": "online",
        "service": "VanguardAsset OOP Logic Engine",
        "endpoints": {
            "depreciation": "/api/asset/depreciation [POST]",
            "observer_log": "/api/observer/log [GET]",
        }
    }


@app.get("/api/observer/log")
def get_observer_log():
    """
    Endpoint untuk melihat riwayat event Observer Pattern.
    Berguna untuk debugging dan demonstrasi.
    """
    return {
        "event_log": event_dispatcher.get_event_log(),
        "registered_events": event_dispatcher.get_registered_events(),
        "audit_records": audit_listener.get_records(),
        "notifications": notification_listener.get_notifications()
    }


@app.post("/api/asset/depreciation", response_model=DepreciationResponse)
def calculate_depreciation_endpoint(request: AssetRequest):
    """
    POST Endpoint to calculate asset depreciation.
    Dynamically instantiates classes, executes polymorphic methods,
    fires Observer events, and returns results with audit logs.
    """
    try:
        # Polymorphic creation using the AssetFactory with Strategy Pattern
        asset = AssetFactory.create_asset({
            "type": request.type,
            "asset_id": request.asset_id,
            "name": request.name,
            "purchase_cost": request.purchase_cost,
            "purchase_date": request.purchase_date,
            "serial_number": request.serial_number,
            "maintenance_interval": request.maintenance_interval,
            "license_key": request.license_key,
            "expiry_date": request.expiry_date,
            "depreciation_method": request.depreciation_method or "straight_line"
        })
        
        # Execute polymorphic depreciation calculation
        depreciation_amount = asset.calculate_depreciation()
        remaining_value = round(max(0.0, asset.purchase_cost - depreciation_amount), 2)
        
        # Execute Loggable interface method (returns immutable frozen dataclass)
        audit_trail_entry = asset.generate_audit_trail(depreciation_amount, remaining_value)
        
        # ─── Observer Pattern: Dispatch Events ─────────────────────────────
        strategy_used = asset.get_audit_details().get("depreciation_strategy", "unknown")

        event_dispatcher.dispatch(AssetDepreciated, {
            "asset_id": asset.asset_id,
            "name": asset.name,
            "type": request.type.lower(),
            "purchase_cost": asset.purchase_cost,
            "depreciation_amount": depreciation_amount,
            "remaining_value": remaining_value,
            "strategy_used": strategy_used,
        })

        event_dispatcher.dispatch(AssetAuditLogged, {
            "asset_id": asset.asset_id,
            "name": asset.name,
            "audit_type": audit_trail_entry.asset_type,
            "purchase_cost": asset.purchase_cost,
        })

        return DepreciationResponse(
            asset_id=asset.asset_id,
            name=asset.name,
            type=request.type.lower(),
            purchase_cost=asset.purchase_cost,
            purchase_date=asset.purchase_date,
            depreciation_amount=depreciation_amount,
            remaining_value=remaining_value,
            audit_trail=audit_trail_entry.to_dict()
        )
        
    except ValueError as ve:
        raise HTTPException(
            status_code=status.HTTP_422_UNPROCESSABLE_ENTITY,
            detail=f"Domain Validation Error: {str(ve)}"
        )
    except Exception as e:
        raise HTTPException(
            status_code=status.HTTP_500_INTERNAL_SERVER_ERROR,
            detail=f"Computational Engine Error: {str(e)}"
        )
