from pathlib import Path
path = Path('resources/views/admin/employees/show.blade.php')
text = path.read_text()
old = "                <div class=\"rounded-xl bg-amber-50 px-4 py-3\">\n                    <p class=\"text-xs text-slate-500\">Cuti Akan Hangus</p>\n                    <p class=\"text-lg font-semibold text-slate-900\">0 hari</p>\n                </div>\n"
new = "                <div class=\"rounded-xl bg-amber-50 px-4 py-3\">\n                    <p class=\"text-xs text-slate-500\">Cuti Akan Hangus</p>\n                    <p class=\"text-lg font-semibold text-slate-900\">{{ $expiring }} hari</p>\n                    @if($expiring > 0 && ($annualSummary['carry_over_expires_at'] ?? null))\n                        <p class=\"text-[11px] text-slate-600 mt-0.5\">Sampai {{ $annualSummary['carry_over_expires_at']->translatedFormat('d F Y') }}</p>\n                    @endif\n                </div>\n"
if old not in text:
    raise SystemExit('pattern not found')
path.write_text(text.replace(old, new, 1))
