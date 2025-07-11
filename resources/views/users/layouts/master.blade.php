<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="description" content="" />
<meta name="keywords" content="" />
<link rel="apple-touch-icon" sizes="180x180" href="assets/image/favicon.ico" />
<title>TRAVEL WEBSITE || HOME</title>
@include('users.layouts.headercss')
</head>
<body>
<!-- scroll top -->
<div class="circle-container">
    <svg class="circle-progress svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
        <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
    </svg>
</div>
@include('users.layouts.header')
@yield('content')
@include('users.layouts.footer')
@include('users.layouts.footerjs')
</body>
</html>