<!DOCTYPE html>
<!--
Author: Keenthemes
Product Name: SaulProduct Version: 1.0.4
Purchase: https://keenthemes.com/products/saul-html-pro
Website: http://www.keenthemes.com
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Dribbble: www.dribbble.com/keenthemes
Like: www.facebook.com/keenthemes
License: For each use you must have a valid license purchased only from above link in order to legally use the theme for your project.
-->
<html lang="en">
<!--begin::Head-->

<head>
    <title>Saul Theme by KeenThemes</title>
    <meta charset="utf-8">
    <meta name="description" content="Saul HTML Pro - Bootstrap 5 HTML Multipurpose Admin Dashboard Theme">
    <meta name="keywords"
        content="Saul, bootstrap, bootstrap 5, dmin themes, free admin themes, bootstrap admin, bootstrap dashboard">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta property="og:locale" content="en_US">
    <meta property="og:type" content="article">
    <meta property="og:title" content="Saul Theme by KeenThemes">
    <meta property="og:url" content="https://keenthemes.com/products/saul-html-pro">
    <meta property="og:site_name" content="Saul HTML Pro by Keenthemes">
    <link rel="canonical" href="https://preview.keenthemes.com#">
    <link rel="shortcut icon" href="{{ asset('assets') }}/media/logos/favicon.ico">

    <!--begin::Fonts(mandatory for all pages)-->
    <link rel="stylesheet" href="../css?family=Inter:300,400,500,600,700"> <!--end::Fonts-->

    <!--begin::Vendor Stylesheets(used for this page only)-->
    <link href="{{ asset('assets') }}/plugins/custom/fullcalendar/fullcalendar.bundle.css" rel="stylesheet"
        type="text/css">
    <link href="{{ asset('assets') }}/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css">
    <!--end::Vendor Stylesheets-->


    <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
    <link href="{{ asset('assets') }}/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets') }}/css/style.bundle.css" rel="stylesheet" type="text/css">
    <!--end::Global Stylesheets Bundle-->

    <!--begin::Google tag-->
    <script async="" src="../gtag/js?id=UA-37564768-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag() { dataLayer.push(arguments); }
        gtag('js', new Date());
        gtag('config', 'UA-37564768-1');
    </script>
    <!--end::Google tag-->
    <script>
        // Frame-busting to prevent site from being loaded within a frame without permission (click-jacking)
        if (window.top != window.self) {
            window.top.location.replace(window.self.location.href);
        }
    </script>

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Include jQuery UI -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
</head>
<!--end::Head-->

<!--begin::Body-->

<body id="kt_app_body" data-kt-app-header-fixed="true" data-kt-app-header-fixed-mobile="true"
    data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true"
    data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true"
    data-kt-app-aside-enabled="true" data-kt-app-aside-fixed="true" data-kt-app-aside-push-toolbar="true"
    data-kt-app-aside-push-footer="true" class="app-default">
    <!--begin::Theme mode setup on page load-->
    <script>
        var defaultThemeMode = "light";
        var themeMode;

        if (document.documentElement) {
            if (document.documentElement.hasAttribute("data-bs-theme-mode")) {
                themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
            } else {
                if (localStorage.getItem("data-bs-theme") !== null) {
                    themeMode = localStorage.getItem("data-bs-theme");
                } else {
                    themeMode = defaultThemeMode;
                }
            }

            if (themeMode === "system") {
                themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
            }

            document.documentElement.setAttribute("data-bs-theme", themeMode);
        }            
    </script>
    <!--end::Theme mode setup on page load-->
    <!--Begin::Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5FS8GGP" height="0" width="0"
            style="display:none;visibility:hidden"></iframe></noscript>
    <!--End::Google Tag Manager (noscript) -->


    <!--begin::App-->
    <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
        <!--begin::Page-->
        <div class="app-page  flex-column flex-column-fluid " id="kt_app_page">


            <!--begin::Header-->
            <div id="kt_app_header" class="app-header  d-flex flex-column flex-stack ">

                <!--begin::Header main-->
                <div class="d-flex align-items-center flex-stack flex-grow-1">

                    <div class="app-header-logo d-flex align-items-center flex-stack px-lg-10 mb-2"
                        id="kt_app_header_logo">
                        <!--begin::Sidebar mobile toggle-->
                        <div class="btn btn-icon btn-active-color-primary w-35px h-35px ms-3 me-2 d-flex d-lg-none"
                            id="kt_app_sidebar_mobile_toggle">
                            <i class="ki-duotone ki-abstract-14 fs-2"><span class="path1"></span><span
                                    class="path2"></span></i>
                        </div>
                        <!--end::Sidebar mobile toggle-->

                        <!--begin::Logo-->
                        <a href="index.html" class="app-sidebar-logo">
                            <img alt="Logo" src="{{ asset('assets') }}/media/logos/default.svg"
                                class="h-30px theme-light-show">
                            <img alt="Logo" src="{{ asset('assets') }}/media/logos/default-dark.svg"
                                class="h-30px theme-dark-show">
                        </a>
                        <!--end::Logo-->

                        <!--begin::Sidebar toggle-->
                        <div id="kt_app_sidebar_toggle"
                            class="app-sidebar-toggle btn btn-sm btn-icon btn-color-warning me-n2 d-none d-lg-flex "
                            data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body"
                            data-kt-toggle-name="app-sidebar-minimize">

                            <i class="ki-duotone ki-exit-left fs-2x rotate-180"><span class="path1"></span><span
                                    class="path2"></span></i>
                        </div>
                        <!--end::Sidebar toggle-->
                    </div>

                    <!--begin::Navbar-->
                    <div class="app-navbar flex-grow-1 justify-content-end" id="kt_app_header_navbar">
                        <div class="app-navbar-item d-flex align-items-stretch flex-lg-grow-1 me-2 me-lg-0">

                            <!--begin::Search-->
                            <div id="kt_header_search" class="header-search d-flex align-items-center w-lg-350px"
                                data-kt-search-keypress="true" data-kt-search-min-length="2"
                                data-kt-search-enter="enter" data-kt-search-layout="menu"
                                data-kt-search-responsive="true" data-kt-menu-trigger="auto"
                                data-kt-menu-permanent="true" data-kt-menu-placement="bottom-start">

                                <!--begin::Tablet and mobile search toggle-->
                                <div data-kt-search-element="toggle"
                                    class="search-toggle-mobile d-flex d-lg-none align-items-center">
                                    <div class="d-flex ">
                                        <i class="ki-duotone ki-magnifier fs-1 fs-1"><span class="path1"></span><span
                                                class="path2"></span></i>
                                    </div>
                                </div>
                                <!--end::Tablet and mobile search toggle-->

                                <!--begin::Form(use d-none d-lg-block classes for responsive search)-->
                                <form data-kt-search-element="form"
                                    class="d-none d-lg-block w-100 position-relative mb-5 mb-lg-0" autocomplete="off">
                                    <!--begin::Hidden input(Added to disable form autocomplete)-->
                                    <input type="hidden">
                                    <!--end::Hidden input-->

                                    <!--begin::Icon-->
                                    <i
                                        class="ki-duotone ki-magnifier search-icon fs-2 text-gray-500 position-absolute top-50 translate-middle-y ms-5"><span
                                            class="path1"></span><span class="path2"></span></i> <!--end::Icon-->

                                    <!--begin::Input-->
                                    <input type="text"
                                        class="search-input form-control form-control border-0 h-lg-40px  ps-13"
                                        name="search" value="" placeholder="Search..." data-kt-search-element="input">
                                    <!--end::Input-->

                                    <!--begin::Spinner-->
                                    <span
                                        class="search-spinner  position-absolute top-50 end-0 translate-middle-y lh-0 d-none me-5"
                                        data-kt-search-element="spinner">
                                        <span class="spinner-border h-15px w-15px align-middle text-gray-500"></span>
                                    </span>
                                    <!--end::Spinner-->

                                    <!--begin::Reset-->
                                    <span
                                        class="search-reset  btn btn-flush btn-active-color-primary position-absolute top-50 end-0 translate-middle-y lh-0 d-none me-4"
                                        data-kt-search-element="clear">
                                        <i class="ki-duotone ki-cross fs-2 fs-lg-1 me-0"><span
                                                class="path1"></span><span class="path2"></span></i> </span>
                                    <!--end::Reset-->
                                </form>
                                <!--end::Form-->
                                <!--begin::Menu-->
                                <div data-kt-search-element="content"
                                    class="menu menu-sub menu-sub-dropdown py-7 px-7 overflow-hidden w-300px w-md-350px">
                                    <!--begin::Wrapper-->
                                    <div data-kt-search-element="wrapper">
                                        <!--begin::Recently viewed-->
                                        <div data-kt-search-element="results" class="d-none">
                                            <!--begin::Items-->
                                            <div class="scroll-y mh-200px mh-lg-350px">
                                                <!--begin::Category title-->
                                                <h3 class="fs-5 text-muted m-0  pb-5"
                                                    data-kt-search-element="category-title">
                                                    Users </h3>
                                                <!--end::Category title-->




                                                <!--begin::Item-->
                                                <a href="#"
                                                    class="d-flex text-gray-900 text-hover-primary align-items-center mb-5">
                                                    <!--begin::Symbol-->
                                                    <div class="symbol symbol-40px me-4">
                                                        <img src="{{ asset('assets') }}/media/avatars/300-6.jpg" alt="">
                                                    </div>
                                                    <!--end::Symbol-->

                                                    <!--begin::Title-->
                                                    <div class="d-flex flex-column justify-content-start fw-semibold">
                                                        <span class="fs-6 fw-semibold">Karina Clark</span>
                                                        <span class="fs-7 fw-semibold text-muted">Marketing
                                                            Manager</span>
                                                    </div>
                                                    <!--end::Title-->
                                                </a>
                                                <!--end::Item-->



                                                <!--begin::Item-->
                                                <a href="#"
                                                    class="d-flex text-gray-900 text-hover-primary align-items-center mb-5">
                                                    <!--begin::Symbol-->
                                                    <div class="symbol symbol-40px me-4">
                                                        <img src="{{ asset('assets') }}/media/avatars/300-2.jpg" alt="">
                                                    </div>
                                                    <!--end::Symbol-->

                                                    <!--begin::Title-->
                                                    <div class="d-flex flex-column justify-content-start fw-semibold">
                                                        <span class="fs-6 fw-semibold">Olivia Bold</span>
                                                        <span class="fs-7 fw-semibold text-muted">Software
                                                            Engineer</span>
                                                    </div>
                                                    <!--end::Title-->
                                                </a>
                                                <!--end::Item-->



                                                <!--begin::Item-->
                                                <a href="#"
                                                    class="d-flex text-gray-900 text-hover-primary align-items-center mb-5">
                                                    <!--begin::Symbol-->
                                                    <div class="symbol symbol-40px me-4">
                                                        <img src="{{ asset('assets') }}/media/avatars/300-9.jpg" alt="">
                                                    </div>
                                                    <!--end::Symbol-->

                                                    <!--begin::Title-->
                                                    <div class="d-flex flex-column justify-content-start fw-semibold">
                                                        <span class="fs-6 fw-semibold">Ana Clark</span>
                                                        <span class="fs-7 fw-semibold text-muted">UI/UX Designer</span>
                                                    </div>
                                                    <!--end::Title-->
                                                </a>
                                                <!--end::Item-->



                                                <!--begin::Item-->
                                                <a href="#"
                                                    class="d-flex text-gray-900 text-hover-primary align-items-center mb-5">
                                                    <!--begin::Symbol-->
                                                    <div class="symbol symbol-40px me-4">
                                                        <img src="{{ asset('assets') }}/media/avatars/300-14.jpg"
                                                            alt="">
                                                    </div>
                                                    <!--end::Symbol-->

                                                    <!--begin::Title-->
                                                    <div class="d-flex flex-column justify-content-start fw-semibold">
                                                        <span class="fs-6 fw-semibold">Nick Pitola</span>
                                                        <span class="fs-7 fw-semibold text-muted">Art Director</span>
                                                    </div>
                                                    <!--end::Title-->
                                                </a>
                                                <!--end::Item-->



                                                <!--begin::Item-->
                                                <a href="#"
                                                    class="d-flex text-gray-900 text-hover-primary align-items-center mb-5">
                                                    <!--begin::Symbol-->
                                                    <div class="symbol symbol-40px me-4">
                                                        <img src="{{ asset('assets') }}/media/avatars/300-11.jpg"
                                                            alt="">
                                                    </div>
                                                    <!--end::Symbol-->

                                                    <!--begin::Title-->
                                                    <div class="d-flex flex-column justify-content-start fw-semibold">
                                                        <span class="fs-6 fw-semibold">Edward Kulnic</span>
                                                        <span class="fs-7 fw-semibold text-muted">System
                                                            Administrator</span>
                                                    </div>
                                                    <!--end::Title-->
                                                </a>
                                                <!--end::Item-->
                                                <!--begin::Category title-->
                                                <h3 class="fs-5 text-muted m-0 pt-5 pb-5"
                                                    data-kt-search-element="category-title">
                                                    Customers </h3>
                                                <!--end::Category title-->



                                                <!--begin::Item-->
                                                <a href="#"
                                                    class="d-flex text-gray-900 text-hover-primary align-items-center mb-5">
                                                    <!--begin::Symbol-->
                                                    <div class="symbol symbol-40px me-4">
                                                        <span class="symbol-label bg-light">
                                                            <img class="w-20px h-20px"
                                                                src="{{ asset('assets') }}/media/svg/brand-logos/volicity-9.svg"
                                                                alt="">
                                                        </span>
                                                    </div>
                                                    <!--end::Symbol-->

                                                    <!--begin::Title-->
                                                    <div class="d-flex flex-column justify-content-start fw-semibold">
                                                        <span class="fs-6 fw-semibold">Company Rbranding</span>
                                                        <span class="fs-7 fw-semibold text-muted">UI Design</span>
                                                    </div>
                                                    <!--end::Title-->
                                                </a>
                                                <!--end::Item-->



                                                <!--begin::Item-->
                                                <a href="#"
                                                    class="d-flex text-gray-900 text-hover-primary align-items-center mb-5">
                                                    <!--begin::Symbol-->
                                                    <div class="symbol symbol-40px me-4">
                                                        <span class="symbol-label bg-light">
                                                            <img class="w-20px h-20px"
                                                                src="{{ asset('assets') }}/media/svg/brand-logos/tvit.svg"
                                                                alt="">
                                                        </span>
                                                    </div>
                                                    <!--end::Symbol-->

                                                    <!--begin::Title-->
                                                    <div class="d-flex flex-column justify-content-start fw-semibold">
                                                        <span class="fs-6 fw-semibold">Company Re-branding</span>
                                                        <span class="fs-7 fw-semibold text-muted">Web Development</span>
                                                    </div>
                                                    <!--end::Title-->
                                                </a>
                                                <!--end::Item-->



                                                <!--begin::Item-->
                                                <a href="#"
                                                    class="d-flex text-gray-900 text-hover-primary align-items-center mb-5">
                                                    <!--begin::Symbol-->
                                                    <div class="symbol symbol-40px me-4">
                                                        <span class="symbol-label bg-light">
                                                            <img class="w-20px h-20px"
                                                                src="{{ asset('assets') }}/media/svg/misc/infography.svg"
                                                                alt="">
                                                        </span>
                                                    </div>
                                                    <!--end::Symbol-->

                                                    <!--begin::Title-->
                                                    <div class="d-flex flex-column justify-content-start fw-semibold">
                                                        <span class="fs-6 fw-semibold">Business Analytics App</span>
                                                        <span class="fs-7 fw-semibold text-muted">Administration</span>
                                                    </div>
                                                    <!--end::Title-->
                                                </a>
                                                <!--end::Item-->



                                                <!--begin::Item-->
                                                <a href="#"
                                                    class="d-flex text-gray-900 text-hover-primary align-items-center mb-5">
                                                    <!--begin::Symbol-->
                                                    <div class="symbol symbol-40px me-4">
                                                        <span class="symbol-label bg-light">
                                                            <img class="w-20px h-20px"
                                                                src="{{ asset('assets') }}/media/svg/brand-logos/leaf.svg"
                                                                alt="">
                                                        </span>
                                                    </div>
                                                    <!--end::Symbol-->

                                                    <!--begin::Title-->
                                                    <div class="d-flex flex-column justify-content-start fw-semibold">
                                                        <span class="fs-6 fw-semibold">EcoLeaf App Launch</span>
                                                        <span class="fs-7 fw-semibold text-muted">Marketing</span>
                                                    </div>
                                                    <!--end::Title-->
                                                </a>
                                                <!--end::Item-->



                                                <!--begin::Item-->
                                                <a href="#"
                                                    class="d-flex text-gray-900 text-hover-primary align-items-center mb-5">
                                                    <!--begin::Symbol-->
                                                    <div class="symbol symbol-40px me-4">
                                                        <span class="symbol-label bg-light">
                                                            <img class="w-20px h-20px"
                                                                src="{{ asset('assets') }}/media/svg/brand-logos/tower.svg"
                                                                alt="">
                                                        </span>
                                                    </div>
                                                    <!--end::Symbol-->

                                                    <!--begin::Title-->
                                                    <div class="d-flex flex-column justify-content-start fw-semibold">
                                                        <span class="fs-6 fw-semibold">Tower Group Website</span>
                                                        <span class="fs-7 fw-semibold text-muted">Google Adwords</span>
                                                    </div>
                                                    <!--end::Title-->
                                                </a>
                                                <!--end::Item-->

                                                <!--begin::Category title-->
                                                <h3 class="fs-5 text-muted m-0 pt-5 pb-5"
                                                    data-kt-search-element="category-title">
                                                    Projects </h3>
                                                <!--end::Category title-->


                                                <!--begin::Item-->
                                                <a href="#"
                                                    class="d-flex text-gray-900 text-hover-primary align-items-center mb-5">
                                                    <!--begin::Symbol-->
                                                    <div class="symbol symbol-40px me-4">
                                                        <span class="symbol-label bg-light">
                                                            <i class="ki-duotone ki-notepad fs-2 text-primary"><span
                                                                    class="path1"></span><span
                                                                    class="path2"></span><span
                                                                    class="path3"></span><span
                                                                    class="path4"></span><span class="path5"></span></i>
                                                        </span>
                                                    </div>
                                                    <!--end::Symbol-->

                                                    <!--begin::Title-->
                                                    <div class="d-flex flex-column">
                                                        <span class="fs-6 fw-semibold">Si-Fi Project by AU Themes</span>
                                                        <span class="fs-7 fw-semibold text-muted">#45670</span>
                                                    </div>
                                                    <!--end::Title-->
                                                </a>
                                                <!--end::Item-->



                                                <!--begin::Item-->
                                                <a href="#"
                                                    class="d-flex text-gray-900 text-hover-primary align-items-center mb-5">
                                                    <!--begin::Symbol-->
                                                    <div class="symbol symbol-40px me-4">
                                                        <span class="symbol-label bg-light">
                                                            <i class="ki-duotone ki-frame fs-2 text-primary"><span
                                                                    class="path1"></span><span
                                                                    class="path2"></span><span
                                                                    class="path3"></span><span class="path4"></span></i>
                                                        </span>
                                                    </div>
                                                    <!--end::Symbol-->

                                                    <!--begin::Title-->
                                                    <div class="d-flex flex-column">
                                                        <span class="fs-6 fw-semibold">Shopix Mobile App Planning</span>
                                                        <span class="fs-7 fw-semibold text-muted">#45690</span>
                                                    </div>
                                                    <!--end::Title-->
                                                </a>
                                                <!--end::Item-->



                                                <!--begin::Item-->
                                                <a href="#"
                                                    class="d-flex text-gray-900 text-hover-primary align-items-center mb-5">
                                                    <!--begin::Symbol-->
                                                    <div class="symbol symbol-40px me-4">
                                                        <span class="symbol-label bg-light">
                                                            <i class="ki-duotone ki-message-text-2 fs-2 text-primary"><span
                                                                    class="path1"></span><span
                                                                    class="path2"></span><span class="path3"></span></i>
                                                        </span>
                                                    </div>
                                                    <!--end::Symbol-->

                                                    <!--begin::Title-->
                                                    <div class="d-flex flex-column">
                                                        <span class="fs-6 fw-semibold">Finance Monitoring SAAS
                                                            Discussion</span>
                                                        <span class="fs-7 fw-semibold text-muted">#21090</span>
                                                    </div>
                                                    <!--end::Title-->
                                                </a>
                                                <!--end::Item-->



                                                <!--begin::Item-->
                                                <a href="#"
                                                    class="d-flex text-gray-900 text-hover-primary align-items-center mb-5">
                                                    <!--begin::Symbol-->
                                                    <div class="symbol symbol-40px me-4">
                                                        <span class="symbol-label bg-light">
                                                            <i class="ki-duotone ki-profile-circle fs-2 text-primary"><span
                                                                    class="path1"></span><span
                                                                    class="path2"></span><span class="path3"></span></i>
                                                        </span>
                                                    </div>
                                                    <!--end::Symbol-->

                                                    <!--begin::Title-->
                                                    <div class="d-flex flex-column">
                                                        <span class="fs-6 fw-semibold">Dashboard Analitics Launch</span>
                                                        <span class="fs-7 fw-semibold text-muted">#34560</span>
                                                    </div>
                                                    <!--end::Title-->
                                                </a>
                                                <!--end::Item-->


                                            </div>
                                            <!--end::Items-->
                                        </div>
                                        <!--end::Recently viewed-->
                                        <!--begin::Recently viewed-->
                                        <div class="" data-kt-search-element="main">
                                            <!--begin::Heading-->
                                            <div class="d-flex flex-stack fw-semibold mb-4">
                                                <!--begin::Label-->
                                                <span class="text-muted fs-6 me-2">Recently Searched:</span>
                                                <!--end::Label-->

                                                <!--begin::Toolbar-->
                                                <div class="d-flex" data-kt-search-element="toolbar">
                                                    <!--begin::Preferences toggle-->
                                                    <div data-kt-search-element="preferences-show"
                                                        class="btn btn-icon w-20px btn-sm btn-active-color-primary me-2 data-bs-toggle="
                                                        tooltip"="" title="Show search preferences">
                                                        <i class="ki-duotone ki-setting-2 fs-2"><span
                                                                class="path1"></span><span class="path2"></span></i>
                                                    </div>
                                                    <!--end::Preferences toggle-->

                                                    <!--begin::Advanced search toggle-->
                                                    <div data-kt-search-element="advanced-options-form-show"
                                                        class="btn btn-icon w-20px btn-sm btn-active-color-primary me-n1"
                                                        data-bs-toggle="tooltip" title="Show more search options">
                                                        <i class="ki-duotone ki-down fs-2"></i>
                                                    </div>
                                                    <!--end::Advanced search toggle-->
                                                </div>
                                                <!--end::Toolbar-->
                                            </div>
                                            <!--end::Heading-->

                                            <!--begin::Items-->
                                            <div class="scroll-y mh-200px mh-lg-325px">
                                                <!--begin::Item-->
                                                <div class="d-flex align-items-center mb-5">
                                                    <!--begin::Symbol-->
                                                    <div class="symbol symbol-40px me-4">
                                                        <span class="symbol-label bg-light">
                                                            <i class="ki-duotone ki-laptop fs-2 text-primary"><span
                                                                    class="path1"></span><span class="path2"></span></i>
                                                        </span>
                                                    </div>
                                                    <!--end::Symbol-->

                                                    <!--begin::Title-->
                                                    <div class="d-flex flex-column">
                                                        <a href="#"
                                                            class="fs-6 text-gray-800 text-hover-primary fw-semibold">BoomApp
                                                            by Keenthemes</a>
                                                        <span class="fs-7 text-muted fw-semibold">#45789</span>
                                                    </div>
                                                    <!--end::Title-->
                                                </div>
                                                <!--end::Item-->
                                                <!--begin::Item-->
                                                <div class="d-flex align-items-center mb-5">
                                                    <!--begin::Symbol-->
                                                    <div class="symbol symbol-40px me-4">
                                                        <span class="symbol-label bg-light">
                                                            <i class="ki-duotone ki-chart-simple fs-2 text-primary"><span
                                                                    class="path1"></span><span
                                                                    class="path2"></span><span
                                                                    class="path3"></span><span class="path4"></span></i>
                                                        </span>
                                                    </div>
                                                    <!--end::Symbol-->

                                                    <!--begin::Title-->
                                                    <div class="d-flex flex-column">
                                                        <a href="#"
                                                            class="fs-6 text-gray-800 text-hover-primary fw-semibold">"Kept
                                                            API Project Meeting</a>
                                                        <span class="fs-7 text-muted fw-semibold">#84050</span>
                                                    </div>
                                                    <!--end::Title-->
                                                </div>
                                                <!--end::Item-->
                                                <!--begin::Item-->
                                                <div class="d-flex align-items-center mb-5">
                                                    <!--begin::Symbol-->
                                                    <div class="symbol symbol-40px me-4">
                                                        <span class="symbol-label bg-light">
                                                            <i class="ki-duotone ki-chart fs-2 text-primary"><span
                                                                    class="path1"></span><span class="path2"></span></i>
                                                        </span>
                                                    </div>
                                                    <!--end::Symbol-->

                                                    <!--begin::Title-->
                                                    <div class="d-flex flex-column">
                                                        <a href="#"
                                                            class="fs-6 text-gray-800 text-hover-primary fw-semibold">"KPI
                                                            Monitoring App Launch</a>
                                                        <span class="fs-7 text-muted fw-semibold">#84250</span>
                                                    </div>
                                                    <!--end::Title-->
                                                </div>
                                                <!--end::Item-->
                                                <!--begin::Item-->
                                                <div class="d-flex align-items-center mb-5">
                                                    <!--begin::Symbol-->
                                                    <div class="symbol symbol-40px me-4">
                                                        <span class="symbol-label bg-light">
                                                            <i class="ki-duotone ki-chart-line-down fs-2 text-primary"><span
                                                                    class="path1"></span><span class="path2"></span></i>
                                                        </span>
                                                    </div>
                                                    <!--end::Symbol-->

                                                    <!--begin::Title-->
                                                    <div class="d-flex flex-column">
                                                        <a href="#"
                                                            class="fs-6 text-gray-800 text-hover-primary fw-semibold">Project
                                                            Reference FAQ</a>
                                                        <span class="fs-7 text-muted fw-semibold">#67945</span>
                                                    </div>
                                                    <!--end::Title-->
                                                </div>
                                                <!--end::Item-->
                                                <!--begin::Item-->
                                                <div class="d-flex align-items-center mb-5">
                                                    <!--begin::Symbol-->
                                                    <div class="symbol symbol-40px me-4">
                                                        <span class="symbol-label bg-light">
                                                            <i class="ki-duotone ki-sms fs-2 text-primary"><span
                                                                    class="path1"></span><span class="path2"></span></i>
                                                        </span>
                                                    </div>
                                                    <!--end::Symbol-->

                                                    <!--begin::Title-->
                                                    <div class="d-flex flex-column">
                                                        <a href="#"
                                                            class="fs-6 text-gray-800 text-hover-primary fw-semibold">"FitPro
                                                            App Development</a>
                                                        <span class="fs-7 text-muted fw-semibold">#84250</span>
                                                    </div>
                                                    <!--end::Title-->
                                                </div>
                                                <!--end::Item-->
                                                <!--begin::Item-->
                                                <div class="d-flex align-items-center mb-5">
                                                    <!--begin::Symbol-->
                                                    <div class="symbol symbol-40px me-4">
                                                        <span class="symbol-label bg-light">
                                                            <i class="ki-duotone ki-bank fs-2 text-primary"><span
                                                                    class="path1"></span><span class="path2"></span></i>
                                                        </span>
                                                    </div>
                                                    <!--end::Symbol-->

                                                    <!--begin::Title-->
                                                    <div class="d-flex flex-column">
                                                        <a href="#"
                                                            class="fs-6 text-gray-800 text-hover-primary fw-semibold">Shopix
                                                            Mobile App</a>
                                                        <span class="fs-7 text-muted fw-semibold">#45690</span>
                                                    </div>
                                                    <!--end::Title-->
                                                </div>
                                                <!--end::Item-->
                                                <!--begin::Item-->
                                                <div class="d-flex align-items-center mb-5">
                                                    <!--begin::Symbol-->
                                                    <div class="symbol symbol-40px me-4">
                                                        <span class="symbol-label bg-light">
                                                            <i class="ki-duotone ki-chart-line-down fs-2 text-primary"><span
                                                                    class="path1"></span><span class="path2"></span></i>
                                                        </span>
                                                    </div>
                                                    <!--end::Symbol-->

                                                    <!--begin::Title-->
                                                    <div class="d-flex flex-column">
                                                        <a href="#"
                                                            class="fs-6 text-gray-800 text-hover-primary fw-semibold">"Landing
                                                            UI Design" Launch</a>
                                                        <span class="fs-7 text-muted fw-semibold">#24005</span>
                                                    </div>
                                                    <!--end::Title-->
                                                </div>
                                                <!--end::Item-->
                                            </div>
                                            <!--end::Items-->
                                        </div>
                                        <!--end::Recently viewed-->
                                        <!--begin::Empty-->
                                        <div data-kt-search-element="empty" class="text-center d-none">
                                            <!--begin::Icon-->
                                            <div class="pt-10 pb-10">
                                                <i class="ki-duotone ki-search-list fs-4x opacity-50"><span
                                                        class="path1"></span><span class="path2"></span><span
                                                        class="path3"></span></i>
                                            </div>
                                            <!--end::Icon-->

                                            <!--begin::Message-->
                                            <div class="pb-15 fw-semibold">
                                                <h3 class="text-gray-600 fs-5 mb-2">No result found</h3>
                                                <div class="text-muted fs-7">Please try again with a different query
                                                </div>
                                            </div>
                                            <!--end::Message-->
                                        </div>
                                        <!--end::Empty-->
                                    </div>
                                    <!--end::Wrapper-->

                                    <!--begin::Preferences-->
                                    <form data-kt-search-element="advanced-options-form" class="pt-1 d-none">
                                        <!--begin::Heading-->
                                        <h3 class="fw-semibold text-gray-900 mb-7">Advanced Search</h3>
                                        <!--end::Heading-->

                                        <!--begin::Input group-->
                                        <div class="mb-5">
                                            <input type="text" class="form-control form-control-sm form-control-solid"
                                                placeholder="Contains the word" name="query">
                                        </div>
                                        <!--end::Input group-->

                                        <!--begin::Input group-->
                                        <div class="mb-5">
                                            <!--begin::Radio group-->
                                            <div class="nav-group nav-group-fluid">
                                                <!--begin::Option-->
                                                <label>
                                                    <input type="radio" class="btn-check" name="type" value="has"
                                                        checked="checked">
                                                    <span
                                                        class="btn btn-sm btn-color-muted btn-active btn-active-primary">
                                                        All
                                                    </span>
                                                </label>
                                                <!--end::Option-->

                                                <!--begin::Option-->
                                                <label>
                                                    <input type="radio" class="btn-check" name="type" value="users">
                                                    <span
                                                        class="btn btn-sm btn-color-muted btn-active btn-active-primary px-4">
                                                        Users
                                                    </span>
                                                </label>
                                                <!--end::Option-->

                                                <!--begin::Option-->
                                                <label>
                                                    <input type="radio" class="btn-check" name="type" value="orders">
                                                    <span
                                                        class="btn btn-sm btn-color-muted btn-active btn-active-primary px-4">
                                                        Orders
                                                    </span>
                                                </label>
                                                <!--end::Option-->

                                                <!--begin::Option-->
                                                <label>
                                                    <input type="radio" class="btn-check" name="type" value="projects">
                                                    <span
                                                        class="btn btn-sm btn-color-muted btn-active btn-active-primary px-4">
                                                        Projects
                                                    </span>
                                                </label>
                                                <!--end::Option-->
                                            </div>
                                            <!--end::Radio group-->
                                        </div>
                                        <!--end::Input group-->

                                        <!--begin::Input group-->
                                        <div class="mb-5">
                                            <input type="text" name="assignedto"
                                                class="form-control form-control-sm form-control-solid"
                                                placeholder="Assigned to" value="">
                                        </div>
                                        <!--end::Input group-->

                                        <!--begin::Input group-->
                                        <div class="mb-5">
                                            <input type="text" name="collaborators"
                                                class="form-control form-control-sm form-control-solid"
                                                placeholder="Collaborators" value="">
                                        </div>
                                        <!--end::Input group-->

                                        <!--begin::Input group-->
                                        <div class="mb-5">
                                            <!--begin::Radio group-->
                                            <div class="nav-group nav-group-fluid">
                                                <!--begin::Option-->
                                                <label>
                                                    <input type="radio" class="btn-check" name="attachment" value="has"
                                                        checked="checked">
                                                    <span
                                                        class="btn btn-sm btn-color-muted btn-active btn-active-primary">
                                                        Has attachment
                                                    </span>
                                                </label>
                                                <!--end::Option-->

                                                <!--begin::Option-->
                                                <label>
                                                    <input type="radio" class="btn-check" name="attachment" value="any">
                                                    <span
                                                        class="btn btn-sm btn-color-muted btn-active btn-active-primary px-4">
                                                        Any
                                                    </span>
                                                </label>
                                                <!--end::Option-->
                                            </div>
                                            <!--end::Radio group-->
                                        </div>
                                        <!--end::Input group-->

                                        <!--begin::Input group-->
                                        <div class="mb-5">
                                            <select name="timezone" aria-label="Select a Timezone"
                                                data-control="select2" data-dropdown-parent="#kt_header_search"
                                                data-placeholder="date_period"
                                                class="form-select form-select-sm form-select-solid">
                                                <option value="next">Within the next</option>
                                                <option value="last">Within the last</option>
                                                <option value="between">Between</option>
                                                <option value="on">On</option>
                                            </select>
                                        </div>
                                        <!--end::Input group-->

                                        <!--begin::Input group-->
                                        <div class="row mb-8">
                                            <!--begin::Col-->
                                            <div class="col-6">
                                                <input type="number" name="date_number"
                                                    class="form-control form-control-sm form-control-solid"
                                                    placeholder="Lenght" value="">
                                            </div>
                                            <!--end::Col-->

                                            <!--begin::Col-->
                                            <div class="col-6">
                                                <select name="date_typer" aria-label="Select a Timezone"
                                                    data-control="select2" data-dropdown-parent="#kt_header_search"
                                                    data-placeholder="Period"
                                                    class="form-select form-select-sm form-select-solid">
                                                    <option value="days">Days</option>
                                                    <option value="weeks">Weeks</option>
                                                    <option value="months">Months</option>
                                                    <option value="years">Years</option>
                                                </select>
                                            </div>
                                            <!--end::Col-->
                                        </div>
                                        <!--end::Input group-->

                                        <!--begin::Actions-->
                                        <div class="d-flex justify-content-end">
                                            <button type="reset"
                                                class="btn btn-sm btn-light fw-bold btn-active-light-primary me-2"
                                                data-kt-search-element="advanced-options-form-cancel">Cancel</button>

                                            <a href="utilities/search/horizontal.html"
                                                class="btn btn-sm fw-bold btn-primary"
                                                data-kt-search-element="advanced-options-form-search">Search</a>
                                        </div>
                                        <!--end::Actions-->
                                    </form>
                                    <!--end::Preferences-->
                                    <!--begin::Preferences-->
                                    <form data-kt-search-element="preferences" class="pt-1 d-none">
                                        <!--begin::Heading-->
                                        <h3 class="fw-semibold text-gray-900 mb-7">Search Preferences</h3>
                                        <!--end::Heading-->

                                        <!--begin::Input group-->
                                        <div class="pb-4 border-bottom">
                                            <label
                                                class="form-check form-switch form-switch-sm form-check-custom form-check-solid flex-stack">
                                                <span class="form-check-label text-gray-700 fs-6 fw-semibold ms-0 me-2">
                                                    Projects
                                                </span>

                                                <input class="form-check-input" type="checkbox" value="1"
                                                    checked="checked">
                                            </label>
                                        </div>
                                        <!--end::Input group-->

                                        <!--begin::Input group-->
                                        <div class="py-4 border-bottom">
                                            <label
                                                class="form-check form-switch form-switch-sm form-check-custom form-check-solid flex-stack">
                                                <span class="form-check-label text-gray-700 fs-6 fw-semibold ms-0 me-2">
                                                    Targets
                                                </span>
                                                <input class="form-check-input" type="checkbox" value="1"
                                                    checked="checked">
                                            </label>
                                        </div>
                                        <!--end::Input group-->

                                        <!--begin::Input group-->
                                        <div class="py-4 border-bottom">
                                            <label
                                                class="form-check form-switch form-switch-sm form-check-custom form-check-solid flex-stack">
                                                <span class="form-check-label text-gray-700 fs-6 fw-semibold ms-0 me-2">
                                                    Affiliate Programs
                                                </span>
                                                <input class="form-check-input" type="checkbox" value="1">
                                            </label>
                                        </div>
                                        <!--end::Input group-->

                                        <!--begin::Input group-->
                                        <div class="py-4 border-bottom">
                                            <label
                                                class="form-check form-switch form-switch-sm form-check-custom form-check-solid flex-stack">
                                                <span class="form-check-label text-gray-700 fs-6 fw-semibold ms-0 me-2">
                                                    Referrals
                                                </span>
                                                <input class="form-check-input" type="checkbox" value="1"
                                                    checked="checked">
                                            </label>
                                        </div>
                                        <!--end::Input group-->

                                        <!--begin::Input group-->
                                        <div class="py-4 border-bottom">
                                            <label
                                                class="form-check form-switch form-switch-sm form-check-custom form-check-solid flex-stack">
                                                <span class="form-check-label text-gray-700 fs-6 fw-semibold ms-0 me-2">
                                                    Users
                                                </span>
                                                <input class="form-check-input" type="checkbox" value="1">
                                            </label>
                                        </div>
                                        <!--end::Input group-->

                                        <!--begin::Actions-->
                                        <div class="d-flex justify-content-end pt-7">
                                            <button type="reset"
                                                class="btn btn-sm btn-light fw-bold btn-active-light-primary me-2"
                                                data-kt-search-element="preferences-dismiss">Cancel</button>
                                            <button type="submit" class="btn btn-sm fw-bold btn-primary">Save
                                                Changes</button>
                                        </div>
                                        <!--end::Actions-->
                                    </form>
                                    <!--end::Preferences-->
                                </div>
                                <!--end::Menu-->
                            </div>
                            <!--end::Search-->
                        </div>

  

                     

                                 <!--begin::User menu-->
                                 <div class="app-navbar-item ms-3 ms-lg-4 me-lg-2" id="kt_header_user_menu_toggle">
                            <!--begin::Menu wrapper-->
                            <div class="cursor-pointer symbol symbol-30px symbol-lg-40px"
                                data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent"
                                data-kt-menu-placement="bottom-end">
                                {{ project() }} <i class="fas fa-angle-down"></i>
                            </div>

                            <!--begin::User account menu-->
                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px"
                                data-kt-menu="true">
                                <!--begin::Menu item-->
                                <div class="menu-item px-3">
                                    <div class="menu-content d-flex align-items-center px-3">
                                        <!--begin::Avatar-->
                                        <div class="symbol symbol-50px me-5">
                                            <img alt="Avatar" src="{{ Auth::user()->get_gravatar(150) }}">
                                        </div>
                                        <!--end::Avatar-->

                                        <!--begin::Username-->
                                        <div class="d-flex flex-column">
                                            <div class="fw-bold d-flex align-items-center fs-5">
                                                {{ Auth::user()->name }}
                                                @if(Auth::user()->is_client() && Auth::user()->package && package(Auth::user()->package)->name == "Basic")
                                                    <span
                                                        class="badge badge-light-success fw-bold fs-8 px-2 py-1 ms-2">Basic</span>
                                                @endif
                                            </div>
                                            <a href="#" class="fw-semibold text-muted text-hover-primary fs-7">
                                                {{ Auth::user()->email }}
                                            </a>
                                        </div>
                                        <!--end::Username-->
                                    </div>
                                </div>
                                <!--end::Menu item-->

                                <!--begin::Menu separator-->
                                <div class="separator my-2"></div>
                                <!--end::Menu separator-->
                                @if(Auth::user()->is_client())
                                @if(Auth::user()->is_client() && Auth::user()->package && package(Auth::user()->package)->name == "Basic")
                                    <div class="help-box text-center">
                                        <p class="mb-3 mt-2 text-muted">
                                            <strong>{{ package(Auth::user()->package)->name }}</strong><br>
                                            Upgrade your plan and get the most out of Fedhatrac
                                        </p>
                                        <div class="mt-3">
                                            <a href="{{ route('subscribe') }}" class="btn btn-success">Upgrade now</a>
                                        </div>
                                    </div>
                                @endif

                                <!--begin::Menu item-->
                                <div class="menu-item px-5">
                                    <a href="{{ url('account') }}" class="menu-link px-5">My Profile</a>
                                </div>
                                <!--end::Menu item-->

                                <!--begin::Menu item-->
                                <div class="menu-item px-5">
                                    <a href="/admin/settings" class="menu-link px-5">Business settings</a>
                                </div>
                                <!--end::Menu item-->

                                <!--begin::Menu item-->
                                <div class="menu-item px-5">
                                    <a href="#" class="menu-link px-5" data-bs-toggle="modal"
                                        data-bs-target="#switchbusinesses">
                                        Switch businesses
                                    </a>
                                </div>
                                <!--end::Menu item-->

                                <!--begin::Menu item-->
                                <div class="menu-item px-5">
                                    <a href="{{ url('businesses') }}" class="menu-link px-5">Manage your businesses</a>
                                </div>
                                <!--end::Menu item-->

                                <!--begin::Menu item-->
                                <div class="menu-item px-5">
                                    <a href="{{ url('billings') }}" class="menu-link px-5">Manage your billings</a>
                                </div>
                                <!--end::Menu item-->

                                <!--begin::Menu item-->
                                <div class="menu-item px-5">
                                    <a href="{{ url('subscribe') }}" class="menu-link px-5">Manage your
                                        subscriptions</a>
                                </div>
                                <!--end::Menu item-->

                                @endif

                                @if(Auth::user()->is_admin())
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-5">
                                        <a href="{{ route('dashboard') }}" class="menu-link px-5"><i class="fa fa-home"></i> 
                                            Switch to B2C</a>
                                    </div>
                                    <!--end::Menu item-->

                                    <!--begin::Menu item-->
                                    <div class="menu-item px-5">
                                        <a href="{{ route('website_info', $website->id) }}" class="menu-link px-5"><i
                                                class="fa fa-gear"></i>  Website settings</a>
                                    </div>
                                    <!--end::Menu item-->
                                @endif

                                <!--begin::Menu separator-->
                                <div class="separator my-2"></div>
                                <!--end::Menu separator-->

                                <!--begin::Menu item-->
                                <div class="menu-item px-5">
                                    <a href="{{ route('logout') }}" class="menu-link px-5"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Sign
                                        out</a>
                                </div>
                                <!--end::Menu item-->

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>

                                <!--begin::Menu separator-->
                                <div class="separator my-2"></div>
                                <!--end::Menu separator-->

                                <!--begin::Menu item-->
                                <div class="menu-item px-5 text-center fw-bold fs-10 text-body-quaternary">
                                    <a class="text-body-quaternary me-1" href="#!">Privacy policy</a>&bull;
                                    <a class="text-body-quaternary mx-1" href="#!">Terms</a>&bull;
                                    <a class="text-body-quaternary ms-1" href="#!">Cookies</a>
                                </div>
                                <!--end::Menu item-->
                            </div>
                            <!--end::User account menu-->
                        </div>

                        <!--begin::Action-->
                        <div class="app-navbar-item ms-3 ms-lg-4 me-lg-6">
                            <!--begin::Link-->
                            <a href="#"
                                class="btn btn-icon btn-custom btn-color-gray-600 btn-active-color-primary w-35px h-35px w-md-40px h-md-40px">
                                <i class="ki-duotone ki-setting-3 fs-1"><span class="path1"></span><span
                                        class="path2"></span><span class="path3"></span><span class="path4"></span><span
                                        class="path5"></span></i>
                            </a>
                            <!--end::Link-->
                        </div>
                        <!--end::Action-->

                
                        <!--end::Header menu toggle-->
                    </div>
                    <!--end::Navbar-->
                </div>
                <!--end::Header main-->

                <!--begin::Separator-->
                <div class="app-header-separator"></div>
                <!--end::Separator-->
            </div>
            <!--end::Header-->



            <!--begin::Wrapper-->
            <div class="app-wrapper  flex-column flex-row-fluid " id="kt_app_wrapper">






                <!--begin::Sidebar-->
                <div id="kt_app_sidebar" class="app-sidebar  flex-column " data-kt-drawer="true"
                    data-kt-drawer-name="app-sidebar" data-kt-drawer-activate="{default: true, lg: false}"
                    data-kt-drawer-overlay="true" data-kt-drawer-width="250px" data-kt-drawer-direction="start"
                    data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">

                    <!--begin::Main-->
                    <div class="d-flex flex-column justify-content-between h-100 hover-scroll-overlay-y my-2 mx-5 d-flex flex-column"
                        id="kt_app_sidebar_main" data-kt-scroll="true" data-kt-scroll-activate="true"
                        data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_app_header"
                        data-kt-scroll-wrappers="#kt_app_main" data-kt-scroll-offset="5px">
                        <!--begin::Sidebar menu-->
                        <div id="#kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false"
                            class="flex-column-fluid menu menu-sub-indention menu-column menu-rounded menu-active-bg mb-7">
                            <!--begin:Menu item-->
                            <div data-kt-menu-trigger="click" class="menu-item here show menu-accordion">
                                <!--begin:Menu link-->
                                <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
    <!--begin:Menu link-->
    <a href="{{ url('dashboard') }}" class="menu-link">
        <span class="menu-icon">
            <i class="ki-duotone ki-element-11 fs-1">
                <span class="path1"></span>
                <span class="path2"></span>
                <span class="path3"></span>
                <span class="path4"></span>
            </i>
        </span>
        <span class="menu-title">Dashboards</span>
    </a>
    <!--end:Menu link-->
    <!--begin:Menu sub-->
    <!-- Your sub-menu code here -->
    <!--end:Menu sub-->
</div>



                   
                            </div><!--end:Menu item--><!--begin:Menu item-->


              
<!-- Ensure Font Awesome CSS is included in the <head> of your HTML -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">



<!-- Bills of Quantities (BQ) -->
<div data-kt-menu-trigger="click" class="menu-item menu-accordion">
    <span class="menu-link">
        <span class="menu-icon"><i class="fas fa-file-alt fs-1"></i></span>
        <span class="menu-title">Bills of Quantities</span>
        <span class="menu-arrow"></span>
    </span>
    <div class="menu-sub menu-sub-accordion">
        <div class="menu-item">
            <a class="menu-link" href="{{ url('bq_documents') }}" title="Create, manage, and store BQ documents">
                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                <span class="menu-title">Manage BQ Documents</span>
            </a>
        </div>
        <div class="menu-item">
            <a class="menu-link" href="bq-sections.html" title="Standard libraries for BQ sections">
                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                <span class="menu-title">BQ Sections Libraries</span>
            </a>
        </div>
    </div>
</div>

<!-- Bills of Materials (BOM) -->
<div data-kt-menu-trigger="click" class="menu-item menu-accordion">
    <span class="menu-link">
        <span class="menu-icon"><i class="fas fa-cogs fs-1"></i></span>
        <span class="menu-title">Bills of Materials</span>
        <span class="menu-arrow"></span>
    </span>
    <div class="menu-sub menu-sub-accordion">
        <div class="menu-item">
            <a class="menu-link" href="{{ route('boms.index') }}" title="Generate BOM from BQ">
                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                <span class="menu-title">Generate BOM</span>
            </a>
        </div>
        <div class="menu-item">
            <a class="menu-link" href="estimate-labor-materials.html" title="Estimate labor and materials">
                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                <span class="menu-title">Labor & Materials Estimation</span>
            </a>
        </div>
    </div>
</div>


<!-- Labour -->
<div data-kt-menu-trigger="click" class="menu-item menu-accordion">
    <span class="menu-link">
        <span class="menu-icon"><i class="fas fa-users fs-1"></i></span>
        <span class="menu-title">Labour</span>
        <span class="menu-arrow"></span>
    </span>
    <div class="menu-sub menu-sub-accordion">
        <!-- Manage Labour -->
        <div class="menu-item">
            <a class="menu-link" href="{{ url(route('workers.index')) }}" title="Manage existing labour resources">
                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                <span class="menu-title">Manage Labour</span>
            </a>
        </div>
        <!-- Labour Costing -->
        <div class="menu-item">
            <a class="menu-link" href="labour-costing.html" title="Estimate and track labour costs">
                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                <span class="menu-title">Labour Costing</span>
            </a>
        </div>
        <!-- Labour Reports -->
        <div class="menu-item">
            <a class="menu-link" href="labour-reports.html" title="Generate and view labour reports">
                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                <span class="menu-title">Labour Reports</span>
            </a>
        </div>
    </div>
</div>


<!-- Cost Tracking -->
<div data-kt-menu-trigger="click" class="menu-item menu-accordion">
    <span class="menu-link">
        <span class="menu-icon"><i class="fas fa-chart-line fs-1"></i></span>
        <span class="menu-title">Cost Tracking</span>
        <span class="menu-arrow"></span>
    </span>
    <div class="menu-sub menu-sub-accordion">
        <div class="menu-item">
            <a class="menu-link" href="real-time-cost.html" title="Real-time cost tracking">
                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                <span class="menu-title">Real-Time Tracking</span>
            </a>
        </div>
        <div class="menu-item">
            <a class="menu-link" href="input-output-comparison.html" title="Input vs. output cost comparison">
                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                <span class="menu-title">Cost Comparison</span>
            </a>
        </div>
        <div class="menu-item">
            <a class="menu-link" href="budget-tracking.html" title="Budget tracking for each project stage">
                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                <span class="menu-title">Budget Tracking</span>
            </a>
        </div>
    </div>
</div>

<!-- Material Management -->
<div data-kt-menu-trigger="click" class="menu-item menu-accordion">
    <span class="menu-link">
        <span class="menu-icon"><i class="fas fa-truck-loading fs-1"></i></span>
        <span class="menu-title">Material Management</span>
        <span class="menu-arrow"></span>
    </span>
    <div class="menu-sub menu-sub-accordion">
        <div class="menu-item">
            <a class="menu-link" href="{{ url(route('suppliers.index')) }}" title="Procurement and supply chain management">
                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                <span class="menu-title">Suppliers</span>
            </a>
        </div>
        <div class="menu-item">
            <a class="menu-link" href="{{ url(route('materials.index')) }}" title="Procurement and supply chain management">
                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                <span class="menu-title">Materials</span>
            </a>
        </div>
        <div class="menu-item">
            <a class="menu-link" href="payment-integration.html" title="Payment integration (MPESA, Bank, etc.)">
                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                <span class="menu-title">Payment Integration</span>
            </a>
        </div>
        <div class="menu-item">
            <a class="menu-link" href="inventory-tracking.html" title="Inventory tracking">
                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                <span class="menu-title">Inventory Tracking</span>
            </a>
        </div>
    </div>
</div>

<!-- Reporting -->
<div data-kt-menu-trigger="click" class="menu-item menu-accordion">
    <span class="menu-link">
        <span class="menu-icon"><i class="fas fa-file-alt fs-1"></i></span>
        <span class="menu-title">Reporting</span>
        <span class="menu-arrow"></span>
    </span>
    <div class="menu-sub menu-sub-accordion">
        <div class="menu-item">
            <a class="menu-link" href="daily-logs.html" title="Daily logs/reports">
                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                <span class="menu-title">Daily Logs</span>
            </a>
        </div>
        <div class="menu-item">
            <a class="menu-link" href="weekly-reports.html" title="Weekly performance reports">
                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                <span class="menu-title">Weekly Reports</span>
            </a>
        </div>
        <div class="menu-item">
            <a class="menu-link" href="cost-vs-work.html" title="Graphs and charts for input costs vs. work done">
                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                <span class="menu-title">Cost vs. Work</span>
            </a>
        </div>
    </div>
</div>

<!-- Communication -->
<div data-kt-menu-trigger="click" class="menu-item menu-accordion">
    <span class="menu-link">
        <span class="menu-icon"><i class="fas fa-comments fs-1"></i></span>
        <span class="menu-title">Communication</span>
        <span class="menu-arrow"></span>
    </span>
    <div class="menu-sub menu-sub-accordion">
        <div class="menu-item">
            <a class="menu-link" href="document-sharing.html" title="Document sharing">
                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                <span class="menu-title">Document Sharing</span>
            </a>
        </div>
        <div class="menu-item">
            <a class="menu-link" href="messaging-system.html" title="Messaging system with notifications and alerts">
                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                <span class="menu-title">Messaging System</span>
            </a>
        </div>
    </div>
</div>





                        </div>
                     
                        <!--end::Footer-->
                    </div>
                    <!--end::Main-->
                </div>
                <!--end::Sidebar-->








                @yield('content')

                </div>
                        </div>
                        <!--end::Footer-->
                    </div>
                    <!--end::Main-->
                </div>

    <!--begin::Javascript-->
    <script>
        var hostUrl = "/saul-html-pro/{{ asset('assets') }}/";        </script>

    <!--begin::Global Javascript Bundle(mandatory for all pages)-->
    <script src="{{ asset('assets') }}/plugins/global/plugins.bundle.js"></script>
    <script src="{{ asset('assets') }}/js/scripts.bundle.js"></script>
    <!--end::Global Javascript Bundle-->

    <!--begin::Vendors Javascript(used for this page only)-->
    <script src="{{ asset('assets') }}/plugins/custom/fullcalendar/fullcalendar.bundle.js"></script>

    <script src="{{ asset('assets') }}/plugins/custom/datatables/datatables.bundle.js"></script>
    <!--end::Vendors Javascript-->

    <!--begin::Custom Javascript(used for this page only)-->
    <script src="{{ asset('assets') }}/js/widgets.bundle.js"></script>
    <script src="{{ asset('assets') }}/js/custom/widgets.js"></script>
    <script src="{{ asset('assets') }}/js/custom/apps/chat/chat.js"></script>
    <script src="{{ asset('assets') }}/js/custom/utilities/modals/upgrade-plan.js"></script>
    <script src="{{ asset('assets') }}/js/custom/utilities/modals/create-account.js"></script>
    <script src="{{ asset('assets') }}/js/custom/utilities/modals/create-app.js"></script>
    <script src="{{ asset('assets') }}/js/custom/utilities/modals/users-search.js"></script>
    <!--end::Custom Javascript-->
    <!--end::Javascript-->
</body>
<!--end::Body-->

</html>