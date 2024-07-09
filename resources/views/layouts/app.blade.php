<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} | @hasSection('title')
            @yield('title')
        @endif</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>

    <script async src="https://maps.googleapis.com/maps/api/js?sensor=false&libraries=places&key=AIzaSyA_TX9nyVNa8TSMmVf86qs_qa90IywvTyk"></script>

    <!-- Styles -->
{{--    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>--}}
    @vite(['resources/css/app.css','resources/scss/app.scss','resources/js/main.js'])
    <script src="{{asset('js/plugins/iCheck/icheck.min.js')}}" defer></script>
    @yield('styles')

    <!-- Scripts -->
</head>

<body class="skin-1 pace-done">
<!-- Wrapper-->
<div id="wrapper">
    <!-- Navigation -->
    @include('layouts.sidebar')
    <div id="page-wrapper" class="gray-bg">
        @include('layouts.topnavbar')
        {{ $slot }}
        @include('layouts.footer')
    </div>
</div>
<script language="javascript">
    var Next = "{{trans('pagination.next')}}";
    var Previous = "{{trans('pagination.previous')}}";
    var First = "{{trans('pagination.first')}}";
    var Last = "{{trans('pagination.last')}}";
    var EmptyTable = "{{trans('pagination.empty_table')}}";
    var SortAscending = "{{trans('pagination.sort_ascending')}}";
    var SortDescending = "{{trans('pagination.sort_descending')}}";
    var sInfo = "{{trans('pagination.sInfo')}}";
    var sInfoEmpty = "{{trans('pagination.sInfoEmpty')}}";
    var sInfoFiltered = "{{trans('pagination.sInfoFiltered')}}";
    var sLengthMenu = "{{trans('pagination.sLengthMenu')}}";
    var sLoadingRecords = "{{trans('pagination.sLoadingRecords')}}";
    var sProcessing = "{{trans('pagination.sProcessing')}}";
    var sSearch = "{{trans('pagination.sSearch')}}";
    var sZeroRecords = "{{trans('pagination.sZeroRecords')}}";
    var device_assigned = "{{trans('main.devices.device_can_not_be_deleted')}}";
    var device_move_notify = "{{trans('main.devices.device_move_notify')}}";
</script>

@yield('scripts')

</body>
</html>
