<!-- Javascript Files
================================================== -->
<script src="{{ asset('js/plugins.js') }}" defer></script>
<script src="{{ asset('js/designesia.js') }}" defer></script>
@if (! request()->routeIs('home'))
    <script src="{{ asset('js/custom-marquee.js') }}" defer></script>
@endif
<script src="{{ asset('js/orasi-performance.js') }}" defer></script>
