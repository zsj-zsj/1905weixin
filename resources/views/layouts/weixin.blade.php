<!DOCTYPE html>
<html lang="zxx">
<head>
	<meta charset="UTF-8">
	<title>@yield('title')</title>
	<meta name="viewport" content="width=device-width, initial-scale=1  maximum-scale=1 user-scalable=no">
	<meta name="mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-touch-fullscreen" content="yes">
	<meta name="HandheldFriendly" content="True">

	<link rel="stylesheet" href="/css/materialize.css">
	<link rel="stylesheet" href="/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="/css/normalize.css">
	<link rel="stylesheet" href="/css/owl.carousel.css">
	<link rel="stylesheet" href="/css/owl.theme.css">
	<link rel="stylesheet" href="/css/owl.transitions.css">
	<link rel="stylesheet" href="/css/fakeLoader.css">
	<link rel="stylesheet" href="/css/animate.css">
	<link rel="stylesheet" href="/css/style.css">
	<link rel="shortcut icon" href="/img/favicon.png">

</head>
<body>  
		<!-- navbar top -->
		<div class="navbar-top">
				<!-- site brand	 -->
				<div class="site-brand">
					<a href="index.html"><h1>Mstore</h1></a>
				</div>
				<!-- end site brand	 -->
				<div class="side-nav-panel-right">
					<a href="#" data-activates="slide-out-right" class="side-nav-left"><i class="fa fa-user"></i></a>
					{{-- <img src="{{$tu['headimgurl']}}" width="50"> --}}
				</div>
			</div>
			<!-- end navbar top -->
		
			<!-- side nav right-->
			<div class="side-nav-panel-right">
				<ul id="slide-out-right" class="side-nav side-nav-panel collapsible">
					<li class="profil">
						<img src="{{session('headimgurl')??''}}" alt="">
						<h2> {{session('nickname')??''}} </h2>
					</li>
					<li><a href="setting.html"><i class="fa fa-cog"></i>Settings</a></li>
					<li><a href="about-us.html"><i class="fa fa-user"></i>About Us</a></li>
					<li><a href="contact.html"><i class="fa fa-envelope-o"></i>Contact Us</a></li>
					<li><a href="login.html"><i class="fa fa-sign-in"></i>Login</a></li>
					<li><a href="register.html"><i class="fa fa-user-plus"></i>Register</a></li>
				</ul>
			</div>
			<!-- end side nav right-->
    @yield('content')
    

    <!-- scripts -->
	<script src="/js/jquery.min.js"></script>
	<script src="/js/materialize.min.js"></script>
	<script src="/js/owl.carousel.min.js"></script>
	<script src="/js/fakeLoader.min.js"></script>
	<script src="/js/animatedModal.min.js"></script>
	<script src="/js/main.js"></script>

</body>
</html>