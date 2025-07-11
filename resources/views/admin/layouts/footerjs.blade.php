   <!-- Required Js -->
<!-- jQuery -->
<script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>

<!-- Popper (required for Bootstrap dropdowns) -->
<script src="{{ asset('assets/js/plugins/popper.min.js') }}"></script>

<!-- Bootstrap JS -->
<script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>

<!-- Summernote -->
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote.min.js"></script>

<!-- Optional plugins (after core dependencies) -->
<script src="{{ asset('assets/js/plugins/simplebar.min.js') }}"></script>
<script src="{{ asset('assets/js/icon/custom-icon.js') }}"></script>
<script src="{{ asset('assets/js/plugins/feather.min.js') }}"></script>
<script src="{{ asset('assets/js/component.js') }}"></script>
<script src="{{ asset('assets/js/theme.js') }}"></script>
<script src="{{ asset('assets/js/script.js') }}?v=1.1.0"></script>

<!-- jQuery Validation -->
<script src="{{ asset('assets/js/plugins/jquery.validate/jquery.validate.min.js') }}"></script>

<!-- SweetAlert -->
<script src="{{ asset('assets/js/plugins/sweetalert/sweetalert.min.js') }}"></script>
<script>
  var csrf_token = "{{ csrf_token() }}";
</script>

  
    @stack('scripts')