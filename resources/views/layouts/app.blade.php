<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ get_setting('favicon') }}" type="image/x-icon"> <!-- Favicon-->
    <title>@yield('title') - {{ get_setting('company') }}</title>
    <meta name="description" content="@yield('meta_description', config('app.name'))">
    <meta name="author" content="@yield('meta_author', config('app.name'))">
    @yield('meta')

    {{-- See https://laravel.com/docs/5.5/blade#stacks for usage --}}
    @stack('before-styles')

    <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/jvectormap/jquery-jvectormap-2.0.3.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/morrisjs/morris.min.css') }}" />

    <!-- Custom Css -->
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/color_skins.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}?v={{ date('YmdHis') }}">
    @stack('after-styles')

    @if (trim($__env->yieldContent('page-styles')))
        @yield('page-styles')
    @endif
    @livewireStyles
</head>

<body class="theme-blue">
    <!-- Page Loader -->
    <div class="page-loader-wrapper">
        <div class="loader">
            @if (get_setting('logo'))
                <div class="m-t-30">
                    <img src="{{ get_setting('logo') }}" height="48" alt="{{ get_setting('company') }}">
                </div>
            @endif
            <p>Please wait...</p>
        </div>
    </div>

    <div id="wrapper">
        @include('layouts.navbar')
        @include('layouts.sidebar')
        <div id="main-content">
            <div class="container-fluid">
                <div class="block-header">
                    @if (session()->has('message-success'))
                        <div class="alert alert-success alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                            <i class="fa fa-check-circle"></i> {!! session('message-success') !!}
                        </div>
                    @endif
                    @if (session()->has('message-error'))
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                            <i class="fa fa-times-circle"></i> {!! session('message-error') !!}
                        </div>
                    @endif

                    <div class="alert alert-danger alert-dismissible" role="alert" style="display:none">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <i class="fa fa-times-circle"></i> <span class="message"></span>
                    </div>
                    <div class="alert alert-success alert-dismissible" role="alert" style="display:none">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <i class="fa fa-check-circle"></i> <span class="message"></span>
                    </div>

                    {{-- <div class="row">
                        <div class="col-lg-5 col-md-8 col-sm-12">
                            <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i
                                        class="fa fa-arrow-left"></i></a> @yield('title')</h2>
                            @if (trim($__env->yieldContent('parentPageTitle')) and trim($__env->yieldContent('title')))
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="/"><i class="icon-home"></i></a></li>
                                    @if (trim($__env->yieldContent('parentPageTitle')))
                                        <li class="breadcrumb-item">@yield('parentPageTitle')</li>
                                    @endif
                                    @if (trim($__env->yieldContent('title')))
                                        <li class="breadcrumb-item active">@yield('title')</li>
                                    @endif
                                </ul>
                            @endif
                        </div>
                        <div class="text-right col-lg-7 col-md-4 col-sm-12">
                            @if (trim($__env->yieldContent('title-right')))
                                @yield('title-right')
                            @endif
                        </div>
                    </div> --}}
                </div>
                @yield('content')
                {{ $slot }}
            </div>
        </div>
    </div>
    <!-- Scripts -->
    @stack('before-scripts')
    <script src="{{ asset('assets/bundles/libscripts.bundle.js') }}"></script>
    <script src="{{ asset('assets/bundles/vendorscripts.bundle.js') }}"></script>
    <script src="{{ asset('assets/bundles/morrisscripts.bundle.js') }}"></script><!-- Morris Plugin Js -->
    <script src="{{ asset('assets/bundles/jvectormap.bundle.js') }}"></script> <!-- JVectorMap Plugin Js -->
    <script src="{{ asset('assets/bundles/knob.bundle.js') }}"></script>
    <script src="{{ asset('assets/bundles/mainscripts.bundle.js') }}"></script>
    @livewireScripts
    @stack('after-scripts')
    @if (trim($__env->yieldContent('page-script')))
        <script>
            @yield('page-script')

        </script>
    @endif
    <script>
        Livewire.on('message-success', (msg) => {
            $('.alert-success').show();
            $('.alert-success .message').html(msg);
        });
        Livewire.on('message-error', (msg) => {
            $('.alert-error').show();
            $('.alert-error .message').html(msg);
        });
        $('*').tooltip();
    </script>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        {{ csrf_field() }}
    </form>
</body>

</html>
