@extends('layouts.app')

@section('title', 'Edit Pegawai')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-gray-100 py-6">

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- CARD --}}
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-200">
            {{-- Background Header Card --}}
            <div class="absolute top-0 left-0 w-full h-40 bg-gradient-to-r from-[#0B5E2E] to-[#083D1D] opacity-5"></div>

            {{-- HEADER --}}
            <div class="relative px-6 pt-8 pb-6 border-b border-[#DCE5DF] bg-gradient-to-r from-green-50 to-emerald-50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-gradient-to-br from-[#0B5E2E] to-[#083D1D] p-3 rounded-xl shadow-md mr-4">
                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M15 12c0-1.654-1.346-3-3-3s-3 1.346-3 3 1.346 3 3 3 3-1.346 3-3zm9-.449s-4.252 8.449-11.985 8.449c-7.18 0-12.015-8.449-12.015-8.449s4.446-7.551 12.015-7.551c7.694 0 11.985 7.551 11.985 7.551zm-7 .449c0-2.757-2.243-5-5-5s-5 2.243-5 5 2.243 5 5 5 5-2.243 5-5z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-[#083D1D]">
                                <i class="fas fa-edit mr-2 text-[#0B5E2E]"></i>Edit Data Pegawai
                            </h2>
                            <p class="text-gray-600 mt-1">
                                Perbarui informasi pegawai {{ $employee->name }}
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('admin.employees.show', $employee->id) }}" 
                       class="text-[#083D1D] hover:text-[#0B5E2E] transition">
                        <i class="fas fa-times text-2xl"></i>
                    </a>
                </div>
            </div>

            {{-- BODY --}}
            <div class="px-6 py-8 bg-white">
                {{-- FORM --}}
                <form action="{{ route('admin.employees.update', $employee->id) }}" method="POST" id="editEmployeeForm">
                    @csrf
                    @method('PUT')

                    {{-- NAMA --}}
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-[#083D1D] mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>
                            <input type="text"
                                name="name"
                                value="{{ old('name', $employee->name) }}"
                                class="pl-10 block w-full rounded-lg border-[#DCE5DF] bg-[#F9FAF7] shadow-sm focus:border-[#F2B705] focus:ring-[#F2B705] @error('name') border-red-500 @enderror"
                                placeholder="Masukkan nama lengkap"
                                required>
                        </div>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- GRID INPUTS --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        {{-- NIP --}}
                        <div>
                            <label class="block text-sm font-medium text-[#083D1D] mb-2">
                                NIP (Nomor Induk Pegawai) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-id-badge text-gray-400"></i>
                                </div>
                                <input type="text"
                                    name="employee_id"
                                    value="{{ old('employee_id', $employee->pegawai->nip ?? '') }}"
                                    class="pl-10 block w-full rounded-lg border-[#DCE5DF] bg-[#F9FAF7] shadow-sm focus:border-[#F2B705] focus:ring-[#F2B705] @error('employee_id') border-red-500 @enderror"
                                    placeholder="Masukkan NIP"
                                    required>
                            </div>
                            @error('employee_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- NRP --}}
                        <div>
                            <label class="block text-sm font-medium text-[#083D1D] mb-2">
                                NRP (Nomor Registrasi Pegawai) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-lock text-gray-400"></i>
                                </div>
                                <input type="text"
                                    name="nrp"
                                    value="{{ old('nrp', $employee->pegawai->nrp ?? '') }}"
                                    class="pl-10 block w-full rounded-lg border-[#DCE5DF] bg-[#F9FAF7] shadow-sm focus:border-[#F2B705] focus:ring-[#F2B705] @error('nrp') border-red-500 @enderror"
                                    placeholder="Masukkan NRP"
                                    required>
                            </div>
                            @error('nrp')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- JENIS KELAMIN --}}
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-[#083D1D] mb-3">
                            Jenis Kelamin <span class="text-red-500">*</span>
                        </label>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <input type="radio" 
                                    name="gender" 
                                    value="male" 
                                    id="gender_male"
                                    {{ old('gender', $employee->pegawai->jenis_kelamin ?? '') === 'male' ? 'checked' : '' }}
                                    class="w-4 h-4 text-[#F2B705] focus:ring-[#F2B705]"
                                    required>
                                <label for="gender_male" class="ml-3 text-[#083D1D]">Laki-laki</label>
                            </div>
                            <div class="flex items-center">
                                <input type="radio" 
                                    name="gender" 
                                    value="female" 
                                    id="gender_female"
                                    {{ old('gender', $employee->pegawai->jenis_kelamin ?? '') === 'female' ? 'checked' : '' }}
                                    class="w-4 h-4 text-[#F2B705] focus:ring-[#F2B705]"
                                    required>
                                <label for="gender_female" class="ml-3 text-[#083D1D]">Perempuan</label>
                            </div>
                        </div>
                        @error('gender')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- KUOTA CUTI TAHUN BERJALAN --}}
                    <div class="mb-8">
                        <div class="bg-[#F9FAF7] rounded-xl p-4 border border-[#DCE5DF] mb-6">
                            <h3 class="text-lg font-semibold text-[#083D1D] mb-4 flex items-center">
                                <i class="fas fa-calendar-alt text-[#0B5E2E] mr-2"></i>
                                Pengaturan Kuota Cuti
                            </h3>
                            
                            {{-- KUOTA CUTI TAHUN INI --}}
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-[#083D1D] mb-2">
                                    Kuota Cuti Tahun {{ date('Y') }} <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-calendar-check text-gray-400"></i>
                                    </div>
                                    <input type="number"
                                        name="annual_leave_quota"
                                        id="annual_leave_quota_edit"
                                        value="{{ old('annual_leave_quota', $currentYearValue) }}"
                                        min="0"
                                        max="12"
                                        class="pl-10 block w-full rounded-lg border-[#DCE5DF] bg-white shadow-sm focus:border-[#F2B705] focus:ring-[#F2B705] @error('annual_leave_quota') border-red-500 @enderror"
                                        placeholder="Masukkan kuota cuti tahunan"
                                        required>
                                </div>
                                <p class="mt-1 text-xs text-[#083D1D]/70">
                                    Jumlah cuti yang diberikan untuk tahun {{ date('Y') }} (Maksimal 12 hari)
                                </p>
                                @error('annual_leave_quota')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- SISA CUTI TAHUN LALU (OPSIONAL) --}}
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-[#083D1D] mb-2">
                                    Kuota Cuti Tahun {{ date('Y') - 1 }} (Opsional)
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-history text-gray-400"></i>
                                    </div>
                                    <input type="number"
                                        name="previous_year_quota"
                                        id="previous_year_quota_edit"
                                        value="{{ old('previous_year_quota', $prevYearValue) }}"
                                        min="0"
                                        max="12"
                                        class="pl-10 block w-full rounded-lg border-[#DCE5DF] bg-white shadow-sm focus:border-[#F2B705] focus:ring-[#F2B705] @error('previous_year_quota') border-red-500 @enderror"
                                        placeholder="Masukkan kuota cuti tahun lalu">
                                </div>
                                <p class="mt-1 text-xs text-[#083D1D]/70">
                                    Kuota cuti tahun {{ date('Y') - 1 }} yang masih bisa digunakan (Maksimal 12 hari)
                                </p>
                                @error('previous_year_quota')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- BUTTONS --}}
                    <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                        <div>
                            <a href="{{ route('admin.employees.show', $employee->id) }}"
                               class="px-6 py-2.5 rounded-lg border border-[#DCE5DF] text-[#083D1D] font-medium hover:bg-gray-50 transition flex items-center">
                                <i class="fas fa-times mr-2"></i>Batal
                            </a>
                        </div>
                        <div class="flex gap-4">
                            <button type="button" onclick="showDeleteConfirm()"
                                    class="px-6 py-2.5 rounded-lg border border-red-300 text-red-600 font-medium hover:bg-red-50 transition flex items-center">
                                <i class="fas fa-trash mr-2"></i>Hapus Pegawai
                            </button>
                            <button type="reset"
                                    class="px-6 py-2.5 rounded-lg border border-[#DCE5DF] text-[#083D1D] font-medium hover:bg-gray-50 transition flex items-center">
                                <i class="fas fa-redo mr-2"></i>Reset
                            </button>
                            <button type="submit"
                                    class="px-8 py-2.5 rounded-lg bg-gradient-to-r from-[#F2B705] to-[#E6A800] text-[#083D1D] font-semibold hover:from-[#E6A800] hover:to-[#D99A00] transition shadow-md flex items-center">
                                <i class="fas fa-save mr-2"></i>Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>

                {{-- DELETE FORM (HIDDEN) --}}
                <form id="deleteForm" action="{{ route('admin.employees.destroy', $employee->id) }}" method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
    </div>
</div>

{{-- DELETE CONFIRMATION MODAL --}}
<div id="deleteModal" style="display: none;" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4">
        <div class="p-6">
            <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full mb-4">
                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-[#083D1D] text-center mb-2">Hapus Pegawai</h3>
            <p class="text-sm text-gray-600 text-center mb-4">
                Apakah Anda yakin ingin menghapus pegawai <strong>{{ $employee->name }}</strong>?
            </p>
            <p class="text-xs text-red-600 bg-red-50 p-3 rounded-lg mb-6">
                <i class="fas fa-info-circle mr-2"></i>
                Tindakan ini tidak dapat dibatalkan. Semua data cuti dan riwayat akan dihapus.
            </p>
            <div class="flex gap-3">
                <button type="button" onclick="closeDeleteConfirm()"
                        class="flex-1 px-4 py-2.5 rounded-lg border border-[#DCE5DF] text-[#083D1D] font-medium hover:bg-gray-50 transition">
                    Batal
                </button>
                <button type="button" onclick="confirmDelete()"
                        class="flex-1 px-4 py-2.5 rounded-lg bg-red-600 text-white font-medium hover:bg-red-700 transition">
                    <i class="fas fa-trash mr-2"></i>Hapus
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function showDeleteConfirm() {
        document.getElementById('deleteModal').style.display = 'flex';
    }

    function closeDeleteConfirm() {
        document.getElementById('deleteModal').style.display = 'none';
    }

    function confirmDelete() {
        document.getElementById('deleteForm').submit();
    }

    // Close modal when clicking outside
    document.getElementById('deleteModal').addEventListener('click', function(event) {
        if (event.target === this) {
            closeDeleteConfirm();
        }
    });

    // Real-time validation untuk kuota cuti
    const annualQuotaInput = document.getElementById('annual_leave_quota_edit');
    if (annualQuotaInput) {
        annualQuotaInput.addEventListener('input', function() {
            const value = parseInt(this.value);
            if (isNaN(value)) return;
            
            if (value > 12) {
                this.classList.add('border-red-500');
                if (!document.getElementById('quota_warning')) {
                    const warning = document.createElement('div');
                    warning.id = 'quota_warning';
                    warning.className = 'mt-2 p-3 bg-red-100 border border-red-400 text-red-700 rounded';
                    warning.textContent = '⚠️ Kuota cuti tidak boleh melebihi 12 hari!';
                    this.parentElement.parentElement.appendChild(warning);
                }
            } else {
                this.classList.remove('border-red-500');
                const warning = document.getElementById('quota_warning');
                if (warning) warning.remove();
            }
        });
    }

    // Real-time validation untuk previous year quota
    const prevYearQuotaInput = document.getElementById('previous_year_quota_edit');
    if (prevYearQuotaInput) {
        prevYearQuotaInput.addEventListener('input', function() {
            const value = parseInt(this.value);
            if (isNaN(value)) return;
            
            if (value > 12) {
                this.classList.add('border-red-500');
                if (!document.getElementById('prev_quota_warning')) {
                    const warning = document.createElement('div');
                    warning.id = 'prev_quota_warning';
                    warning.className = 'mt-2 p-3 bg-red-100 border border-red-400 text-red-700 rounded';
                    warning.textContent = '⚠️ Kuota cuti tidak boleh melebihi 12 hari!';
                    this.parentElement.parentElement.appendChild(warning);
                }
            } else {
                this.classList.remove('border-red-500');
                const warning = document.getElementById('prev_quota_warning');
                if (warning) warning.remove();
            }
        });
    }

    document.getElementById('editEmployeeForm').addEventListener('submit', function(e) {
        // Validasi jenis kelamin
        const genderSelected = this.querySelector('input[name="gender"]:checked');
        if (!genderSelected) {
            e.preventDefault();
            alert('Silakan pilih jenis kelamin!');
            return false;
        }

        // Validasi NIP dan NRP harus angka
        const employeeId = this.querySelector('input[name="employee_id"]').value;
        const nrp = this.querySelector('input[name="nrp"]').value;
        
        if (!/^\d+$/.test(employeeId)) {
            e.preventDefault();
            alert('NIP harus berupa angka!');
            return false;
        }
        
        if (!/^\d+$/.test(nrp)) {
            e.preventDefault();
            alert('NRP harus berupa angka!');
            return false;
        }

        // Validasi kuota cuti
        const annualQuota = this.querySelector('input[name="annual_leave_quota"]').value;
        if (annualQuota < 0 || annualQuota > 12) {
            e.preventDefault();
            alert('Kuota cuti tahunan harus antara 0 - 12 hari!');
            return false;
        }

        // Validasi sisa tahun lalu
        const prevYearRemaining = this.querySelector('input[name="previous_year_remaining"]').value;
        if (prevYearRemaining && (prevYearRemaining < 0 || prevYearRemaining > 365)) {
            e.preventDefault();
            alert('Sisa cuti tahun lalu harus antara 0 - 365 hari!');
            return false;
        }

        // Konfirmasi sebelum submit
        if (!confirm('Apakah Anda yakin ingin menyimpan perubahan data pegawai ini?')) {
            e.preventDefault();
            return false;
        }

        return true;
    });
</script>
@endpush