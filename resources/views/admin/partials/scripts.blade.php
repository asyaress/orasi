<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>

@include('admin.partials.datatables-scripts')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="{{ asset('js/admin.js') }}"></script>
<script src="{{ asset('js/admin-datatables.js') }}"></script>
<script src="{{ asset('js/admin-alerts.js') }}"></script>

@stack('scripts')

