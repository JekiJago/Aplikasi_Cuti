from pathlib import Path
raw = Path('app/Http/Controllers/LeaveRequestController.php').read_bytes()
print(raw[:40])
