@extends('dashboard.master')

@section('title', 'Admin Profile')
@section('page-title', 'Admins Management')
@section('page-subtitle', 'Add a new admin to the system')

@section('content')
    <div class="dashboard-content">
        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        <!-- Error Messages -->
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <ul class="mb-0 pl-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <!-- Main Container -->
        <div class="form-container">
            <!-- Header with Back Button -->
            <div class="form-header">
                <div class="form-title">
                    <h3>Admin Profile</h3>
                    <p class="form-subtitle">Update your admin profile information</p>
                </div>
            </div>

            <!-- Form -->
            <form action="{{ url('/update-admin') }}" method="POST">
                @csrf

                <div class="form-body">
                    <!-- Username -->
                    <div class="form-group">
                        <label for="username" class="form-label">
                            Username <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <div class="input-icon">
                                <i class="fas fa-user-shield"></i>
                            </div>
                            <input type="text"
                                id="username"
                                name="username"
                                class="form-control @error('username') is-invalid @enderror"
                                placeholder="Enter username"
                                value="{{ old('username', $admin->username) }}"
                                required>
                        </div>
                        @error('username')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            Enter a unique username for the admin
                        </small>
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label for="password" class="form-label">
                            Password <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <div class="input-icon">
                                <i class="fas fa-lock"></i>
                            </div>
                            <input type="password"
                                id="password"
                                name="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Enter new password (leave blank to keep current)">
                        </div>
                        @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            Password must be at least 8 characters. Leave blank to keep current password.
                        </small>
                    </div>

                    <!-- Confirm Password -->
                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">
                            Confirm Password
                        </label>
                        <div class="input-group">
                            <div class="input-icon">
                                <i class="fas fa-lock"></i>
                            </div>
                            <input type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                class="form-control @error('password_confirmation') is-invalid @enderror"
                                placeholder="Confirm new password">
                        </div>
                        @error('password_confirmation')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            Re-enter the password to confirm
                        </small>
                    </div>
                </div>

                <!-- Form Footer -->
                <div class="form-footer">
                    <button type="reset" class="btn btn-reset">
                        Reset
                    </button>
                    <button type="submit" class="btn btn-submit">
                        <i class="fas fa-plus mr-2"></i>
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('form');
            const usernameInput = document.getElementById('username');
            const passwordInput = document.getElementById('password');
            const confirmPasswordInput = document.getElementById('password_confirmation');

            form.addEventListener('submit', function (e) {
                let isValid = true;

                document.querySelectorAll('.is-invalid').forEach(el => {
                    el.classList.remove('is-invalid');
                });

                if (!usernameInput.value.trim()) {
                    usernameInput.classList.add('is-invalid');
                    isValid = false;
                }

                if (passwordInput.value) {
                    if (passwordInput.value.length < 8) {
                        passwordInput.classList.add('is-invalid');
                        alert('Password must be at least 8 characters');
                        isValid = false;
                    } else if (passwordInput.value !== confirmPasswordInput.value) {
                        confirmPasswordInput.classList.add('is-invalid');
                        alert('Passwords do not match');
                        isValid = false;
                    }
                }

                if (!isValid) {
                    e.preventDefault();
                }
            });
        });
    </script>
@endsection