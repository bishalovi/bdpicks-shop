<!DOCTYPE html>
<html lang="en">
@include('frontend.partials.head')
<body class="sticky-header" style="border: 1px solid darkgray; box-shadow: 0 0 12px rgb(0 0 0 / 42%);">
    <a href="#top" class="back-to-top main-bg" id="backto-top"><i class="fal fa-arrow-up"></i></a>
    @include('frontend.partials.header')
    @yield('content')

@include('frontend.partials.footer')
    