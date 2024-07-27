<!DOCTYPE html>
<html>

<head>
    @include('backend.dashboard.component.head')
</head>

<body>
    <div id="wrapper">
        
        {{-- Sidebar --}}
        @include('backend.dashboard.component.sidebar')

        <div id="page-wrapper" class="gray-bg">

            {{-- Navigation --}}
            @include('backend.dashboard.component.nav')

            {{-- Index --}}
            @include($template)

            {{-- Footer --}}
            @include('backend.dashboard.component.footer')

        </div>
    </div>

    {{-- Script --}}
    @include('backend.dashboard.component.script')

</body>
</html>
