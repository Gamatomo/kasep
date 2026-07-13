<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $setting->nama_perusahaan }} | @yield('title')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <link rel="icon" href="{{ url($setting->path_logo) }}" type="image/png">

    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('/AdminLTE-2/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('/AdminLTE-2/bower_components/font-awesome/css/font-awesome.min.css') }}">
    <!-- AdminLTE base -->
    <link rel="stylesheet" href="{{ asset('/AdminLTE-2/dist/css/AdminLTE.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/AdminLTE-2/dist/css/skins/_all-skins.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/AdminLTE-2/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap">
    <link rel="stylesheet" href="{{ asset('css/modern.css') }}">

    <link rel="manifest" href="{{ url('/manifest.json') }}">
    <meta name="theme-color" content="#1976D2">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-title" content="{{ $setting->nama_perusahaan ?? config('app.name') }}">
    <link rel="apple-touch-icon" href="{{ url($setting->path_logo) }}">

    @stack('css')
</head>
<body class="hold-transition sidebar-mini modern-app">
    <div class="modern-shell">
        @includeIf('layouts.header')

        <div class="modern-main-layout">
            @includeIf('layouts.sidebar')

            <div class="modern-content-area">
                <div class="modern-page-header">
                    <div>
                        <h1>@yield('title')</h1>
                        <ol class="breadcrumb modern-breadcrumb">
                            @section('breadcrumb')
                                <li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                            @show
                        </ol>
                    </div>
                </div>

                <main class="modern-main-content">
                    @yield('content')
                </main>

                @includeIf('layouts.footer')
            </div>
        </div>
    </div>

    <!-- jQuery 3 -->
    <script src="{{ asset('AdminLTE-2/bower_components/jquery/dist/jquery.min.js') }}"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="{{ asset('AdminLTE-2/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <!-- Moment -->
    <script src="{{ asset('AdminLTE-2/bower_components/moment/min/moment.min.js') }}"></script>

    <!-- DataTables -->
    <script src="{{ asset('AdminLTE-2/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('AdminLTE-2/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('AdminLTE-2/dist/js/adminlte.min.js') }}"></script>
    <!-- Validator -->
    <script src="{{ asset('js/validator.min.js') }}"></script>
    <script src="{{ asset('js/detail-modal.js') }}"></script>
    <script>
        function preview(selector, temporaryFile, width = 200)  {
            $(selector).empty();
            $(selector).append(`<img src="${window.URL.createObjectURL(temporaryFile)}" width="${width}">`);
        }

                // Initialize sidebar toggle on page load
                function initSidebarToggle() {
                    var sidebarToggle = document.querySelector('[data-action="toggle-sidebar"]');
                    var shell = document.querySelector('.modern-shell');

                    function closeSidebar() {
                        shell.classList.remove('modern-sidebar-collapsed');
                        document.body.classList.remove('sidebar-open');
                    }

                    if (sidebarToggle && shell) {
                        // Remove any existing listeners by cloning and replacing
                        var newToggle = sidebarToggle.cloneNode(true);
                        sidebarToggle.parentNode.replaceChild(newToggle, sidebarToggle);

                        // Add new event listener
                        newToggle.addEventListener('click', function (event) {
                            event.preventDefault();
                            event.stopPropagation();
                            shell.classList.toggle('modern-sidebar-collapsed');
                            document.body.classList.toggle('sidebar-open', shell.classList.contains('modern-sidebar-collapsed'));
                        });

                        // Close sidebar when clicking on content area (mobile only)
                        var contentArea = document.querySelector('.modern-content-area');
                        if (contentArea) {
                            contentArea.addEventListener('click', function(e) {
                                var isMobile = window.innerWidth <= 992;
                                if (isMobile && shell.classList.contains('modern-sidebar-collapsed')) {
                                    // Close sidebar if not clicking on sidebar
                                    var sidebar = document.querySelector('.modern-sidebar');
                                    if (sidebar && !sidebar.contains(e.target) && e.target !== newToggle) {
                                        closeSidebar();
                                    }
                                }
                            });
                        }

                        // Close sidebar when clicking the backdrop (mobile only)
                        var sidebar = document.querySelector('.modern-sidebar');
                        if (sidebar) {
                            sidebar.addEventListener('click', function(e) {
                                var isMobile = window.innerWidth <= 992;
                                if (isMobile && e.target === sidebar) {
                                    closeSidebar();
                                }
                            });
                        }
                    }
                }

        // Run on DOM ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initSidebarToggle);
        } else {
            initSidebarToggle();
        }
    </script>
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function () {
                navigator.serviceWorker.register('{{ asset("sw.js") }}')
                    .then(function (reg) { console.log('ServiceWorker registered', reg.scope); })
                    .catch(function (err) { console.warn('ServiceWorker registration failed:', err); });
            });
        }
    </script>
    @stack('scripts')
</body>
</html>
