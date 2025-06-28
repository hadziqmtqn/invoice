<!doctype html>

<html
    lang="en"
    class="light-style layout-navbar-fixed layout-menu-fixed layout-compact"
    dir="ltr"
    data-theme="theme-default"
    data-assets-path="{{ url('https://hadziqmtqn.github.io/materialize/assets/') }}"
    data-template="vertical-menu-template">
<head>
    <meta charset="utf-8" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>{{ !isset($subTitle) ? $title : $subTitle }} | {{ config('app.name') }}</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/favicon.png') }}" />

    @include('layouts.head')
    @yield('styles')
</head>

<body>
<!-- Layout wrapper -->
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
        @include('layouts.menu')

        <!-- Layout container -->
        <div class="layout-page">
            <!-- Content wrapper -->
            @include('layouts.navbar')

            <div class="content-wrapper">
                <!-- Content -->

                <div class="container-xxl flex-grow-1 container-p-y">
                    @yield('content')
                </div>
                <!--/ Content -->

                <!-- Footer -->
                <footer class="content-footer footer bg-footer-theme">
                    <div class="container-xxl">
                        <div class="footer-container py-3 text-center">
                            <div class="mb-2 mb-md-0">
                                © {{ now()->year }}, made with <span class="text-danger"><i class="tf-icons mdi mdi-heart"></i></span> by
                                <a href="/" class="footer-link fw-medium">Tim Dev {{ config('app.name') }}</a>
                            </div>
                        </div>
                    </div>
                </footer>
                <!-- / Footer -->

                <div class="content-backdrop fade"></div>
            </div>
            <!--/ Content wrapper -->
        </div>

        <!--/ Layout container -->
        @include('layouts.flash')
    </div>
</div>

<!-- Overlay -->
<div class="layout-overlay layout-menu-toggle"></div>

<!-- Drag Target Area To SlideIn Menu On Small Screens -->
<div class="drag-target"></div>

<!--/ Layout wrapper -->

<!-- Core JS -->
@include('layouts.script')
@yield('scripts')

<!-- Page JS -->
</body>
</html>
