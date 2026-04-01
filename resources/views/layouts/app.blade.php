<!DOCTYPE html>
<html lang="en-US" dir="ltr" data-navigation-type="default" data-navbar-horizontal-shape="default" data-turbo="false">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- ===============================================-->
    <!--    Document Title-->
    <!-- ===============================================-->
    <title>@section('title') Jengametrics @show</title>
    <meta name="description" content="@section('description') Jengametrics @show">

    <!-- ===============================================-->
    <!--    Favicons-->
    <!-- ===============================================-->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ favicon_url() }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ favicon_url() }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ favicon_url() }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ favicon_url() }}">
    <link rel="manifest" href="{{ favicon_url() }}">
    <meta name="msapplication-TileImage" content="{{ favicon_url() }}">
    <meta name="theme-color" content="#ffffff">

    @stack('styles')
    <script src="{{ asset('assets/metrics/vendors/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/metrics/assets/js/config.js') }}"></script>

    <!-- ===============================================-->
    <!--    Stylesheets-->
    <!-- ===============================================-->
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;600;700;800;900&amp;display=swap"
    rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;700&family=Inter:wght@400;500;600;700;800&family=Outfit:wght@400;500;600;700;800&display=swap"
    rel="stylesheet">
    <link href="{{ asset('assets/metrics/vendors/simplebar/simplebar.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/metrics/assets/css/line.css') }}">
    <link href="{{ asset('assets/metrics/assets/css/theme-rtl.min.css') }}" type="text/css" rel="stylesheet" id="style-rtl">
    <link href="{{ asset('assets/metrics/assets/css/theme.min.css') }}" type="text/css" rel="stylesheet" id="style-default">
    <link href="{{ asset('assets/metrics/assets/css/user-rtl.min.css') }}" type="text/css" rel="stylesheet"
    id="user-style-rtl">
    <link href="{{ asset('assets/metrics/assets/css/user.min.css') }}" type="text/css" rel="stylesheet"
    id="user-style-default">
    <link href="{{ asset('assets/metrics/vendors/prism/prism-okaidia.css') }}" rel="stylesheet">
    {{-- Turbo Drive disabled (data-turbo=false on <html>): full page loads for Blade + reliable /boq /boms navigation --}}

    <!-- ===============================================-->
    <!--    Additional Scripts and Styles-->
    <!-- ===============================================-->
    <script>
        (function () {
            if (!Element.prototype.matches) {
                Element.prototype.matches = Element.prototype.msMatchesSelector || Element.prototype.webkitMatchesSelector;
            }

            if (!Element.prototype.closest) {
                Element.prototype.closest = function (selector) {
                    let element = this;
                    while (element) {
                        if (element.matches(selector)) {
                            return element;
                        }
                        element = element.parentElement;
                    }
                    return null;
                };
            }

            if (!NodeList.prototype.forEach) {
                NodeList.prototype.forEach = function (callback, thisArg) {
                    thisArg = thisArg || window;
                    for (let i = 0; i < this.length; i++) {
                        callback.call(thisArg, this[i], i, this);
                    }
                };
            }

            if (!window.fetch) {
                window.fetch = function (url, options) {
                    options = options || {};
                    return new Promise(function (resolve, reject) {
                        const request = new XMLHttpRequest();
                        request.open(options.method || 'GET', url, true);

                        if (options.headers) {
                            Object.keys(options.headers).forEach(function (header) {
                                request.setRequestHeader(header, options.headers[header]);
                            });
                        }

                        request.onload = function () {
                            const status = request.status;
                            const ok = status >= 200 && status < 300;
                            const response = {
                                ok: ok,
                                status: status,
                                statusText: request.statusText,
                                text: function () {
                                    return Promise.resolve(request.responseText);
                                },
                                json: function () {
                                    return new Promise(function (resolve, reject) {
                                        try {
                                            resolve(JSON.parse(request.responseText || 'null'));
                                        } catch (error) {
                                            reject(error);
                                        }
                                    });
                                }
                            };
                            resolve(response);
                        };

                        request.onerror = function () {
                            reject(new TypeError('Network request failed'));
                        };

                        request.send(options.body || null);
                    });
                };
            }

            var phoenixIsRTL = window.config && window.config.config ? window.config.config.phoenixIsRTL : false;
            if (phoenixIsRTL) {
                var linkDefault = document.getElementById('style-default');
                var userLinkDefault = document.getElementById('user-style-default');
                if (linkDefault) {
                    linkDefault.setAttribute('disabled', true);
                }
                if (userLinkDefault) {
                    userLinkDefault.setAttribute('disabled', true);
                }
                document.documentElement.setAttribute('dir', 'rtl');
            } else {
                var linkRTL = document.getElementById('style-rtl');
                var userLinkRTL = document.getElementById('user-style-rtl');
                if (linkRTL) {
                    linkRTL.setAttribute('disabled', true);
                }
                if (userLinkRTL) {
                    userLinkRTL.setAttribute('disabled', true);
                }
            }
        })();
    </script>
    <link href="{{ asset('assets/metrics/vendors/leaflet/leaflet.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/metrics/vendors/leaflet.markercluster/MarkerCluster.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/metrics/vendors/leaflet.markercluster/MarkerCluster.Default.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/metrics/assets/css/ui-refresh.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/metrics/assets/css/app-shell.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/metrics/assets/css/new-ui-system.css') }}">

    @yield('page-css')

    <style>
        :root {
            --jm-primary: #027333;
            --jm-primary-strong: #015c2a;
            --jm-primary-soft: #e8f7ef;
            --jm-accent: #19a36b;
            --jm-title: #113026;
            --jm-text: #1f2b26;
            --jm-muted: #5d6f68;
            --jm-bg: #f4f7f5;
            --jm-surface: #ffffff;
            --jm-surface-alt: #f9fcfa;
            --jm-border: #d9e5de;
            --jm-shadow-sm: 0 8px 22px -18px rgba(2, 115, 51, 0.48);
            --jm-shadow-md: 0 18px 44px -26px rgba(2, 115, 51, 0.42);
            --jm-radius-sm: 10px;
            --jm-radius-md: 14px;
            --jm-radius-lg: 18px;
        }

        [data-bs-theme='dark'] {
            --jm-title: #e5f2ec;
            --jm-text: #d6e7e0;
            --jm-muted: #9fb8af;
            --jm-bg: #161e1a;
            --jm-surface: #1e2823;
            --jm-surface-alt: #243029;
            --jm-border: #2f3e36;
        }

        body.jm-theme[data-ui-skin='non-admin'] .content {
            color: var(--jm-text);
        }

        body.jm-theme[data-ui-skin='non-admin'] .content .card {
            border: 1px solid var(--jm-border);
            border-radius: var(--jm-radius-lg);
            background: var(--jm-surface);
            box-shadow: var(--jm-shadow-sm);
        }

        body.jm-theme[data-ui-skin='non-admin'] .content .card .card-header {
            background: transparent;
            border-bottom-color: var(--jm-border);
        }

        body.jm-theme[data-ui-skin='non-admin'] .content h1,
        body.jm-theme[data-ui-skin='non-admin'] .content h2,
        body.jm-theme[data-ui-skin='non-admin'] .content h3,
        body.jm-theme[data-ui-skin='non-admin'] .content h4,
        body.jm-theme[data-ui-skin='non-admin'] .content h5 {
            color: var(--jm-title);
        }

        body.jm-theme[data-ui-skin='non-admin'] .content .text-muted {
            color: var(--jm-muted) !important;
        }

        body.jm-theme[data-ui-skin='non-admin'] .content .btn {
            border-radius: var(--jm-radius-sm);
        }

        body.jm-theme[data-ui-skin='non-admin'] .content .btn-success {
            background-color: var(--jm-primary);
            border-color: var(--jm-primary);
        }

        body.jm-theme[data-ui-skin='non-admin'] .content .btn-success:hover,
        body.jm-theme[data-ui-skin='non-admin'] .content .btn-success:focus {
            background-color: var(--jm-primary-strong);
            border-color: var(--jm-primary-strong);
        }

        body.jm-theme[data-ui-skin='non-admin'] .content .btn-primary {
            background-color: var(--jm-primary);
            border-color: var(--jm-primary);
        }

        body.jm-theme[data-ui-skin='non-admin'] .content .btn-primary:hover,
        body.jm-theme[data-ui-skin='non-admin'] .content .btn-primary:focus {
            background-color: var(--jm-primary-strong);
            border-color: var(--jm-primary-strong);
        }

        body.jm-theme[data-ui-skin='non-admin'] .content .btn-outline-primary {
            color: var(--jm-primary);
            border-color: var(--jm-primary);
        }

        body.jm-theme[data-ui-skin='non-admin'] .content .btn-outline-primary:hover,
        body.jm-theme[data-ui-skin='non-admin'] .content .btn-outline-primary:focus {
            color: #fff;
            background-color: var(--jm-primary);
            border-color: var(--jm-primary);
        }

        body.jm-theme[data-ui-skin='non-admin'] .content .badge.bg-success {
            background-color: var(--jm-primary) !important;
        }

        body.jm-theme[data-ui-skin='non-admin'] .content .form-control,
        body.jm-theme[data-ui-skin='non-admin'] .content .form-select,
        body.jm-theme[data-ui-skin='non-admin'] .content .input-group-text {
            border-color: var(--jm-border);
            border-radius: var(--jm-radius-sm);
        }

        body.jm-theme[data-ui-skin='non-admin'] .content .modal-content {
            border: 1px solid var(--jm-border);
            border-radius: var(--jm-radius-md);
            background: var(--jm-surface);
        }

        body.jm-theme[data-ui-skin='non-admin'] .content .table {
            border-color: var(--jm-border);
            color: var(--jm-text);
        }

        body.jm-theme[data-ui-skin='non-admin'] .content .table > :not(caption) > * > * {
            border-bottom-color: var(--jm-border);
        }

        body.jm-theme[data-ui-skin='non-admin'] .content .table thead th,
        body.jm-theme[data-ui-skin='non-admin'] .content .table-light th {
            background: var(--jm-surface-alt) !important;
            color: var(--jm-muted);
            font-weight: 700;
            font-size: 0.8125rem;
            letter-spacing: 0.02em;
            text-transform: uppercase;
        }

        body.jm-theme[data-ui-skin='non-admin'] .content a:not(.btn):not(.nav-link) {
            color: var(--jm-primary-strong);
        }

        body.jm-theme[data-ui-skin='non-admin'] .content a:not(.btn):not(.nav-link):hover {
            color: var(--jm-primary);
        }

        .jm-ui-title {
            color: var(--jm-primary) !important;
            font-weight: 700;
        }

        .jm-ui-muted {
            color: var(--jm-muted) !important;
        }

        .jm-ui-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.35rem 0.7rem;
            border-radius: 999px;
            border: 1px solid var(--jm-border);
            background: var(--jm-primary-soft);
            color: var(--jm-primary-strong);
            font-size: 0.82rem;
            font-weight: 600;
        }

        .jm-ui-card {
            border: 1px solid var(--jm-border);
            border-radius: var(--jm-radius-lg);
            background: var(--jm-surface);
            box-shadow: var(--jm-shadow-sm);
        }

        .jm-ui-surface {
            border: 1px solid var(--jm-border);
            border-radius: var(--jm-radius-md);
            background: var(--jm-surface-alt);
        }

        .jm-ui-table-wrap {
            border: 1px solid var(--jm-border);
            border-radius: var(--jm-radius-md);
            overflow: hidden;
            background: var(--jm-surface);
        }

        .modal {
            z-index: 1050 !important;
            /* Ensure the modal is above other elements */
        }

        .modal-backdrop {
            z-index: 1040 !important;
            /* Ensure the backdrop is below the modal */
        }

        a.btn:hover {
            transform: scale(1.02);
            transition: transform 0.2s ease-in-out;
        }

        button:hover {
            transform: scale(1.02);
            transition: transform 0.2s ease-in-out;
        }

        .green_text {
            color: #027333;
        }

        .write-disabled {
            opacity: 0.55;
            cursor: not-allowed;
        }
    </style>
</head>

<body class="jm-theme jm-body-reset jm-app-shell-page"
    data-ui-skin="{{ auth()->check() && auth()->user()->is_admin() ? 'admin' : 'non-admin' }}"
    data-user-is-subaccount="{{ auth()->check() && auth()->user()->isSubAccount() ? '1' : '0' }}"
    data-can-manage-boq="{{ auth()->check() && auth()->user()->can_manage_boq ? '1' : '0' }}"
    data-can-manage-materials="{{ auth()->check() && auth()->user()->can_manage_materials ? '1' : '0' }}"
    data-can-manage-labour="{{ auth()->check() && auth()->user()->can_manage_labour ? '1' : '0' }}"
>
    <!-- ===============================================-->
    <!--    Main Content-->
    <!-- ===============================================-->
    <main class="main" id="top">
        @php
            $jmPath = request()->path();
            $jmUser = \Illuminate\Support\Facades\Auth::user();
            $jmNav = [
                'dashboard' => request()->routeIs('dashboard'),
                'boq' => request()->is('boq') || request()->is('bq_documents') || request()->is('bq_documents/*'),
                'boms' => request()->is('boms') || request()->is('boms/*'),
                'material' => request()->is('requisitions', 'requisitions/*', 'materials', 'materials/*'),
                'req' => request()->is('requisitions', 'requisitions/*'),
                'mat_del' => request()->is('materials/delivered'),
                'mat_inv' => request()->is('materials/inventory'),
                'mat_use' => request()->is('materials/usage'),
                'labour' => request()->is('workers', 'workers/*', 'attendance', 'attendance/*', 'labour-tasks', 'labour-tasks/*') || str_contains($jmPath, '/payments'),
                'cost' => request()->is('cost-tracking', 'cost-tracking/*'),
                'reports' => request()->is('reports', 'reports/*'),
                'settings' => request()->routeIs('settings.*') || request()->routeIs('profile.*') || request()->is('admin/settings'),
            ];
            $jmNameParts = preg_split('/\s+/', trim((string) ($jmUser->name ?? 'User')));
            $jmInitials = collect($jmNameParts)->filter()->take(2)->map(fn ($part) => strtoupper(mb_substr($part, 0, 1)))->implode('');
            $jmRoleLabel = $jmUser->isSubAccount() ? 'Sub Account User' : 'Primary Account User';

            $jmPageTitle = 'Dashboard';
            if (request()->is('requisitions', 'requisitions/*')) {
                $jmPageTitle = 'Material Requisitions';
            } elseif (request()->is('materials/delivered', 'materials/delivered/*')) {
                $jmPageTitle = 'Materials Delivered';
            } elseif (request()->is('materials/inventory', 'materials/inventory/*')) {
                $jmPageTitle = 'Inventory Management';
            } elseif (request()->is('materials/usage', 'materials/usage/*')) {
                $jmPageTitle = 'Stock Usage';
            } elseif ($jmNav['boq']) {
                $jmPageTitle = 'Bills of Quantities';
            } elseif ($jmNav['boms']) {
                $jmPageTitle = 'Bills of Materials';
            } elseif ($jmNav['material']) {
                $jmPageTitle = 'Manage Material';
            } elseif ($jmNav['labour']) {
                $jmPageTitle = 'Labour';
            } elseif ($jmNav['cost']) {
                $jmPageTitle = 'Cost Tracking';
            } elseif ($jmNav['reports']) {
                $jmPageTitle = 'Reporting';
            } elseif ($jmNav['settings']) {
                $jmPageTitle = 'Settings';
            } elseif (request()->is('sub-accounts', 'sub-accounts/*')) {
                $jmPageTitle = 'Sub Accounts';
            } elseif (request()->is('account', 'profile')) {
                $jmPageTitle = 'Account';
            }
        @endphp
        <div class="jm-shell-root">
            <div class="jm-shell-row">
                <div class="jm-shell-sidebar-wrap">
                    <nav class="navbar navbar-vertical navbar-expand-lg jm-app-sidebar">
                        <div class="collapse navbar-collapse" id="navbarVerticalCollapse">
                            <div class="navbar-vertical-content">
                                <div class="jm-sidebar-brand">
                                    <span class="jm-sidebar-brand-mark" aria-hidden="true">🏗️</span>
                                    <span class="jm-sidebar-brand-text">Jenga<span>Metrics</span></span>
                                </div>
                                <div class="jm-sidebar-scroll">
                                <ul class="navbar-nav flex-column" id="navbarVerticalNav">
                                    <div class="nav-item">
                                        @if(\Illuminate\Support\Facades\Auth::user()->is_client())
                                            <p class="jm-sidebar-section-label">Apps</p>
                                        @endif
                                        <!-- Dashboard (all clients) -->
                                        <div class="nav-item-wrapper">
                                            <a class="nav-link label-1 {{ $jmNav['dashboard'] ? 'jm-nav-active' : '' }}" href="{{ route('dashboard') }}" role="button" aria-expanded="false">
                                                <div class="d-flex align-items-center">
                                                    <span class="nav-link-icon"><span data-feather="grid"></span></span>
                                                    <span class="nav-link-text-wrapper">
                                                        <span class="nav-link-text">Dashboard</span>
                                                    </span>
                                                </div>
                                            </a>
                                        </div>

                                        @if(\Illuminate\Support\Facades\Auth::user()->is_client())
                                            <div class="nav-item-wrapper">
                                                <a class="nav-link label-1 {{ $jmNav['boq'] ? 'jm-nav-active' : '' }}" href="{{ route('boq') }}" role="button" aria-expanded="false">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-icon"><span data-feather="file-text"></span></span>
                                                        <span class="nav-link-text-wrapper">
                                                            <span class="nav-link-text">Bills of Quantities</span>
                                                        </span>
                                                    </div>
                                                </a>
                                            </div>

                                            <div class="nav-item-wrapper">
                                                <a class="nav-link label-1 {{ $jmNav['boms'] ? 'jm-nav-active' : '' }}" href="{{ route('boms.index') }}" role="button" aria-expanded="false">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-icon"><span data-feather="layers"></span></span>
                                                        <span class="nav-link-text-wrapper">
                                                            <span class="nav-link-text">Bills of Materials</span>
                                                        </span>
                                                    </div>
                                                </a>
                                            </div>

                                            <div class="nav-item-wrapper has-submenu {{ $jmNav['material'] ? 'open' : '' }}">
                                                <div class="jm-submenu-row">
                                                    <a class="nav-link label-1 {{ $jmNav['material'] ? 'jm-nav-active' : '' }}"
                                                        href="{{ route('materials.index') }}">
                                                        <div class="d-flex align-items-center">
                                                            <span class="nav-link-icon"><span data-feather="box"></span></span>
                                                            <span class="nav-link-text-wrapper">
                                                                <span class="nav-link-text">Manage Material</span>
                                                            </span>
                                                        </div>
                                                    </a>
                                                    <button
                                                        type="button"
                                                        class="jm-submenu-toggle-btn"
                                                        aria-label="Toggle material submenu"
                                                        aria-expanded="{{ $jmNav['material'] ? 'true' : 'false' }}"
                                                        onclick="toggleDropdown(this)">
                                                        <span data-feather="chevron-down"></span>
                                                    </button>
                                                </div>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item nav-link label-1 {{ $jmNav['req'] ? 'jm-nav-active' : '' }}" href="{{ route('requisitions.index') }}">
                                                            <span class="nav-link-text">Requisitions</span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item nav-link label-1 {{ $jmNav['mat_del'] ? 'jm-nav-active' : '' }}" href="{{ route('materials.delivered') }}">
                                                            <span class="nav-link-text">Materials Delivered</span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item nav-link label-1 {{ $jmNav['mat_inv'] ? 'jm-nav-active' : '' }}" href="{{ route('materials.inventory') }}">
                                                            <span class="nav-link-text">Inventory Management</span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item nav-link label-1 {{ $jmNav['mat_use'] ? 'jm-nav-active' : '' }}" href="{{ route('materials.usage') }}">
                                                            <span class="nav-link-text">Stock Usage</span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>

                                            <div class="nav-item-wrapper">
                                                <a class="nav-link label-1 {{ $jmNav['settings'] ? 'jm-nav-active' : '' }}" title="Settings" href="{{ route('settings.index') }}" role="button" aria-expanded="false">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-icon"><span data-feather="settings"></span></span>
                                                        <span class="nav-link-text-wrapper">
                                                            <span class="nav-link-text">Settings</span>
                                                        </span>
                                                    </div>
                                                </a>
                                            </div>

                                            <p class="jm-sidebar-section-label">Workforce</p>
                                            <div class="nav-item-wrapper">
                                                <a class="nav-link label-1 {{ $jmNav['labour'] ? 'jm-nav-active' : '' }}" title="Manage your labour" href="{{ route('workers.index') }}" role="button" aria-expanded="false">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-icon"><span data-feather="users"></span></span>
                                                        <span class="nav-link-text-wrapper">
                                                            <span class="nav-link-text">Labour</span>
                                                        </span>
                                                    </div>
                                                </a>
                                            </div>

                                            <p class="jm-sidebar-section-label">Finance</p>
                                            <div class="nav-item-wrapper">
                                                <a class="nav-link label-1 {{ $jmNav['cost'] ? 'jm-nav-active' : '' }}" title="Track your costs" href="{{ route('cost-tracking.index') }}" role="button" aria-expanded="false">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-icon"><span data-feather="trending-up"></span></span>
                                                        <span class="nav-link-text-wrapper">
                                                            <span class="nav-link-text">Cost Tracking</span>
                                                        </span>
                                                    </div>
                                                </a>
                                            </div>

                                            <div class="nav-item-wrapper">
                                                <a class="nav-link label-1 {{ $jmNav['reports'] ? 'jm-nav-active' : '' }}" title="Reports" href="{{ route('reports') }}" role="button" aria-expanded="false">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-icon"><span data-feather="file"></span></span>
                                                        <span class="nav-link-text-wrapper">
                                                            <span class="nav-link-text">Reporting</span>
                                                        </span>
                                                    </div>
                                                </a>
                                            </div>
                                        @endif             

                                        @if(\Illuminate\Support\Facades\Auth::user()->is_admin())
                                            <!-- Sales & Payment Section -->
                                            <div class="nav-item-wrapper">
                                                <a class="nav-link dropdown-indicator label-1" href="#sidebarSalesPayment" role="button" data-bs-toggle="collapse" aria-expanded="false" aria-controls="sidebarSalesPayment">
                                                    <div class="d-flex align-items-center">
                                                        <div class="dropdown-indicator-icon-wrapper">
                                                            <span class="fas fa-caret-right dropdown-indicator-icon"></span>
                                                        </div>
                                                        <span class="nav-link-icon"><span data-feather="credit-card"></span></span>
                                                        <span class="nav-link-text">Clients</span>
                                                    </div>
                                                </a>
                                                <div class="parent-wrapper label-1 ">
                                                    <ul class="nav collapse parent {{ request()->is('transactions') || request()->is('billings') || request()->is('recurring-invoices') || request()->is('admin/listofusers') || request()->is('products') ? 'show' : '' }}"
                                                        data-bs-parent="#navbarVerticalCollapse" id="sidebarSalesPayment">
                                                        <li class="nav-item">
                                                            <a class="nav-link {{ request()->is('admin/listofusers') ? 'active' : '' }}" href="{{ url('listofadmins') }}">
                                                                <div class="d-flex align-items-center">
                                                                    <span class="nav-link-text">Merchants</span>
                                                                </div>
                                                            </a>
                                                        </li>

                                                        <li class="nav-item">
                                                            <a class="nav-link {{ request()->is('transactions') ? 'active' : '' }}" href="/transactions">
                                                                <div class="d-flex align-items-center">
                                                                    <span class="nav-link-text">Payments</span>
                                                                </div>
                                                            </a>
                                                        </li>

                                                        <li class="nav-item"><a
                                                            class="nav-link {{ request()->is('billings') ? 'active' : '' }}"
                                                            href="/billings">
                                                            <div class="d-flex align-items-center"><span
                                                                class="nav-link-text">Invoices</span></div>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>

                                            <div class="nav-item-wrapper">
                                                <a class="nav-link dropdown-indicator label-1" href="#settings" role="button" data-bs-toggle="collapse" aria-expanded="false" aria-controls="settings">
                                                    <div class="d-flex align-items-center">
                                                        <div class="dropdown-indicator-icon-wrapper">
                                                            <span class="fas fa-caret-right dropdown-indicator-icon"></span>
                                                        </div>
                                                        <span class="nav-link-icon"><span data-feather="settings"></span></span>
                                                        <span class="nav-link-text">Settings</span>
                                                    </div>
                                                </a>

                                                <div class="parent-wrapper label-1">
                                                    <ul class="nav collapse parent {{ request()->is('transactions') || request()->is('reconciliation') || request()->is('chart-of-accounts') ? 'show' : '' }}"
                                                        data-bs-parent="#navbarVerticalCollapse" id="settings">
                                                        <li class="nav-item">
                                                            <a class="nav-link" href="{{ url('website_info')}}">
                                                                <div class="d-flex align-items-center"><span
                                                                    class="nav-link-text">Website setting</span>
                                                                </div>
                                                            </a>
                                                        </li>

                                                        <li class="nav-item">
                                                            <a class="nav-link {{ request()->is('sections') ? 'active' : '' }}" href="{{ route('sections.index') }}">
                                                                <div class="d-flex align-items-center">
                                                                    <span class="nav-link-text">Sections</span>
                                                                </div>
                                                            </a>
                                                        </li>

                                                        <li class="nav-item">
                                                            <a class="nav-link" href="{{ route('products.index') }}">
                                                                <div class="d-flex align-items-center">
                                                                    <span class="nav-link-text">Materials Library</span>
                                                                </div>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </ul>
                                </div>{{-- .jm-sidebar-scroll --}}
                                @if($jmUser->is_client())
                                    <a href="{{ route('account') }}" class="jm-sidebar-account text-decoration-none">
                                        <div class="jm-sidebar-account-avatar">{{ $jmInitials ?: 'U' }}</div>
                                        <div>
                                            <div class="jm-sidebar-account-name">{{ $jmUser->name }}</div>
                                            <div class="jm-sidebar-account-role">{{ $jmRoleLabel }}</div>
                                        </div>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </nav>
                </div>
                <div class="jm-shell-main-wrap">
                    <nav class="navbar navbar-top navbar-expand jm-app-topbar" id="navbarDefault">
                        <div class="navbar-collapse justify-content-between">
                            <div class="navbar-logo d-flex align-items-center gap-2">
                                <a class="navbar-brand jm-topbar-mobile-brand d-flex d-lg-none align-items-center gap-2 mb-0 me-0" href="{{ route('dashboard') }}">
                                    <span class="jm-sidebar-brand-mark" aria-hidden="true">🏗️</span>
                                    <span class="jm-sidebar-brand-text">Jenga<span>Metrics</span></span>
                                </a>
                                <div class="jm-topbar-context d-none d-lg-flex align-items-center gap-2">
                                    <span class="jm-topbar-title">{{ $jmPageTitle }}</span>
                                    <span class="jm-topbar-period">{{ now()->format('F Y') }}</span>
                                </div>
                            </div>
                            <ul class="navbar-nav navbar-nav-icons flex-row align-items-center">
                                <li class="nav-item d-none d-lg-block me-2">
                                    <button type="button" class="btn jm-topbar-bell" aria-label="Notifications">
                                        <span data-feather="bell"></span>
                                        <span class="jm-topbar-bell-dot"></span>
                                    </button>
                                </li>
                                @if(\Illuminate\Support\Facades\Auth::user()->is_client())
                                    <li class="nav-item d-none d-lg-block me-2">
                                        @if(request()->routeIs('dashboard'))
                                            <button type="button" class="btn btn-sm btn-outline-secondary jm-topbar-action" data-bs-toggle="modal" data-bs-target="#projectStepsModal">
                                                Project Steps
                                            </button>
                                        @else
                                            <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-secondary jm-topbar-action">Project Steps</a>
                                        @endif
                                    </li>
                                    <li class="nav-item d-none d-lg-block me-2">
                                        <a href="{{ route('boq') }}" class="btn btn-sm btn-outline-secondary jm-topbar-action">+ New BOQ</a>
                                    </li>
                                    <li class="nav-item d-none d-lg-block me-2">
                                        <a href="{{ route('wizard') }}" class="btn btn-sm btn-success jm-topbar-action jm-topbar-action-primary">+ New Project</a>
                                    </li>
                                @endif
                                @if(!\Illuminate\Support\Facades\Auth::user()->is_client())
                                    <li class="nav-item">
                                        <div class="theme-control-toggle fa-icon-wait px-2">
                                            <input class="form-check-input ms-0 theme-control-toggle-input" type="checkbox" data-theme-control="phoenixTheme" value="dark" id="themeControlToggle" />
                                            <label class="mb-0 theme-control-toggle-label theme-control-toggle-light jm-theme-toggle-btn" for="themeControlToggle" data-bs-toggle="tooltip" data-bs-placement="left"
                                                data-bs-title="Switch theme">
                                                <span class="icon" data-feather="sun"></span>
                                            </label>
                                            <label class="mb-0 theme-control-toggle-label theme-control-toggle-dark jm-theme-toggle-btn" for="themeControlToggle" data-bs-toggle="tooltip" data-bs-placement="left"
                                                data-bs-title="Switch theme">
                                                <span class="icon" data-feather="moon"></span>
                                            </label>
                                        </div>
                                    </li>
                                @endif
                                <li class="nav-item d-none d-lg-block ms-2">
                                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-secondary jm-topbar-action">Logout</button>
                                    </form>
                                </li>
                                <li class="nav-item dropdown {{ $jmUser->is_client() ? 'd-lg-none' : '' }}">
                                    <a class="nav-link lh-1 pe-2" id="navbarDropdownUser" href="#!" role="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true"
                                        aria-expanded="false">
                                        <div class="d-flex align-items-center">
                                            <span class="jm-project-chip">{{ project() }}</span>
                                            <span class="ms-2 fs-8"><span data-feather="chevron-down"></span></span>
                                        </div>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end navbar-dropdown-caret py-0 dropdown-profile shadow border" aria-labelledby="navbarDropdownUser">
                                        <div class="card position-relative border-0">
                                            <div class="card-body p-0">
                                                @php
                                                    $photoUrl = null;
                                                    $picturePath = \Illuminate\Support\Facades\Auth::user()->photo ?? null;

                                                    if (!empty($picturePath)) {
                                                        if (preg_match('/^https?:\/\//i', $picturePath)) {
                                                            $photoUrl = $picturePath;
                                                        } elseif (\Illuminate\Support\Str::startsWith($picturePath, ['storage/', '/storage/'])) {
                                                            $photoUrl = asset($picturePath);
                                                        } else {
                                                            $photoUrl = asset('storage/' . ltrim($picturePath, '/'));
                                                        }
                                                    }
                                                @endphp
                                                <div class="text-center pt-4 pb-3">
                                                    <div class="avatar avatar-xl ">
                                                        <img class="rounded-circle " src="{{ $photoUrl ?? asset('assets/media/svg/avatars/blank.svg') }}"
                                                        alt="" />
                                                    </div>
                                                    <h6 class="mt-2 text-body-emphasis">{{ \Illuminate\Support\Facades\Auth::user()->name }}</h6>
                                                </div>
                                            </div>
                                            <div>
                                                @if(\Illuminate\Support\Facades\Auth::user()->is_client())
                                                    @if(\Illuminate\Support\Facades\Auth::user()->package)
                                                        @if(package(\Illuminate\Support\Facades\Auth::user()->package)->name == "Basic")
                                                            <div class="help-box text-center">
                                                                <p class="mb-3 mt-2 text-muted">
                                                                    <strong>{{ package(\Illuminate\Support\Facades\Auth::user()->package)->name }}</strong><br>
                                                                    Upgrade your plan and get the most out of JengaMetrics
                                                                </p>
                                                                <div class="mt-3">
                                                                    <a href="{{ route('subscribe') }}" class="btn btn-success"> Upgrade now</a>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endif
                                                @endif

                                                <ul class="nav d-flex flex-column mb-2 pb-1">
                                                    @if(\Illuminate\Support\Facades\Auth::user()->is_client())
                                                        <li class="nav-item">
                                                            <a class="nav-link px-3 d-block" href="{{ route('settings.index') }}">
                                                                <span class="me-2 text-body align-bottom" data-feather="settings"></span>
                                                                Settings
                                                            </a>
                                                        </li>
                                                        @if(\Illuminate\Support\Facades\Auth::user()->project_id)
                                                            <li class="nav-item">
                                                                <a class="nav-link px-3 d-block" href="#" data-bs-toggle="modal" data-bs-target="#switchbusinesses">
                                                                    <span class="me-2 text-body align-bottom" data-feather="menu"></span>
                                                                    Projects
                                                                </a>
                                                            </li>
                                                        @endif
                                                    @endif
                                                </ul>
                                            </div>
                                            <div class="card-footer p-0 border-top border-translucent">
                                                <ul class="nav d-flex flex-column my-3">
                                                    @if(\Illuminate\Support\Facades\Auth::user()->is_client())
                                                        <a class="dropdown-item" href="{{ route('settings.index') }}">
                                                            <i class="fa fa-cog"></i>
                                                            <span key="t-settings">Settings</span>
                                                        </a>
                                                        <!-- @if(!\Illuminate\Support\Facades\Auth::user()->isSubAccount())
                                                            <a class="dropdown-item" href="{{ url('billings') }}">
                                                                <i class="fa fa-users"></i>
                                                                <span key="t-profile">Manage your billings</span>
                                                            </a>
                                                            <a class="dropdown-item" href="{{ url('subscribe') }}">
                                                                <i class="fa fa-users"></i>
                                                                <span key="t-profile">Manage your subscriptions</span>
                                                            </a>
                                                        @endif -->
                                                    @endif
                                                </ul>
                                                <hr />
                                                <div class="px-3"> 
                                                    <form method="POST" action="{{ route('logout') }}">
                                                        @csrf
                                                        <x-responsive-nav-link :href="route('logout')" class="btn btn-phoenix-secondary d-flex flex-center w-100"
                                                            onclick="event.preventDefault();
                                                            this.closest('form').submit();">
                                                            <span class="me-2" data-feather="log-out"></span>
                                                                Sign out
                                                        </x-responsive-nav-link>
                                                    </form>
                                                </div>

                                                <div class="my-2 text-center fw-bold fs-10 text-body-quaternary"><a
                                                    class="text-body-quaternary me-1" href="#!">Privacy policy</a>&bull;<a
                                                    class="text-body-quaternary mx-1" href="#!">Terms</a>&bull;<a
                                                    class="text-body-quaternary ms-1" href="#!">Cookies</a></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </nav>
                    <div class="content {{ request()->routeIs('dashboard') ? '' : 'jm-dashboard-inherit' }}">
                        @yield('content')
                    </div>
                    <script>
                        var navbarTopShape = window.config.config.phoenixNavbarTopShape;
                        var navbarPosition = window.config.config.phoenixNavbarPosition;
                        var body = document.querySelector('body');
                        var navbarDefault = document.querySelector('#navbarDefault');
                        var navbarTop = document.querySelector('#navbarTop');
                        var topNavSlim = document.querySelector('#topNavSlim');
                        var navbarTopSlim = document.querySelector('#navbarTopSlim');
                        var navbarCombo = document.querySelector('#navbarCombo');
                        var navbarComboSlim = document.querySelector('#navbarComboSlim');
                        var dualNav = document.querySelector('#dualNav');

                        var documentElement = document.documentElement;
                        var navbarVertical = document.querySelector('.navbar-vertical');

                        if (navbarPosition === 'dual-nav') {
                            topNavSlim?.remove();
                            navbarTop?.remove();
                            navbarTopSlim?.remove();
                            navbarCombo?.remove();
                            navbarComboSlim?.remove();
                            navbarDefault?.remove();
                            navbarVertical?.remove();
                            dualNav.removeAttribute('style');
                            document.documentElement.setAttribute('data-navigation-type', 'dual');

                        } else if (navbarTopShape === 'slim' && navbarPosition === 'vertical') {
                            navbarDefault?.remove();
                            navbarTop?.remove();
                            navbarTopSlim?.remove();
                            navbarCombo?.remove();
                            navbarComboSlim?.remove();
                            topNavSlim.style.display = 'block';
                            navbarVertical.style.display = 'inline-block';
                            document.documentElement.setAttribute('data-navbar-horizontal-shape', 'slim');

                        } else if (navbarTopShape === 'slim' && navbarPosition === 'horizontal') {
                            navbarDefault?.remove();
                            navbarVertical?.remove();
                            navbarTop?.remove();
                            topNavSlim?.remove();
                            navbarCombo?.remove();
                            navbarComboSlim?.remove();
                            dualNav?.remove();
                            navbarTopSlim.removeAttribute('style');
                            document.documentElement.setAttribute('data-navbar-horizontal-shape', 'slim');
                        } else if (navbarTopShape === 'slim' && navbarPosition === 'combo') {
                            navbarDefault?.remove();
                            navbarTop?.remove();
                            topNavSlim?.remove();
                            navbarCombo?.remove();
                            navbarTopSlim?.remove();
                            dualNav?.remove();
                            navbarComboSlim.removeAttribute('style');
                            navbarVertical.removeAttribute('style');
                            document.documentElement.setAttribute('data-navbar-horizontal-shape', 'slim');
                        } else if (navbarTopShape === 'default' && navbarPosition === 'horizontal') {
                            navbarDefault?.remove();
                            topNavSlim?.remove();
                            navbarVertical?.remove();
                            navbarTopSlim?.remove();
                            navbarCombo?.remove();
                            navbarComboSlim?.remove();
                            dualNav?.remove();
                            navbarTop.removeAttribute('style');
                            document.documentElement.setAttribute('data-navigation-type', 'horizontal');
                        } else if (navbarTopShape === 'default' && navbarPosition === 'combo') {
                            topNavSlim?.remove();
                            navbarTop?.remove();
                            navbarTopSlim?.remove();
                            navbarDefault?.remove();
                            navbarComboSlim?.remove();
                            dualNav?.remove();
                            navbarCombo.removeAttribute('style');
                            navbarVertical.removeAttribute('style');
                            document.documentElement.setAttribute('data-navigation-type', 'combo');
                        } else {
                            topNavSlim?.remove();
                            navbarTop?.remove();
                            navbarTopSlim?.remove();
                            navbarCombo?.remove();
                            navbarComboSlim?.remove();
                            dualNav?.remove();
                            navbarDefault.removeAttribute('style');
                            navbarVertical.removeAttribute('style');
                        }

                        var navbarTopStyle = window.config.config.phoenixNavbarTopStyle;
                        var navbarTop = document.querySelector('.navbar-top');
                        if (navbarTopStyle === 'darker') {
                            navbarTop.setAttribute('data-navbar-appearance', 'darker');
                        }

                        var navbarVerticalStyle = window.config.config.phoenixNavbarVerticalStyle;
                        var navbarVertical = document.querySelector('.navbar-vertical');
                        if (navbarVerticalStyle === 'darker') {
                            navbarVertical.setAttribute('data-navbar-appearance', 'darker');
                        }
                    </script>
                    <div class="modal modal-blur fade" id="switchbusinesses" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Select project</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <?php
                                $projects = \Illuminate\Support\Facades\Auth::user()
                                    ?->accessibleProjects()
                                    ->orderBy('name')
                                    ->get() ?? collect();
                                ?>
                                <div class="modal-body">
                                    <table class="table">
                                        <tbody>
                                            @foreach ($projects as $project)
                                                <tr>
                                                    <td>
                                                        <b>{{ $loop->iteration }}. {{ $project->name }}</b>
                                                        <small class="text-muted">({{ $project->project_uid ?: 'N/A' }})</small>
                                                    </td>
                                                    <td>
                                                        <form action="{{ route('select_project') }}" method="POST"
                                                            class="d-inline">
                                                            @csrf
                                                            <input type="hidden" name="id" value="{{ $project->id }}">
                                                            <button type="submit" class="btn btn-success btn-sm">Select</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            @if (!auth()->user()->isSubAccount() && !auth()->user()->is_admin())
                                                <tr>
                                                    <td>
                                                        <a class="btn btn-success btn-small" href="{{ route('wizard') }}">
                                                            <span key="t-profile">Create project</span>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                        <!-- Pagination Links -->
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <style>
                        #toast-container {
                            position: fixed;
                            top: 1rem;
                            right: 1rem;
                            z-index: 1050;
                            display: flex;
                            flex-direction: column;
                            gap: 1rem;
                        }

                        .toast {
                            border-radius: 8px;
                            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
                            overflow: hidden;
                            animation: fadeInUp 0.5s ease-out;
                        }

                        .toast-header {
                            background-color: #f8f9fa;
                            border-bottom: 1px solid #e9ecef;
                        }

                        .toast-body {
                            font-size: 0.9rem;
                        }

                        .toast .badge {
                            margin-right: 0.5rem;
                            font-size: 0.75rem;
                        }

                        @keyframes fadeInUp {
                            from {
                                transform: translateY(20px);
                                opacity: 0;
                            }
                            to {
                                transform: translateY(0);
                                opacity: 1;
                            }
                        }
                    </style>
                    <div id="toast-container">
                        @if (\Session::has('success'))
                            <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="false">
                                <div class="toast-header">
                                    <span class="avatar avatar-xs me-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-success" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 5a2 2 0 0 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6" /><path d="M9 17v1a3 3 0 0 0 6 0v-1" /><path d="M21 6.727a11.05 11.05 0 0 0 -2.794 -3.727" /><path d="M3 6.727a11.05 11.05 0 0 1 2.792 -3.727" /></svg>
                                    </span>
                                    <strong class="me-auto">System</strong>
                                    <small>Just now</small>
                                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                                </div>
                                <div class="toast-body">
                                    <span class="badge bg-success">Success</span>
                                    {!! \Session::get('success') !!}
                                </div>
                            </div>
                        @endif 

                        @if (\Session::has('warning'))
                            <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="false">
                                <div class="toast-header">
                                    <span class="avatar avatar-xs me-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-warning" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 5a2 2 0 0 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6" /><path d="M9 17v1a3 3 0 0 0 6 0v-1" /><path d="M21 6.727a11.05 11.05 0 0 0 -2.794 -3.727" /><path d="M3 6.727a11.05 11.05 0 0 1 2.792 -3.727" /></svg>
                                    </span>
                                    <strong class="me-auto">System</strong>
                                    <small>Just now</small>
                                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                                </div>
                                <div class="toast-body">
                                    <span class="badge bg-warning">Warning</span>
                                    {!! \Session::get('warning') !!}
                                </div>
                            </div>
                        @endif
                        
                        @if (\Session::has('danger'))
                            <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="false">
                                <div class="toast-header">
                                    <span class="avatar avatar-xs me-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-danger" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 5a2 2 0 0 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6" /><path d="M9 17v1a3 3 0 0 0 6 0v-1" /><path d="M21 6.727a11.05 11.05 0 0 0 -2.794 -3.727" /><path d="M3 6.727a11.05 11.05 0 0 1 2.792 -3.727" /></svg>
                                    </span>
                                    <strong class="me-auto">System</strong>
                                    <small>Just now</small>
                                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                                </div>
                                <div class="toast-body">
                                    <span class="badge bg-danger">Error</span>
                                    {!! \Session::get('danger') !!}
                                </div>
                            </div>
                        @endif 
                    </div>

                    <!-- Global Delete Confirmation Modal -->
                    <div class="modal fade" id="globalDeleteConfirmModal" tabindex="-1" aria-labelledby="globalDeleteConfirmModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="globalDeleteConfirmModalLabel">Confirm Deletion</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body" id="globalDeleteConfirmModalBody">
                                    Are you sure you want to delete this record?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                    <button type="button" class="btn btn-danger" id="globalDeleteConfirmModalConfirm">Delete</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>{{-- .jm-shell-main-wrap --}}
            </div>{{-- .jm-shell-row --}}
        </div>{{-- .jm-shell-root --}}
    </main>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
    <script src="{{ asset('assets/metrics/vendors/popper/popper.min.js') }}" defer></script>
    <script src="{{ asset('assets/metrics/vendors/anchorjs/anchor.min.js') }}" defer></script>
    <script src="{{ asset('assets/metrics/vendors/is/is.min.js') }}" defer></script>
    <script src="{{ asset('assets/metrics/vendors/fontawesome/all.min.js') }}" defer></script>
    <script src="{{ asset('assets/metrics/vendors/lodash/lodash.min.js') }}" defer></script>
    <script src="{{ asset('assets/metrics/vendors/list.js/list.min.js') }}" defer></script>
    <script src="{{ asset('assets/metrics/vendors/feather-icons/feather.min.js') }}" defer></script>
    <script src="{{ asset('assets/metrics/vendors/dayjs/dayjs.min.js') }}" defer></script>
    <script src="{{ asset('assets/metrics/vendors/leaflet/leaflet.js') }}" defer></script>
    <script src="{{ asset('assets/metrics/vendors/leaflet.markercluster/leaflet.markercluster.js') }}" defer></script>
    <script src="{{ asset('assets/metrics/vendors/leaflet.tilelayer.colorfilter/leaflet-tilelayer-colorfilter.min.js') }}" defer></script>
    <script src="{{ asset('assets/metrics/assets/js/phoenix.js') }}" defer></script>
    <script src="{{ asset('assets/metrics/vendors/echarts/echarts.min.js') }}" defer></script>
    <script src="{{ asset('assets/metrics/assets/js/ecommerce-dashboard.js') }}" defer></script>
    <script src="{{ asset('assets/metrics/vendors/prism/prism.js') }}" defer></script>

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Toast Options (if using toastr.js) -->
    <script>
        var toastr_options = { closeButton: true };
    </script>

    <script>
        (function () {
            const htmlEl = document.documentElement;
            if (!htmlEl) {
                return;
            }

            htmlEl.classList.remove('navbar-vertical-collapsed');

            try {
                localStorage.setItem('navbarVerticalCollapsed', '0');
                localStorage.setItem('phoenixIsNavbarVerticalCollapsed', 'false');
            } catch (error) {
                // Ignore storage access errors.
            }
        })();
    </script>
    <script>
        (function () {
            const placeContentInShell = function () {
                const shellMain = document.querySelector('.jm-shell-main-wrap');
                const topbar = shellMain ? shellMain.querySelector('#navbarDefault') : null;
                const content = document.querySelector('.content');

                if (!shellMain || !topbar || !content) {
                    return;
                }

                if (!shellMain.contains(content)) {
                    topbar.insertAdjacentElement('afterend', content);
                    return;
                }

                if (topbar.nextElementSibling !== content) {
                    topbar.insertAdjacentElement('afterend', content);
                }
            };

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', placeContentInShell);
            } else {
                placeContentInShell();
            }
        })();
    </script>

    <script>
        (function () {
            const body = document.body;
            if (!body || body.dataset.userIsSubaccount !== '1') {
                return;
            }

            const access = {
                boq: body.dataset.canManageBoq === '1',
                materials: body.dataset.canManageMaterials === '1',
                labour: body.dataset.canManageLabour === '1',
            };
            const isReadOnlyUser = !access.boq && !access.materials && !access.labour;

            function inferRoleFromAction(action) {
                const path = (action || '').toLowerCase();
                if (path.includes('/materials') || path.includes('/suppliers') || path.includes('/requisitions')) {
                    return 'materials';
                }
                if (path.includes('/workers') || path.includes('/attendance') || path.includes('/payments')) {
                    return 'labour';
                }
                if (
                    path.includes('/bq') ||
                    path.includes('bq_') ||
                    path.includes('/boms') ||
                    path.includes('/sections') ||
                    path.includes('/elements') ||
                    path.includes('/items') ||
                    path.includes('/products') ||
                    path.includes('/libraries')
                ) {
                    return 'boq';
                }
                return null;
            }

            let lastDeniedAt = 0;
            function showWriteDeniedToast() {
                if (window.suppressWriteDeniedToast) {
                    return;
                }
                const now = Date.now();
                if (now - lastDeniedAt < 2000) {
                    return;
                }
                lastDeniedAt = now;

                const container = document.getElementById('toast-container');
                if (!container) {
                    alert('Write Access Denied');
                    return;
                }

                const toast = document.createElement('div');
                toast.className = 'toast show';
                toast.setAttribute('role', 'alert');
                toast.setAttribute('aria-live', 'assertive');
                toast.setAttribute('aria-atomic', 'true');
                toast.setAttribute('data-bs-autohide', 'true');

                toast.innerHTML = `
                    <div class="toast-header">
                        <strong class="me-auto">System</strong>
                        <small>Just now</small>
                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body">
                        <span class="badge bg-warning">Warning</span>
                        Write Access Denied
                    </div>
                `;

                container.appendChild(toast);
                setTimeout(() => {
                    toast.classList.remove('show');
                    toast.remove();
                }, 3500);
            }

            function isBackButton(field) {
                if (!field || field.tagName !== 'BUTTON') {
                    return false;
                }
                const label = (field.textContent || '').trim().toLowerCase();
                return label === 'back';
            }

            function shouldAllowReadonlyButton(button) {
                if (!button) {
                    return false;
                }
                if (button.hasAttribute('data-allow-readonly')) {
                    return true;
                }
                return isBackButton(button);
            }

            function disableFormFields(form) {
                const fields = form.querySelectorAll('input, select, textarea, button');
                fields.forEach((field) => {
                    if (field.type === 'hidden') {
                        return;
                    }
                    if (shouldAllowReadonlyButton(field)) {
                        return;
                    }
                    if (field.matches('button[type="submit"], input[type="submit"]')) {
                        field.classList.add('write-disabled');
                        field.setAttribute('aria-disabled', 'true');
                        return;
                    }
                    field.classList.add('write-disabled');
                    field.setAttribute('aria-disabled', 'true');
                    field.setAttribute('disabled', 'disabled');
                });
            }

            function markWriteButtons(form) {
                const method = (form.getAttribute('method') || 'get').toLowerCase();
                if (method === 'get') {
                    return;
                }

                if (isReadOnlyUser) {
                    form.dataset.writeDenied = '1';
                    disableFormFields(form);
                    return;
                }

                const role = form.dataset.writeRole || inferRoleFromAction(form.getAttribute('action'));
                if (!role || access[role]) {
                    return;
                }

                form.dataset.writeDenied = '1';

                const buttons = form.querySelectorAll('button[type="submit"], input[type="submit"]');
                buttons.forEach((btn) => {
                    btn.classList.add('write-disabled');
                    btn.setAttribute('aria-disabled', 'true');
                });

                disableFormFields(form);
            }

            document.querySelectorAll('form').forEach(markWriteButtons);

            function isNavbarElement(el) {
                return !!(el && el.closest && el.closest('.navbar, .navbar-vertical, .navbar-top, #navbarVerticalCollapse'));
            }

            function disableAllButtonsExceptBack() {
                const buttons = document.querySelectorAll('button');
                buttons.forEach((button) => {
                    if (isNavbarElement(button)) {
                        return;
                    }
                    if (shouldAllowReadonlyButton(button)) {
                        return;
                    }
                    button.classList.add('write-disabled');
                    button.setAttribute('aria-disabled', 'true');
                    button.setAttribute('data-readonly-disabled', 'true');
                });
            }

            if (isReadOnlyUser) {
                disableAllButtonsExceptBack();
            }

            document.addEventListener('submit', function (event) {
                const form = event.target;
                if (!form || form.tagName !== 'FORM') {
                    return;
                }

                if (form.dataset.writeDenied !== '1') {
                    return;
                }

                event.preventDefault();
                showWriteDeniedToast();
            });

            document.addEventListener('focusin', function (event) {
                const form = event.target ? event.target.closest('form') : null;
                if (!form || form.dataset.writeDenied !== '1') {
                    return;
                }
                showWriteDeniedToast();
            });

            document.addEventListener('click', function (event) {
                const target = event.target;
                if (isNavbarElement(target)) {
                    return;
                }
                const form = target ? target.closest('form') : null;
                if (form && form.dataset.writeDenied === '1' && target.matches('input, select, textarea, button')) {
                    if (shouldAllowReadonlyButton(target.closest('button'))) {
                        return;
                    }
                    showWriteDeniedToast();
                    return;
                }

                const button = target ? target.closest('button') : null;
                if (button && button.hasAttribute('data-readonly-disabled')) {
                    if (shouldAllowReadonlyButton(button)) {
                        return;
                    }
                    event.preventDefault();
                    event.stopPropagation();
                    showWriteDeniedToast();
                }
            });
        })();
    </script>

    <!-- Custom Theme Script -->
    <script>
        document.addEventListener('click', function (event) {
            const trigger = event.target.closest('.ghuranti');
            if (!trigger) {
                return;
            }

            document.querySelectorAll('.themeqx-demo-chooser-wrap').forEach(function (wrap) {
                wrap.classList.toggle('open');
            });
        });
    </script>

    <script>
        (function () {
            function applyTheme(theme) {
                const normalizedTheme = theme === 'dark' ? 'dark' : 'light';
                document.documentElement.setAttribute('data-bs-theme', normalizedTheme);
                try {
                    localStorage.setItem('phoenixTheme', normalizedTheme);
                } catch (error) {
                    // Ignore storage failures.
                }

                document.body.dispatchEvent(new CustomEvent('clickControl', {
                    detail: {
                        control: 'phoenixTheme',
                        value: normalizedTheme
                    }
                }));
            }

            function initThemeToggle() {
                const toggle = document.getElementById('themeControlToggle');
                if (!toggle || toggle.dataset.themeBound === '1') {
                    return;
                }

                let savedTheme = 'light';
                try {
                    savedTheme = localStorage.getItem('phoenixTheme') || 'light';
                } catch (error) {
                    savedTheme = 'light';
                }

                const initialTheme = savedTheme === 'dark'
                    ? 'dark'
                    : (document.documentElement.getAttribute('data-bs-theme') === 'dark' ? 'dark' : 'light');

                toggle.checked = initialTheme === 'dark';
                applyTheme(initialTheme);

                toggle.addEventListener('change', function () {
                    applyTheme(toggle.checked ? 'dark' : 'light');
                });

                toggle.dataset.themeBound = '1';
            }

            document.addEventListener('DOMContentLoaded', initThemeToggle);
        })();
    </script>

    <script>
    function toggleDropdown(element) {
        const parent = element.closest('.nav-item-wrapper');

        if (!parent) {
            return;
        }

        parent.classList.toggle('open');

        const isOpen = parent.classList.contains('open');
        const toggleBtn = parent.querySelector('.jm-submenu-toggle-btn');
        if (toggleBtn) {
            toggleBtn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        }
    }
    </script>

    <script>
        (function () {
            let pendingDeleteForm = null;

            function findDeleteMethodInput(form) {
                return form ? form.querySelector('input[name="_method"][value="DELETE"]') : null;
            }

            function getConfirmMessage(form, submitter) {
                if (submitter && submitter.dataset && submitter.dataset.confirmMessage) {
                    return submitter.dataset.confirmMessage;
                }
                if (form && form.dataset && form.dataset.confirmMessage) {
                    return form.dataset.confirmMessage;
                }
                return 'Are you sure you want to delete this record?';
            }

            function shouldBypass(form, submitter) {
                if (form && form.hasAttribute('data-confirm-bypass')) {
                    return true;
                }
                if (submitter && submitter.hasAttribute('data-confirm-bypass')) {
                    return true;
                }
                return false;
            }

            document.addEventListener('submit', function (event) {
                const form = event.target;
                if (!form || form.tagName !== 'FORM') {
                    return;
                }

                if (!findDeleteMethodInput(form)) {
                    return;
                }

                const submitter = event.submitter || document.activeElement;
                if (shouldBypass(form, submitter) || form.dataset.confirmed === 'true') {
                    return;
                }

                event.preventDefault();
                pendingDeleteForm = form;

                const message = getConfirmMessage(form, submitter);
                const body = document.getElementById('globalDeleteConfirmModalBody');
                if (body) {
                    body.textContent = message;
                }

                const modalEl = document.getElementById('globalDeleteConfirmModal');
                if (modalEl && window.bootstrap) {
                    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                    modal.show();
                }
            });

            const confirmButton = document.getElementById('globalDeleteConfirmModalConfirm');
            if (confirmButton) {
                confirmButton.addEventListener('click', function () {
                    if (!pendingDeleteForm) {
                        return;
                    }
                    pendingDeleteForm.dataset.confirmed = 'true';
                    pendingDeleteForm.submit();
                });
            }
        })();
    </script>

    <script>
        (function () {
            function renderFeatherIcons() {
                if (window.feather && typeof window.feather.replace === 'function') {
                    window.feather.replace();
                }
            }

            document.addEventListener('DOMContentLoaded', renderFeatherIcons);
        })();
    </script>

    <!-- Blade Yield Stacks -->
    @yield('page-js')
    @yield('scripts')
    @stack('scripts')
</body>
</html>
