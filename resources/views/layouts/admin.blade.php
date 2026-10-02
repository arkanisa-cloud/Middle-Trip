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

    <!-- Scripts and Styles -->
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

    <!-- jQuery & Toastr JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        // Setup Toastr or fallback
        if (typeof toastr !== 'undefined') {
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "showDuration": "300",
                "hideDuration": "500",
                "timeOut": "4000",
                "extendedTimeOut": "1500",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };
        } else {
            // Elegant native fallback toast if CDN is unreachable
            window.toastr = (function() {
                function show(type, msg, title) {
                    let container = document.getElementById('native-toastr-container');
                    if (!container) {
                        container = document.createElement('div');
                        container.id = 'native-toastr-container';
                        container.className = 'fixed top-5 right-5 z-50 flex flex-col gap-2 max-w-sm w-full pointer-events-none';
                        document.body.appendChild(container);
                    }
                    const toast = document.createElement('div');
                    const bgClass = type === 'error' ? 'bg-rose-600 text-white' : (type === 'success' ? 'bg-emerald-600 text-white' : 'bg-amber-600 text-white');
                    toast.className = `${bgClass} p-4 rounded-2xl shadow-xl pointer-events-auto transform transition-all duration-300 translate-y-[-10px] opacity-0 flex items-start justify-between gap-3 text-xs`;
                    toast.innerHTML = `
                        <div>
                            ${title ? `<p class="font-bold text-sm mb-0.5">${title}</p>` : ''}
                            <p class="font-medium">${msg}</p>
                        </div>
                        <button type="button" class="text-white/80 hover:text-white font-bold text-sm leading-none">&times;</button>
                    `;
                    container.appendChild(toast);
                    requestAnimationFrame(() => {
                        toast.classList.remove('translate-y-[-10px]', 'opacity-0');
                    });
                    const close = () => {
                        toast.classList.add('opacity-0', 'translate-y-[-10px]');
                        setTimeout(() => toast.remove(), 300);
                    };
                    toast.querySelector('button').onclick = close;
                    setTimeout(close, 4500);
                }
                return {
                    error: (msg, title) => show('error', msg, title),
                    success: (msg, title) => show('success', msg, title),
                    warning: (msg, title) => show('warning', msg, title),
                    info: (msg, title) => show('info', msg, title)
                };
            })();
        }

        // Trigger flash notifications from Laravel session
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
            toastr.error(@json($errors->first()), 'Gagal Menyimpan Data');
        @endif
    </script>

    @stack('scripts')
</body>
</html>
