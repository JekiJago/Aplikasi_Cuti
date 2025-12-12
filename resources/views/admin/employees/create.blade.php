@extends('layouts.app')

@section('title', 'Tambah Pegawai Baru')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-user-plus"></i> Tambah Pegawai Baru
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.employees.store') }}" method="POST" id="employeeForm">
                        @csrf
                        
                        <!-- Informasi Penting -->
                        <div class="alert alert-info mb-4">
                            <div class="d-flex">
                                <div class="me-3">
                                    <i class="fas fa-info-circle fa-2x"></i>
                                </div>
                                <div>
                                    <h5 class="alert-heading">Informasi Penting</h5>
                                    <p class="mb-1">Semua field bertanda (*) wajib diisi</p>
                                    <p class="mb-0">
                                        <strong>Password Default:</strong> Sama dengan ID Pegawai<br>
                                        <strong>Login dengan:</strong> ID Pegawai sebagai username dan password
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Nama Lengkap -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <label for="name" class="form-label">
                                    <i class="fas fa-user"></i> Nama Lengkap *
                                </label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name') }}" 
                                       placeholder="Masukkan nama lengkap pegawai"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    Contoh: Ahmad Rizki Pratama
                                </div>
                            </div>
                        </div>
                        
                        <!-- ID Pegawai -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="employee_id" class="form-label">
                                    <i class="fas fa-id-card"></i> ID Pegawai *
                                </label>
                                <input type="text" 
                                       class="form-control @error('employee_id') is-invalid @enderror" 
                                       id="employee_id" 
                                       name="employee_id" 
                                       value="{{ old('employee_id') }}" 
                                       placeholder="Masukkan ID Pegawai"
                                       required
                                       maxlength="20">
                                @error('employee_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    Contoh: 198501012010 (6-20 digit angka)
                                </div>
                            </div>
                            
                            <!-- Password Preview -->
                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="fas fa-key"></i> Password Default
                                </label>
                                <div class="input-group">
                                    <input type="text" 
                                           class="form-control" 
                                           id="password_preview" 
                                           value="" 
                                           readonly 
                                           placeholder="Sama dengan ID Pegawai">
                                    <button class="btn btn-outline-secondary" type="button" id="copyPassword">
                                        <i class="fas fa-copy"></i> Copy
                                    </button>
                                </div>
                                <div class="form-text">
                                    Password akan sama dengan ID Pegawai
                                </div>
                            </div>
                        </div>
                        
                        <!-- Jenis Kelamin -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <label class="form-label">
                                    <i class="fas fa-venus-mars"></i> Jenis Kelamin *
                                </label>
                                <div class="d-flex gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="gender" 
                                               id="gender_male" value="male" {{ old('gender') == 'male' ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="gender_male">
                                            <i class="fas fa-male"></i> Laki-laki
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="gender" 
                                               id="gender_female" value="female" {{ old('gender') == 'female' ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="gender_female">
                                            <i class="fas fa-female"></i> Perempuan
                                        </label>
                                    </div>
                                </div>
                                @error('gender')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    Pilih jenis kelamin pegawai
                                </div>
                            </div>
                        </div>
                        
                        <hr class="my-4">
                        
                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.employees.index') }}" 
                               class="btn btn-outline-danger">
                                <i class="fas fa-times"></i> Batalkan
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Pegawai
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const employeeIdInput = document.getElementById('employee_id');
        const passwordPreview = document.getElementById('password_preview');
        const copyButton = document.getElementById('copyPassword');
        
        // Format employee_id input (hanya angka)
        employeeIdInput.addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '');
            updatePasswordPreview();
        });
        
        // Update password preview
        function updatePasswordPreview() {
            let employeeId = employeeIdInput.value;
            if (employeeId.length > 0) {
                // Maksimal 8 karakter untuk password
                if (employeeId.length > 8) {
                    passwordPreview.value = employeeId.substring(0, 8);
                } else {
                    passwordPreview.value = employeeId;
                }
            } else {
                passwordPreview.value = '';
            }
        }
        
        // Copy password to clipboard
        copyButton.addEventListener('click', function() {
            const password = passwordPreview.value;
            if (password) {
                navigator.clipboard.writeText(password).then(() => {
                    // Show success message
                    const originalHtml = copyButton.innerHTML;
                    copyButton.innerHTML = '<i class="fas fa-check"></i> Copied!';
                    copyButton.classList.remove('btn-outline-secondary');
                    copyButton.classList.add('btn-success');
                    
                    setTimeout(() => {
                        copyButton.innerHTML = originalHtml;
                        copyButton.classList.remove('btn-success');
                        copyButton.classList.add('btn-outline-secondary');
                    }, 2000);
                });
            }
        });
        
        // Form validation before submit
        document.getElementById('employeeForm').addEventListener('submit', function(e) {
            // Validate employee_id length
            const employeeId = employeeIdInput.value;
            if (employeeId.length < 6 || employeeId.length > 20) {
                e.preventDefault();
                alert('ID Pegawai harus 6-20 digit angka');
                employeeIdInput.focus();
                return false;
            }
            
            // Validate required fields
            const requiredFields = this.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('is-invalid');
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                alert('Harap isi semua field yang wajib diisi!');
            }
        });
        
        // Initialize password preview
        updatePasswordPreview();
    });
</script>
@endpush

<style>
    .card {
        border-radius: 10px;
        box-shadow: 0 2px 20px rgba(0,0,0,0.1);
    }
    .card-header {
        border-radius: 10px 10px 0 0 !important;
        padding: 1.2rem 1.5rem;
    }
    .form-label {
        font-weight: 500;
        color: #495057;
    }
    .form-control, .form-select {
        padding: 0.75rem;
        border-radius: 8px;
    }
    .form-check-input {
        width: 1.2em;
        height: 1.2em;
    }
    .btn-primary {
        padding: 0.75rem 2rem;
        border-radius: 8px;
        font-weight: 500;
    }
    .btn-outline-danger {
        padding: 0.75rem 2rem;
        border-radius: 8px;
        font-weight: 500;
    }
</style>
@endsection