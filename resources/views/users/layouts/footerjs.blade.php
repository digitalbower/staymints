<!--  Main jQuery  -->
<script data-cfasync="false" src="https://cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
<script src="{{asset('assets/user/js/jquery-3.7.1.min.js')}}"></script>
<!-- Popper and Bootstrap JS -->
<script src="{{asset('assets/user/js/popper.min.js')}}"></script>
<script src="{{asset('assets/user/js/bootstrap.min.js')}}"></script>
<!-- Swiper slider JS -->
<!-- Counvikasterup JS -->
<script src="{{asset('assets/user/js/jquery.counterup.min.js')}}"></script>
<script src="{{asset('assets/user/js/waypoints.min.js')}}"></script>
<!-- swiper -->
<script src="{{asset('assets/user/js/swiper-bundle.min.js')}}"></script>
<script src="{{asset('assets/user/js/jquery.fancybox.min.js')}}"></script>
<script src="{{asset('assets/user/js/jquery.nice-select.min.js')}}"></script>
<script src="{{asset('assets/user/js/moment.min.js')}}"></script>
<script src="{{asset('assets/user/js/daterangepicker.min.js')}}"></script>
<script src="{{asset('assets/user/js/select2.min.js')}}"></script>
<script src="{{asset('assets/user/js/wow.min.js')}}"></script>
<script src="{{asset('assets/user/js/gsap.min.js')}}"></script>
<script src="{{asset('assets/user/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('assets/user/js/all.min.js')}}"></script>
<script src="{{asset('assets/user/js/intlTelInput-jquery.min.js')}}"></script>
<script src="{{asset('assets/user/js/main.js')}}"></script>

<script>
$(document).ready(function() {
    $(".remove_guest").click(function() {
        $(".guest_counting").hide();
        $(".remove_guest").hide();
        $(".add_guest").show();
    });

    $(".add_guest").click(function() {
        $(".guest_counting").show();
        $(".remove_guest").show();
        $(".add_guest").hide();
    });
});

$(".child_quant_minus").click(function() {
    $(".childern_age").hide();
});

$(".child_quant_plus").click(function() {
    $(".childern_age").show();
});

</script>
@stack('scripts')