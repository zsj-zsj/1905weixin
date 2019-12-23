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
	<script src="http://res.wx.qq.com/open/js/jweixin-1.4.0.js"></script>
	<script src="/js/jquery.min.js"></script>
	<script src="/js/materialize.min.js"></script>
	<script src="/js/owl.carousel.min.js"></script>
	<script src="/js/fakeLoader.min.js"></script>
	<script src="/js/animatedModal.min.js"></script>
	<script src="/js/main.js"></script>

	<script>
		wx.config({
			debug: true, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
			appId: "{{$wx_config['appId']}}", // 必填，公众号的唯一标识
			timestamp: "{{$wx_config['timestamp']}}", // 必填，生成签名的时间戳
			nonceStr: "{{$wx_config['nonceStr']}}", // 必填，生成签名的随机串
			signature: "{{$wx_config['signature']}}",// 必填，签名
			jsApiList: ['updateAppMessageShareData','chooseImage','updateTimelineShareData'] // 必填，需要使用的JS接口列表
		});
		wx.ready(function () {   //需在用户可能点击分享按钮前就先调用
			wx.updateAppMessageShareData({
				title: '分享测试', // 分享标题
				desc: '描述', // 分享描述
				//link: 'http://1905zhangshaojie.comcto.com', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
				imgUrl: 'http://1905zhangshaojie.comcto.com/img/fenxiang.jpg', // 分享图标
				success: function () {
					// 设置成功
					alert(11111);
				}
			})
		});
	</script>

</body>
</html>