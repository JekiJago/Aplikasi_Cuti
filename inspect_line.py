from pathlib import Path
text = Path('app/Http/Controllers/LeaveRequestController.php').read_text()
print(repr(text.splitlines()[4]))
