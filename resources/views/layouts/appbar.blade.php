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



    <!-- ===============================================-->
    <!--    Additional Scripts and Styles-->
    <!-- ===============================================-->
    <script>
        var phoenixIsRTL = window.config.config.phoenixIsRTL;
        if (phoenixIsRTL) {
            var linkDefault = document.getElementById('style-default');
            var userLinkDefault = document.getElementById('user-style-default');
            linkDefault.setAttribute('disabled', true);
            userLinkDefault.setAttribute('disabled', true);
            document.querySelector('html').setAttribute('dir', 'rtl');
        } else {
            var linkRTL = document.getElementById('style-rtl');
            var userLinkRTL = document.getElementById('user-style-rtl');
            linkRTL.setAttribute('disabled', true);
            userLinkRTL.setAttribute('disabled', true);
        }
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
    </style>
</head>


<body>
    <!-- ===============================================-->
    <!--    Main Content-->
    <!-- ===============================================-->
    <main class="main" id="top">
        <nav class="navbar navbar-vertical navbar-expand-lg" style="display:none;">
            <div class="collapse navbar-collapse" id="navbarVerticalCollapse">
                <!-- scrollbar removed-->
                <div class="navbar-vertical-content">
                    <ul class="navbar-nav flex-column" id="navbarVerticalNav">

                        <li class="nav-item">
                            <!-- label-->
                            <p class="navbar-vertical-label">Apps</p>
                            <hr class="navbar-vertical-line" /><!-- parent pages-->


                            <style>
                                /* Navigation Item Styles */
                                .nav-item-wrapper {
                                    margin-bottom: 1rem;
                                    /* Add spacing between navigation items */
                                }

                                .nav-link {
                                    padding: 0.75rem 1rem;
                                    /* Add padding for better touch target size */
                                    border-radius: 0.375rem;
                                    /* Add rounded corners */
                                    transition: background-color 0.3s ease, color 0.3s ease;
                                    /* Smooth transition effects */
                                    color: #000000;
                                    /* Default text color in green */
                                    text-decoration: none;
                                    /* Remove underline from links */
                                }

                                .nav-link:hover {
                                    background-color: #e0f2f1;
                                    /* Light green background on hover */
                                    color: #000000;
                                    /* Darker green color on hover */
                                }

                                .nav-link-icon i {
                                    font-size: 20px;
                                    /* Adjust the font size for a balanced size */
                                    color: #000000;
                                    /* Green icon color */
                                    margin-right: 0.5rem;
                                    /* Add spacing between icon and text */
                                }

                                .nav-link-text {
                                    font-size: 16px;
                                    /* Set a professional font size */
                                    font-weight: 500;
                                    /* Slightly bolder text */
                                    margin-left: 0.5rem;
                                    /* Add spacing between icon and text */
                                    color: #027333;
                                    /* Ensure text color matches the nav-link color */
                                }
                            </style>


                            <!-- Dashboard Item -->
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



                            @if(Auth::user()->is_client())

                          
<!-- Bills of Quantities (BQ) -->
 
<div class="nav-item-wrapper">
    <a class="nav-link label-1" href="{{ route('bq_documents') }}" role="button" aria-expanded="false">
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
                <span data-feather="settings"></span>
            </span>
            <span class="nav-link-text-wrapper">
                <span class="nav-link-text">Bills of Materials</span>
            </span>
        </div>
    </a>
</div>

<!-- Labour -->
<div class="nav-item-wrapper">
    <div class="nav-link label-1" role="button" aria-expanded="false">
        <div class="d-flex align-items-center">
            <span class="nav-link-icon">
                <span data-feather="users"></span>
            </span>
            <a href="{{ url(route('workers.index')) }}" title="Manage existing labour">
                <span class="nav-link-text-wrapper">
                    <span class="nav-link-text">Labour</span>
                </span>
            </a>
            <span class="menu-arrow"></span>
        </div>
    </div>
    <!-- <div class="menu-sub menu-sub-accordion"> -->
        <!-- Manage Labour -->
        <!-- <div class="nav-item-wrapper">
            <a class="nav-link label-1" href="{{ url(route('workers.index')) }}" title="Manage existing labour resources">
                <div class="d-flex align-items-center">
                    <span class="nav-link-text-wrapper">
                        <span class="nav-link-text">Manage Labour</span>
                    </span>
                </div>
            </a>
        </div> -->
        <!-- Labour Costing -->
        <!-- <div class="nav-item-wrapper">
            <a class="nav-link label-1" href="labour-costing.html" title="Estimate and track labour costs">
                <div class="d-flex align-items-center">
                    <span class="nav-link-text-wrapper">
                        <span class="nav-link-text">Labour Costing</span>
                    </span>
                </div>
            </a>
        </div> -->
        <!-- Labour Reports -->
        <!-- <div class="nav-item-wrapper">
            <a class="nav-link label-1" href="labour-reports.html" title="Generate and view labour reports">
                <div class="d-flex align-items-center">
                    <span class="nav-link-text-wrapper">
                        <span class="nav-link-text">Labour Reports</span>
                    </span>
                </div>
            </a>
        </div>
    </div> -->
</div>

<!-- Cost Tracking -->
<div class="nav-item-wrapper">
    <div class="nav-link label-1" role="button" aria-expanded="false">
        <div class="d-flex align-items-center">
            <span class="nav-link-icon">
                <span data-feather="trending-up"></span>
            </span>
            <a href="{{ route('cost-tracking.index') }}" title="Cost-tracking">
                <span class="nav-link-text-wrapper">
                    <span class="nav-link-text">Cost Tracking</span>
                </span>
            </a>
            <span class="menu-arrow"></span>
        </div>
    </div>
    <!-- <div class="menu-sub menu-sub-accordion">
        Real-Time Tracking -->
        <!-- <div class="nav-item-wrapper">
            <a class="nav-link label-1" href="real-time-cost.html" title="Real-time cost tracking">
                <div class="d-flex align-items-center">
                    <span class="nav-link-text-wrapper">
                        <span class="nav-link-text">Real-Time Tracking</span>
                    </span>
                </div>
            </a>
        </div> -->
        <!-- Cost Comparison -->
        <!-- <div class="nav-item-wrapper">
            <a class="nav-link label-1" href="input-output-comparison.html" title="Input vs. output cost comparison">
                <div class="d-flex align-items-center">
                    <span class="nav-link-text-wrapper">
                        <span class="nav-link-text">Cost Comparison</span>
                    </span>
                </div>
            </a>
        </div> -->
        <!-- Budget Tracking -->
        <!-- <div class="nav-item-wrapper">
            <a class="nav-link label-1" href="budget-tracking.html" title="Budget tracking for each project stage">
                <div class="d-flex align-items-center">
                    <span class="nav-link-text-wrapper">
                        <span class="nav-link-text">Budget Tracking</span>
                    </span>
                </div>
            </a>
        </div>
    </div> -->
</div>

<!-- Material Management -->
<div class="nav-item-wrapper">
    <div class="nav-link label-1" role="button" aria-expanded="false">
        <div class="d-flex align-items-center">
            <span class="nav-link-icon">
                <span data-feather="box"></span>
            </span>
            <a href="{{ url(route('materials.index')) }}" title="Material Management">
                <!-- <div class="d-flex align-items-center">   -->
                    <span class="nav-link-text-wrapper">
                        <span class="nav-link-text">Material Management</span>
                    </span>
                <!-- </div> -->
            </a>
            <span class="menu-arrow"></span>
        </div>
    </div>
    <!-- <div class="menu-sub menu-sub-accordion"> -->
        <!-- Materials Purchased -->
        <!-- <div class="nav-item-wrapper">
            <a class="nav-link label-1" href="{{ url(route('materials.index')) }}" title="Materials Purchased">
                <div class="d-flex align-items-center">
                    <span class="nav-link-text-wrapper">
                        <span class="nav-link-text">Materials Purchased</span>
                    </span>
                </div>
            </a>
        </div> -->
        <!-- List of Suppliers -->
        <!-- <div class="nav-item-wrapper">
            <a class="nav-link label-1" href="{{ url(route('suppliers.index')) }}" title="Procurement and supply chain management">
                <div class="d-flex align-items-center">
                    <span class="nav-link-text-wrapper">
                        <span class="nav-link-text">List of Suppliers</span>
                    </span>
                </div>
            </a>
        </div>
    </div> -->
</div>

<!-- Reporting -->
<div class="nav-item-wrapper">
    <div class="nav-link label-1" role="button" aria-expanded="false">
        <div class="d-flex align-items-center">
            <span class="nav-link-icon">
                <span data-feather="file"></span>
            </span>
            <a href="#" title="Daily logs/reports">
                <span class="nav-link-text-wrapper">
                    <span class="nav-link-text">Reporting</span>
                </span>
            </a>
            <span class="menu-arrow"></span>
        </div>
    </div>
    <!-- <div class="menu-sub menu-sub-accordion"> -->
        <!-- Daily Logs -->
        <!-- <div class="nav-item-wrapper">
            <a class="nav-link label-1" href="daily-logs.html" title="Daily logs/reports">
                <div class="d-flex align-items-center">
                    <span class="nav-link-text-wrapper">
                        <span class="nav-link-text">Daily Logs</span>
                    </span>
                </div>
            </a>
        </div> -->
        <!-- Weekly Reports -->
        <!-- <div class="nav-item-wrapper">
            <a class="nav-link label-1" href="weekly-reports.html" title="Weekly performance reports">
                <div class="d-flex align-items-center">
                    <span class="nav-link-text-wrapper">
                        <span class="nav-link-text">Weekly Reports</span>
                    </span>
                </div>
            </a>
        </div> -->
        <!-- Cost vs. Work -->
        <!-- <div class="nav-item-wrapper">
            <a class="nav-link label-1" href="cost-vs-work.html" title="Graphs and charts for input costs vs. work done">
                <div class="d-flex align-items-center">
                    <span class="nav-link-text-wrapper">
                        <span class="nav-link-text">Cost vs. Work</span>
                    </span>
                </div>
            </a>
        </div>
    </div>
</div> -->

<!-- Communication -->
<div class="nav-item-wrapper">
    <div class="nav-link label-1" role="button" aria-expanded="false">
        <div class="d-flex align-items-center">
            <span class="nav-link-icon">
                <span data-feather="message-circle"></span>
            </span>
            <a href="#" title="Document sharing">
                <span class="nav-link-text-wrapper">
                    <span class="nav-link-text">Communication</span>
                </span>
            </a>
            <span class="menu-arrow"></span>
        </div>
    </div>
    <!-- <div class="menu-sub menu-sub-accordion"> -->
        <!-- Document Sharing -->
        <!-- <div class="nav-item-wrapper">
            <a class="nav-link label-1" href="document-sharing.html" title="Document sharing">
                <div class="d-flex align-items-center">
                    <span class="nav-link-text-wrapper">
                        <span class="nav-link-text">Document Sharing</span>
                    </span>
                </div>
            </a>
        </div> -->
        <!-- Meeting Scheduler -->
        <!-- <div class="nav-item-wrapper">
            <a class="nav-link label-1" href="meeting-scheduler.html" title="Scheduling meetings">
                <div class="d-flex align-items-center">
                    <span class="nav-link-text-wrapper">
                        <span class="nav-link-text">Meeting Scheduler</span>
                    </span>
                </div>
            </a>
        </div>
    </div> -->
</div>


                            @endif



                            @if(Auth::user()->is_admin())

                                                            <!-- Sales & Payment Section -->
                                                            <div class="nav-item-wrapper">
                                                                <a class="nav-link dropdown-indicator label-1" href="#sidebarSalesPayment" role="button"
                                                                    data-bs-toggle="collapse" aria-expanded="false" aria-controls="sidebarSalesPayment">
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

                                                                        <li class="nav-item"><a
                                                                                class="nav-link {{ request()->is('admin/listofusers') ? 'active' : '' }}"
                                                                                href="{{ url('listofadmins') }}">
                                                                                <div class="d-flex align-items-center"><span
                                                                                        class="nav-link-text">Merchants</span></div>
                                                                            </a>
                                                                        </li>

                                                                        <li class="nav-item"><a
                                                                                class="nav-link {{ request()->is('transactions') ? 'active' : '' }}"
                                                                                href="/transactions">
                                                                                <div class="d-flex align-items-center"><span
                                                                                        class="nav-link-text">Payments</span></div>
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
                                                                <a class="nav-link dropdown-indicator label-1" href="#settings" role="button"
                                                                    data-bs-toggle="collapse" aria-expanded="false" aria-controls="settings">
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
                                                                        <li class="nav-item"><a class="nav-link"
                                                                                href="{{ url('website_info')}}">
                                                                                <div class="d-flex align-items-center"><span
                                                                                        class="nav-link-text">Website setting</span></div>
                                                                            </a>
                                                                        </li>
                                                                        
                                                                        <li class="nav-item">
                                                                            <a class="nav-link {{ request()->is('sections') ? 'active' : '' }}" href="{{ route('sections.index') }}">
                                                                                <div class="d-flex align-items-center">
                                                                                    <span class="nav-link-text">Sections</span>
                                                                                </div>
                                                                            </a>
                                                                        </li>


                                                                        <li class="nav-item"><a class="nav-link" href="#">
                                                                                <div class="d-flex align-items-center"><span
                                                                                        class="nav-link-text">Elements</span></div>
                                                                            </a></li>


                                                                        <li class="nav-item"><a class="nav-link" href="#">
                                                                                <div class="d-flex align-items-center"><span
                                                                                        class="nav-link-text">Sub Elements</span></div>
                                                                            </a></li>

                                                                    </ul>
                                                                </div>
                                                            </div>



                            @endif









                        </li>



                    </ul>
                </div>
            </div>
            <div class="navbar-vertical-footer"><button
                    class="btn navbar-vertical-toggle border-0 fw-semibold w-100 white-space-nowrap d-flex align-items-center"><span
                        class="uil uil-left-arrow-to-left fs-8"></span><span
                        class="uil uil-arrow-from-right fs-8"></span><span
                        class="navbar-vertical-footer-text ms-2">Collapsed View</span></button></div>
        </nav>

        <nav class="navbar navbar-top fixed-top navbar-expand" id="navbarDefault" style="display:none;">
            <div class="collapse navbar-collapse justify-content-between">
                <div class="navbar-logo">
                    <button class="btn navbar-toggler navbar-toggler-humburger-icon hover-bg-transparent" type="button"
                        data-bs-toggle="collapse" data-bs-target="#navbarVerticalCollapse"
                        aria-controls="navbarVerticalCollapse" aria-expanded="false"
                        aria-label="Toggle Navigation"><span class="navbar-toggle-icon"><span
                                class="toggle-line"></span></span></button>
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
                        <div class="theme-control-toggle fa-icon-wait px-2"><input
                                class="form-check-input ms-0 theme-control-toggle-input" type="checkbox"
                                data-theme-control="phoenixTheme" value="dark" id="themeControlToggle" /><label
                                class="mb-0 theme-control-toggle-label theme-control-toggle-light"
                                for="themeControlToggle" data-bs-toggle="tooltip" data-bs-placement="left"
                                data-bs-title="Switch theme" style="height:32px;width:32px;"><span class="icon"
                                    data-feather="moon"></span></label><label
                                class="mb-0 theme-control-toggle-label theme-control-toggle-dark"
                                for="themeControlToggle" data-bs-toggle="tooltip" data-bs-placement="left"
                                data-bs-title="Switch theme" style="height:32px;width:32px;"><span class="icon"
                                    data-feather="sun"></span></label></div>
                    </li>



                    <li class="nav-item dropdown">

                        <a class="nav-link lh-1 pe-0" id="navbarDropdownUser" href="#!" role="button"
                            data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true"
                            aria-expanded="false">
                            <span class="fs-8">{{ project() }}</span>
                            <i class="fas fa-angle-down"></i>
                        </a>


                        <div class="dropdown-menu dropdown-menu-end navbar-dropdown-caret py-0 dropdown-profile shadow border"
                            aria-labelledby="navbarDropdownUser">
                            <div class="card position-relative border-0">
                                <div class="card-body p-0">
                                    <div class="text-center pt-4 pb-3">
                                        <div class="avatar avatar-xl ">
                                            <img class="rounded-circle " src="{{ Auth::user()->get_gravatar(150) }}"
                                                alt="" />
                                        </div>
                                        <h6 class="mt-2 text-body-emphasis">{{ project() }}</h6>
                                    </div>

                                </div>
                                <div>

                                    @if(Auth::user()->is_client())
                                        @if(Auth::user()->package)
                                            @if(package(Auth::user()->package)->name == "Basic")

                                                <div class="help-box text-center">

                                                    <p class="mb-3 mt-2 text-muted">
                                                        <strong>{{ package(Auth::user()->package)->name }}</strong><br>
                                                        Upgrade your plan and get the most out of fedhatrac
                                                    </p>
                                                    <div class="mt-3">
                                                        <a href="{{ route('subscribe') }}" class="btn btn-success"> Upgrade now</a>
                                                    </div>
                                                </div>
                                            @endif
                                        @endif
                                    @endif



                                    <ul class="nav d-flex flex-column mb-2 pb-1">
                                        @if(Auth::user()->is_client())
                                            @if(Auth::user()->project_id)
                                                <li class="nav-item"><a class="nav-link px-3 d-block"
                                                        href="/admin/settings"><span class="me-2 text-body align-bottom"
                                                            data-feather="pie-chart"></span>Project settings</a></li>
                                                <li class="nav-item">


                                                    <a class="nav-link px-3 d-block" href="#" data-bs-toggle="modal"
                                                        data-bs-target="#switchbusinesses">
                                                        <span class="me-2 text-body align-bottom" data-feather="pie-chart">

                                                        </span>Switch Projects
                                                    </a>







                                                </li>
                                            @endif 

                                        @endif
                                    </ul>
                                </div>
                                <div class="card-footer p-0 border-top border-translucent">
                                    <ul class="nav d-flex flex-column my-3">





                                  
                                        <!-- item--> @if(Auth::user()->is_client())
                                            <a class="dropdown-item" href="{{ url('account') }}"><i
                                                    class="fa fa-user"></i> <span key="t-profile">Profile</span></a>
                                            <a class="dropdown-item" href="{{ url('businesses') }}"><i
                                                    class="fa fa-users"></i> <span key="t-profile">Manage your
                                                    projects</span></a>
                                            <a class="dropdown-item" href="{{ url('billings') }}"><i
                                                    class="fa fa-users"></i> <span key="t-profile">Manage your
                                                    billings</span></a>
                                            <a class="dropdown-item" href="{{ url('subscribe') }}"><i
                                                    class="fa fa-users"></i> <span key="t-profile">Manage your
                                                    subscriptions</span></a>
                                        @endif


                                    </ul>
                                    <hr />
                                    <div class="px-3"> 


   <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')" class="btn btn-phoenix-secondary d-flex flex-center w-100"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                 <span class="me-2" data-feather="log-out">
                                            </span>Sign out</a>
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
                        <h5 class="modal-title">Switch businesses</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <?php
$businesses = \App\Models\Project::whereUserId(Auth::user()->id)->get();
?>
                    <div class="modal-body">


                        <table class="table table-striped">

                            <tbody>
                                @foreach ($businesses as $business)
                                    <tr>
                                        <td>{{ $business->name }}</td>
                                        <td>

                                            <form action="{{ route('select_project') }}" method="POST"
                                                style="display:inline;">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $business->id }}">
                                                <button type="submit" class="btn btn-success btn-sm">Select</button>
                                            </form>
                                        </td>
                                    </tr>

                                @endforeach


                                <tr>
                                    <td>
                                        <a class="dropdown-item" href="{{ url('businesses') }}"><i
                                                class="fa fa-users"></i> <span key="t-profile">Manage your
                                                projects</span></a>
                                        <br>
                                        <a class="dropdown-item" href="{{ route('wizard.step1') }}"><i
                                                class="fa fa-users"></i> <span key="t-profile">Create new
                                                project</span></a>

                                    </td>
                                </tr>
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


   
   </main><!-- ===============================================-->
    <!--    End of Main Content-->
    <!-- ===============================================-->


    <!-- ===============================================-->
    <!--    JavaScripts-->
    <!-- ===============================================-->
    <script src="{{ asset('assets/metrics/vendors/popper/popper.min.js') }}"></script>
    <script src="{{ asset('assets/metrics/vendors/bootstrap/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/metrics/vendors/anchorjs/anchor.min.js') }}"></script>
    <script src="{{ asset('assets/metrics/vendors/is/is.min.js') }}"></script>
    <script src="{{ asset('assets/metrics/vendors/fontawesome/all.min.js') }}"></script>


    <script src="{{ asset('assets/metrics/vendors/lodash/lodash.min.js') }}"></script>
    <script src="{{ asset('assets/metrics/vendors/list.js/list.min.js') }}"></script>
    <script src="{{ asset('assets/metrics/vendors/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('assets/metrics/vendors/dayjs/dayjs.min.js') }}"></script>
    <script src="{{ asset('assets/metrics/vendors/leaflet/leaflet.js') }}"></script>
    <script src="{{ asset('assets/metrics/vendors/leaflet.markercluster/leaflet.markercluster.js') }}"></script>
    <script src="{{ asset('assets/metrics/vendors/leaflet.tilelayer.colorfilter/leaflet-tilelayer-colorfilter.min.js') }}"></script>
    <script src="{{ asset('assets/metrics/assets/js/phoenix.js') }}"></script>
    <script src="{{ asset('assets/metrics/vendors/echarts/echarts.min.js') }}"></script>
    <script src="{{ asset('assets/metrics/assets/js/ecommerce-dashboard.js') }}"></script>
    <script src="{{ asset('assets/metrics/vendors/prism/prism.js') }}"></script>

    <script>
    var toastr_options = {closeButton : true};
</script>
    @yield('page-js')



<script>
    $(document).on('click', '.ghuranti', function(){
        $('.themeqx-demo-chooser-wrap').toggleClass('open');
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!--JQuery CDN-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha384-KyZXEAg3QhqLMpG8r+Knujsl5/5hb5g6Q2bj0Ib6W9crrxJKKo4qklqUhcHZCl1r" crossorigin="anonymous"></script>
@stack('scripts')
</body>
</html>