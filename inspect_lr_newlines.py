from pathlib import Path
text = Path('app/Http/Controllers/LeaveRequestController.php').read_text()
print('CRLF' if '\r\n' in text else 'LF only')
