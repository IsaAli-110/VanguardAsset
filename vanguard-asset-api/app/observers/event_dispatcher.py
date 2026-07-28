from typing import Callable, Dict, List, Any
from datetime import datetime

class EventDispatcher:
    """
    Penerapan Observer Pattern (Event Dispatcher).
    
    Kelas ini bertindak sebagai 'Subject' dalam Observer Pattern.
    Komponen lain dapat 'subscribe' sebagai listener terhadap event tertentu,
    dan akan otomatis dipanggil (notify) ketika event tersebut dipicu (dispatch).
    
    Fitur:
    - Mendukung multiple listener per event
    - Setiap dispatch mencatat timestamp otomatis
    - Thread-safe untuk penggunaan sederhana
    """

    def __init__(self):
        self._listeners: Dict[str, List[Callable]] = {}
        self._event_log: List[Dict[str, Any]] = []

    def subscribe(self, event_name: str, listener: Callable) -> None:
        """
        Daftarkan listener untuk event tertentu.
        
        Args:
            event_name: Nama event (misal: 'asset_depreciated')
            listener: Fungsi/method yang akan dipanggil saat event dipicu
        """
        if event_name not in self._listeners:
            self._listeners[event_name] = []
        self._listeners[event_name].append(listener)

    def unsubscribe(self, event_name: str, listener: Callable) -> None:
        """Hapus listener dari event tertentu."""
        if event_name in self._listeners:
            self._listeners[event_name] = [
                l for l in self._listeners[event_name] if l != listener
            ]

    def dispatch(self, event_name: str, data: Dict[str, Any] = None) -> List[Any]:
        """
        Picu event dan panggil semua listener yang terdaftar.
        
        Args:
            event_name: Nama event yang dipicu
            data: Data payload yang dikirim ke listener
            
        Returns:
            List hasil dari setiap listener
        """
        if data is None:
            data = {}

        # Tambahkan metadata event
        data['_event_name'] = event_name
        data['_dispatched_at'] = datetime.now().strftime("%Y-%m-%d %H:%M:%S")

        # Catat ke event log
        self._event_log.append({
            "event": event_name,
            "timestamp": data['_dispatched_at'],
            "listener_count": len(self._listeners.get(event_name, []))
        })

        # Panggil semua listener
        results = []
        for listener in self._listeners.get(event_name, []):
            try:
                result = listener(data)
                results.append(result)
            except Exception as e:
                results.append({"error": str(e), "listener": listener.__name__})

        return results

    def get_event_log(self) -> List[Dict[str, Any]]:
        """Ambil riwayat semua event yang pernah dipicu."""
        return self._event_log.copy()

    def get_registered_events(self) -> Dict[str, int]:
        """Ambil daftar event dan jumlah listener yang terdaftar."""
        return {name: len(listeners) for name, listeners in self._listeners.items()}
