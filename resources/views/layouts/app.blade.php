<!DOCTYPE html>
<html lang="en-US" dir="ltr" data-navigation-type="default" data-navbar-horizontal-shape="default">

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
    <link href="{{ asset('assets/metrics/vendors/simplebar/simplebar.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/metrics/assets/css/line.css') }}">
    <link href="{{ asset('assets/metrics/assets/css/theme-rtl.min.css') }}" type="text/css" rel="stylesheet" id="style-rtl">
    <link href="{{ asset('assets/metrics/assets/css/theme.min.css') }}" type="text/css" rel="stylesheet" id="style-default">
    <link href="{{ asset('assets/metrics/assets/css/user-rtl.min.css') }}" type="text/css" rel="stylesheet"
    id="user-style-rtl">
    <link href="{{ asset('assets/metrics/assets/css/user.min.css') }}" type="text/css" rel="stylesheet"
    id="user-style-default">
    <link href="{{ asset('assets/metrics/vendors/prism/prism-okaidia.css') }}" rel="stylesheet">
    <script src="https://cdn.skypack.dev/@hotwired/turbo@7.3.0"></script>

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

    @yield('page-css')

    <style>
        .modal {
            z-index: 1050 !important;
            /* Ensure the modal is above other elements */
        }

        .modal-backdrop {
            z-index: 1040 !important;
            /* Ensure the backdrop is below the modal */
        }

        a.btn:hover {
            transform: scale(1.1);
            transition: transform 0.2s ease-in-out;
        }

        button:hover {
            transform: scale(1.1);
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

<body
    data-user-is-subaccount="{{ auth()->check() && auth()->user()->isSubAccount() ? '1' : '0' }}"
    data-can-manage-boq="{{ auth()->check() && auth()->user()->can_manage_boq ? '1' : '0' }}"
    data-can-manage-materials="{{ auth()->check() && auth()->user()->can_manage_materials ? '1' : '0' }}"
    data-can-manage-labour="{{ auth()->check() && auth()->user()->can_manage_labour ? '1' : '0' }}"
>
    <!-- ===============================================-->
    <!--    Main Content-->
    <!-- ===============================================-->
    <main class="main" id="top">
        <nav class="navbar navbar-vertical navbar-expand-lg" style="display:none;" data-turbo-permanent>
            <div class="collapse navbar-collapse" id="navbarVerticalCollapse">
                <!-- scrollbar removed-->
                <div class="navbar-vertical-content">
                    <ul class="navbar-nav flex-column" id="navbarVerticalNav">
                        <div class="nav-item">
                            <!-- label-->
                            <p class="navbar-vertical-label">Apps</p>
                            <hr class="navbar-vertical-line" /><!-- parent pages-->

                            <style>
                                /* Navigation Item Styles */
                                .nav-item-wrapper {
                                    margin-bottom: 0.5rem;
                                    position: relative;
                                }

                                /* Dropdown menu */
                                .nav-item-wrapper .dropdown-menu {
                                    position: static !important;
                                    display: block;
                                    margin: 0;
                                    padding: 0 0 0 1.5rem;
                                    background: transparent;
                                    border: none;
                                    box-shadow: none;
                                    list-style: none;
                                    max-height: 0;
                                    overflow: hidden;
                                    opacity: 0;
                                    pointer-events: none;
                                    transition: max-height 0.3s ease, opacity 0.3s ease;
                                }

                                /* Smoothly expand submenu when open */
                                .nav-item-wrapper.open > .dropdown-menu {
                                    max-height: 600px;
                                    opacity: 1;
                                    pointer-events: auto;
                                }

                                /* Submenu items */
                                .nav-item-wrapper .dropdown-menu .dropdown-item {
                                    display: flex;
                                    align-items: center;
                                    padding: 0.75rem 1rem 0.75rem 2.5rem;
                                    color: #027333;
                                    text-decoration: none;
                                    font-size: 0.95rem;
                                    border-radius: 0.375rem;
                                    transition: background-color 0.3s ease, color 0.3s ease;
                                    background-color: transparent;
                                    position: relative;
                                }

                                /* Parent nav links that have a submenu */
                                .nav-item-wrapper.has-submenu > .nav-link {
                                    position: relative;
                                    padding-right: 2rem;
                                }

                                /* Chevron arrow for parent links */
                                .nav-item-wrapper.has-submenu > .nav-link::after {
                                    content: "▾";
                                    position: absolute;
                                    right: 1rem;
                                    font-size: 14px;
                                    color: #027333;
                                    transition: transform 0.3s ease, color 0.3s ease;
                                }

                                /* Rotate chevron when submenu is expanded */
                                .nav-item-wrapper.has-submenu.open > .nav-link::after {
                                    transform: rotate(180deg);
                                    color: #014d22;
                                }

                                /* Hover state for submenu items */
                                .nav-item-wrapper .dropdown-menu .dropdown-item:hover {
                                    background-color: #e0f2f1;
                                    color: #014d22;
                                }

                                .nav-item-wrapper .dropdown-menu .dropdown-item::before {
                                    content: '';
                                    position: absolute;
                                    top: 50%;
                                    left: 1.1rem;
                                    transform: translateY(-50%);
                                    width: 24px;
                                    height: 24px;
                                }

                                .nav-item-wrapper .dropdown-menu .dropdown-item.active {
                                    background-color: #e9ecef;
                                    font-weight: 500;
                                }

                                /* Main nav link */
                                .nav-link {
                                    display: flex;
                                    align-items: center;
                                    padding: 0.75rem 1rem;
                                    border-radius: 0.375rem;
                                    transition: background-color 0.3s ease, color 0.3s ease;
                                    color: #027333;
                                    text-decoration: none;
                                }

                                /* Hover state */
                                .nav-link:hover {
                                    background-color: #e0f2f1;
                                    color: #014d22;
                                }

                                /* Subtle scaling only on text/icon */
                                .nav-link:hover .nav-link-text,
                                .nav-link:hover .nav-link-icon {
                                    transform: scale(1.05);
                                    transition: transform 0.2s ease-in-out;
                                }

                                /* Icon styling */
                                .nav-link-icon i {
                                    font-size: 20px;
                                    color: #027333;
                                    margin-right: 0.5rem;
                                }

                                /* Text styling */
                                .nav-link-text {
                                    font-size: 16px;
                                    font-weight: 500;
                                    margin-left: 0.5rem;
                                    color: #027333;
                                }

                                .nav-item-wrapper .dropdown-menu .dropdown-item .nav-link-text {
                                    margin-left: 0;
                                    color: inherit;
                                    font-size: 15px;
                                }

                            </style>

                            <!-- Dashboard Item -->
                            <div class="nav-item-wrapper">
                                <a class="nav-link label-1" href="{{ route('dashboard') }}" role="button" aria-expanded="false">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-icon"><span data-feather="home"></span></span>
                                        <span class="nav-link-text-wrapper">
                                            <span class="nav-link-text">Dashboard</span>
                                        </span>
                                    </div>
                                </a>
                            </div>

                            @if(\Illuminate\Support\Facades\Auth::user()->is_client())
                                <!-- Bills of Quantities (BQ) -->
                                <div class="nav-item-wrapper">
                                    <a class="nav-link label-1" href="{{ route('boq') }}" role="button" aria-expanded="false">
                                        <div class="d-flex align-items-center">
                                            <span class="nav-link-icon">
                                                <span data-feather="file-text"></span>
                                            </span>
                                            <span class="nav-link-text-wrapper">
                                                <span class="nav-link-text">Bills of Quantities</span>
                                            </span>
                                        </div>
                                    </a>
                                </div>

                                <!-- Bills of Materials (BOM) -->
                                <div class="nav-item-wrapper">
                                    <a class="nav-link label-1" href="{{ route('boms.index') }}" role="button" aria-expanded="false">
                                        <div class="d-flex align-items-center">
                                            <span class="nav-link-icon">
                                                <span data-feather="file-text"></span>
                                            </span>
                                            <span class="nav-link-text-wrapper">
                                                <span class="nav-link-text">Bills of Materials</span>
                                            </span>
                                        </div>
                                    </a>
                                </div>

                              <!-- Material Management (Dropdown Parent) -->
                                <div class="nav-item-wrapper has-submenu">
                                    <a class="nav-link label-1" 
                                    href="javascript:void(0)" 
                                    onclick="toggleDropdown(this)">
                                        <div class="d-flex align-items-center">
                                            <span class="nav-link-icon">
                                                <span data-feather="box"></span>
                                            </span>
                                            <span class="nav-link-text-wrapper">
                                                <span class="nav-link-text">Manage Material</span>
                                            </span>
                                        </div>
                                    </a>

                                    <!-- Dropdown Menu -->
                                    <ul class="dropdown-menu" style="margin-right: 17px; padding-top: 1px;">
                                        <li>
                                            <a class="dropdown-item nav-link label-1" href="{{ route('requisitions.index') }}">
                                                <div class="d-flex align-items-center" style="margin-left: 10px;">
                                                    <span class="nav-link-text-wrapper">
                                                        <span class="nav-link-text">Requisitions</span>
                                                    </span>
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item nav-link label-1" href="{{ route('materials.delivered') }}">
                                                <div class="d-flex align-items-center" style="margin-left: 10px;">
                                                    <span class="nav-link-text-wrapper">
                                                        <span class="nav-link-text">Materials Delivered</span>
                                                    </span>
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item nav-link label-1" href="{{ route('materials.inventory') }}">
                                                <div class="d-flex align-items-center" style="margin-left: 10px;">
                                                    <span class="nav-link-text-wrapper">
                                                        <span class="nav-link-text">Inventory Management</span>
                                                    </span>
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item nav-link label-1" href="{{ route('materials.usage') }}">
                                                <div class="d-flex align-items-center" style="margin-left: 10px;">
                                                    <span class="nav-link-text-wrapper">
                                                        <span class="nav-link-text">Stock Usage</span>
                                                    </span>
                                                </div>
                                            </a>
                                        </li>
                                    </ul>
                                </div>

                                <!-- Labour -->
                                <div class="nav-item-wrapper">
                                    <a class="nav-link label-1" title="Manage your labour" href="{{ route('workers.index') }}" role="button" aria-expanded="false">
                                        <div class="d-flex align-items-center">
                                            <span class="nav-link-icon">
                                                <span data-feather="users"></span>
                                            </span>
                                            <span class="nav-link-text-wrapper">
                                                <span class="nav-link-text">Labour</span>
                                            </span>
                                        </div>
                                    </a>
                                </div>

                                <!-- Cost Tracking -->
                                <div class="nav-item-wrapper">
                                    <a class="nav-link label-1" title="track your costs" href="{{ route('cost-tracking.index') }}" role="button" aria-expanded="false">
                                        <div class="d-flex align-items-center">
                                            <span class="nav-link-icon">
                                                <span data-feather="trending-up"></span>
                                            </span>
                                            <span class="nav-link-text-wrapper">
                                                <span class="nav-link-text">Cost Tracking</span>
                                            </span>
                                        </div>
                                    </a>        
                                </div>

                                <!-- Reporting -->
                                <div class="nav-item-wrapper">
                                    <a class="nav-link label-1" title="Daily logs/reports" href="{{ route('reports') }}" role="button" aria-expanded="false">
                                        <div class="d-flex align-items-center">
                                            <span class="nav-link-icon">
                                                <span data-feather="file"></span>
                                            </span> 
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
                </div>
            </div>
            <div class="navbar-vertical-footer px-3 py-2">
                <button class="navbar-vertical-toggle nav-link w-100 d-flex align-items-center px-3 py-2 text-start border-0 bg-transparent" type="button" role="button">
                    <span class="navbar-vertical-toggle-icon navbar-vertical-toggle-icon--expanded me-2 fs-4 d-none" aria-hidden="true">&lsaquo;</span>
                    <span class="navbar-vertical-toggle-icon navbar-vertical-toggle-icon--collapsed fs-4 me-2 d-none" aria-hidden="true">&rsaquo;</span>
                </button>
            </div>
        </nav>
        <nav class="navbar navbar-top fixed-top navbar-expand" id="navbarDefault" style="display:none;" data-turbo-permanent>
            <div class="collapse navbar-collapse justify-content-between">
                <div class="navbar-logo">
                    <button class="btn navbar-toggler navbar-toggler-humburger-icon hover-bg-transparent" type="button"
                        data-bs-toggle="collapse" data-bs-target="#navbarVerticalCollapse"
                        aria-controls="navbarVerticalCollapse" aria-expanded="false"
                        aria-label="Toggle Navigation">
                        <span class="navbar-toggle-icon">
                            <span class="toggle-line"></span>
                        </span>
                    </button>
                    <a class="navbar-brand me-1 me-sm-3" href="{{ url('/') }}">
                        <div class="d-flex align-items-center">
                            <div class="d-flex align-items-center"><img src="{{ favicon_url() }}" alt="b2b"
                                width="27" />
                                <h5 class="logo-text ms-2 d-none d-sm-block" style="color: #027333;">JengaMetrics</h5>
                            </div>
                        </div>
                    </a>
                </div>
                <ul class="navbar-nav navbar-nav-icons flex-row">
                    <li class="nav-item">
                        <div class="theme-control-toggle fa-icon-wait px-2">
                            <input class="form-check-input ms-0 theme-control-toggle-input" type="checkbox" data-theme-control="phoenixTheme" value="dark" id="themeControlToggle" />
                            <label class="mb-0 theme-control-toggle-label theme-control-toggle-light" for="themeControlToggle" data-bs-toggle="tooltip" data-bs-placement="left"
                                data-bs-title="Switch theme" style="height:32px;width:32px;">
                                <span class="icon" data-feather="moon"></span>
                            </label>
                            <label class="mb-0 theme-control-toggle-label theme-control-toggle-dark" for="themeControlToggle" data-bs-toggle="tooltip" data-bs-placement="left"
                                data-bs-title="Switch theme" style="height:32px;width:32px;">
                                <span class="icon" data-feather="sun"></span>
                            </label>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link lh-1 pe-2" id="navbarDropdownUser" href="#!" role="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true"
                            aria-expanded="false">
                            <div class="d-flex align-items-center">
                                <span class="fs-8">{{ project() }}</span>
                                <span class="ms-2 fs-8"><span data-feather="menu"></span></span>
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
                                            @if(\Illuminate\Support\Facades\Auth::user()->project_id)
                                                @if(!\Illuminate\Support\Facades\Auth::user()->isSubAccount())
                                                    <li class="nav-item">
                                                        <a class="nav-link px-3 d-block" href="/admin/settings">
                                                            <span class="me-2 text-body align-bottom" data-feather="settings"></span>
                                                            Project settings
                                                        </a>
                                                    </li>
                                                @endif
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
                                            <a class="dropdown-item" href="{{ url('account') }}">
                                                <i class="fa fa-user"></i> 
                                                <span key="t-profile">Profile</span>
                                            </a>
                                            @if(!\Illuminate\Support\Facades\Auth::user()->isSubAccount())
                                                <a class="dropdown-item" href="{{ route('sub_accounts.index') }}">
                                                    <i class="fa fa-users"></i>
                                                    <span key="t-profile">Add Users</span>
                                                </a>
                                            @endif
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
                                                </a>
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
                                                    style="display:inline;">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $project->id }}">
                                                    <button type="submit" class="btn btn-success btn-sm">Select</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @if (!auth()->user()->isSubAccount())
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
                <div class="content">
                    @yield('content')
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
            const toggleBtn = document.querySelector('.navbar-vertical-toggle');

            if (!htmlEl || !toggleBtn) {
                return;
            }

            const expandedIcon = toggleBtn.querySelector('.navbar-vertical-toggle-icon--expanded');
            const collapsedIcon = toggleBtn.querySelector('.navbar-vertical-toggle-icon--collapsed');
            const text = toggleBtn.querySelector('.navbar-vertical-footer-text');
            const chevron = toggleBtn.querySelector('.navbar-vertical-toggle-chevron');
            const navKey = 'navbarVerticalCollapsed';
            const dropdownKey = 'navbarVerticalOpenIds';

            function syncToggleButton() {
                const isCollapsed = htmlEl.classList.contains('navbar-vertical-collapsed');

                if (expandedIcon) {
                    expandedIcon.classList.toggle('d-none', isCollapsed);
                }

                if (collapsedIcon) {
                    collapsedIcon.classList.toggle('d-none', !isCollapsed);
                }

                if (text) {
                    const expandedText = text.getAttribute('data-expanded-text') || text.textContent || 'Collapse Menu';
                    const collapsedText = text.getAttribute('data-collapsed-text') || 'Expand Menu';
                    text.textContent = isCollapsed ? collapsedText : expandedText;
                    text.classList.toggle('d-none', isCollapsed);
                }

                if (chevron) {
                    chevron.classList.toggle('d-none', isCollapsed);
                }

                toggleBtn.setAttribute('aria-label', isCollapsed ? 'Expand navigation menu' : 'Collapse navigation menu');
                toggleBtn.setAttribute('aria-expanded', (!isCollapsed).toString());

                localStorage.setItem(navKey, isCollapsed ? '1' : '0');
            }

            syncToggleButton();

            const observer = new MutationObserver(syncToggleButton);
            observer.observe(htmlEl, { attributes: true, attributeFilter: ['class'] });

            toggleBtn.addEventListener('click', function () {
                requestAnimationFrame(syncToggleButton);
            });

            // Restore persisted nav collapsed state
            const stored = localStorage.getItem(navKey);
            if (stored !== null && stored === '1') {
                htmlEl.classList.add('navbar-vertical-collapsed');
                requestAnimationFrame(syncToggleButton);
            }

            // Persist dropdown open state
            const navContainer = document.querySelector('#navbarVerticalCollapse');
            if (navContainer) {
                const persistDropdowns = () => {
                    const openIds = Array.from(navContainer.querySelectorAll('.collapse.show'))
                        .map(el => el.id)
                        .filter(Boolean);
                    localStorage.setItem(dropdownKey, JSON.stringify(openIds));
                };

                navContainer.addEventListener('shown.bs.collapse', persistDropdowns);
                navContainer.addEventListener('hidden.bs.collapse', persistDropdowns);

                const storedDropdowns = localStorage.getItem(dropdownKey);
                if (storedDropdowns) {
                    try {
                        const ids = JSON.parse(storedDropdowns);
                        ids.forEach(id => {
                            const el = document.getElementById(id);
                            if (el && !el.classList.contains('show')) {
                                const collapseInstance = bootstrap.Collapse.getOrCreateInstance(el, { toggle: false });
                                collapseInstance.show();
                            }
                        });
                    } catch (_) {}
                }
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
    function toggleDropdown(element) {
        const parent = element.closest('.nav-item-wrapper');

        if (!parent) {
            return;
        }

        parent.classList.toggle('open');
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

    <!-- Blade Yield Stacks -->
    @yield('page-js')
    @yield('scripts')
    @stack('scripts')
</body>
</html>
