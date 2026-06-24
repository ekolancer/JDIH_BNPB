@include($header ?? 'layout.header')

        <!-- Main Content -->
        @yield('content')
        <!-- End Content -->

@include($footer ?? 'layout.footer')