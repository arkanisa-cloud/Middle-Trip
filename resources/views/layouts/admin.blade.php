<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Panel') — MiddleTrip Operations</title>

    <!-- Google Fonts: Outfit & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">

    <!-- Toastr Notifications CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    <!-- Scripts and Styles -->
    <x-favicon />
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="bg-canvas text-ink font-sans antialiased selection:bg-primary-subtle selection:text-primary min-h-screen">
    
    <div x-data="{ sidebarOpen: false }" class="min-h-screen bg-canvas relative">
        
        <!-- Mobile Backdrop -->
        <div x-show="sidebarOpen" 
             @click="sidebarOpen = false" 
             x-cloak
             class="fixed inset-0 bg-black/60 z-30 md:hidden backdrop-blur-xs transition-opacity duration-300">
        </div>

        <!-- Admin Sidebar (Fixed on Viewport) -->
        <x-admin.sidebar />

        <!-- Main Content Area (Offset by md:pl-64 for fixed sidebar) -->
        <div class="md:pl-64 flex flex-col min-h-screen min-w-0 bg-canvas">
            
            <!-- Topbar Header -->
            <x-admin.topbar />

            <!-- Page Body Container -->
            <main class="flex-1 p-4 md:p-8 max-w-7xl w-full mx-auto space-y-6">
                
                <!-- Flash Alerts -->
                @if (session('success'))
                    <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center justify-between shadow-2xs">
                        <div class="flex items-center gap-3">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span class="font-medium">{{ session('success') }}</span>
                        </div>
                        <button type="button" onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900 font-bold">&times;</button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-center justify-between shadow-2xs">
                        <div class="flex items-center gap-3">
                            <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                            <span class="font-medium">{{ session('error') }}</span>
                        </div>
                        <button type="button" onclick="this.parentElement.remove()" class="text-rose-600 hover:text-rose-900 font-bold">&times;</button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm shadow-2xs">
                        <p class="font-bold mb-1">Terdapat kesalahan input:</p>
                        <ul class="list-disc list-inside text-xs space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Yielded Content -->
                @yield('content')
            </main>

            <!-- Admin Footer -->
            <footer class="py-4 px-8 border-t border-hairline text-center text-xs text-muted flex flex-col sm:flex-row items-center justify-between gap-2 mt-auto">
                <p>&copy; {{ date('Y') }} MiddleTrip Expedition Co. All rights reserved.</p>
                <p class="text-muted-soft">Platform Operasional & Manajemen Pendakian Terstandarisasi</p>
            </footer>
        </div>
    </div>

    @stack('scripts')

    <!-- jQuery, Toastr & SweetAlert2 JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Setup Toastr clean & simple options
        if (typeof toastr !== 'undefined') {
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "showDuration": "200",
                "hideDuration": "300",
                "timeOut": "3500",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };
        }

        // Global Helper for SweetAlert Confirmations
        function appConfirm(options) {
            const defaults = {
                title: 'Apakah Anda yakin?',
                text: 'Tindakan ini akan diproses pada sistem.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#1B4D3E',
                cancelButtonColor: '#94A3B8',
                confirmButtonText: 'Ya, Lanjutkan',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-3xl shadow-xl font-sans',
                    confirmButton: 'rounded-xl font-bold px-5 py-2.5 shadow-sm',
                    cancelButton: 'rounded-xl font-bold px-5 py-2.5'
                }
            };
            return Swal.fire(Object.assign({}, defaults, options));
        }

        // SweetAlert2 Confirmation helper for forms with data-confirm or class confirm-delete
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('form[data-confirm], form.confirm-delete').forEach(function(form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const message = form.getAttribute('data-confirm') || 'Data yang dihapus tidak dapat dipulihkan kembali.';
                    const title = form.getAttribute('data-title') || 'Apakah Anda yakin?';
                    const confirmText = form.getAttribute('data-confirm-text') || 'Ya, Hapus Data';
                    const icon = form.getAttribute('data-icon') || 'warning';
                    
                    Swal.fire({
                        title: title,
                        text: message,
                        icon: icon,
                        showCancelButton: true,
                        confirmButtonColor: '#1B4D3E',
                        cancelButtonColor: '#94A3B8',
                        confirmButtonText: confirmText,
                        cancelButtonText: 'Batal',
                        customClass: {
                            popup: 'rounded-3xl shadow-xl font-sans',
                            confirmButton: 'rounded-xl font-bold px-5 py-2.5',
                            cancelButton: 'rounded-xl font-bold px-5 py-2.5'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });

        // Trigger flash notifications from Laravel session with clean message
        @if (session('success'))
            toastr.success(@json(session('success')));
        @endif

        @if (session('error'))
            toastr.error(@json(session('error')));
        @endif

        @if (session('warning'))
            toastr.warning(@json(session('warning')));
        @endif

        @if ($errors->any())
            toastr.error(@json($errors->first()), 'Validasi Gagal');
        @endif
    </script>

    @stack('scripts')
</body>
</html>
