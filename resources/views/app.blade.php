<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		
		<meta name="theme-color" content="#000">
		<meta name="msapplication-navbutton-color" content="#000">
		<meta name="apple-mobile-web-app-status-bar-style" content="#000">
		
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $meta_title ?? 'LuxiQue' }}</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width,initial-scale=1">
		<meta name="facebook-domain-verification" content="gbyuem93j4ysdq10fjiogrx2sfd0rb" />
		
		<meta name="author" content="TechHut">
		<meta name="keywords" content="{{ $meta_keywords ?? 'techhut,tech product'}}">
		<meta property="og:title" content="{{ $meta_title ?? 'TechHut' }}">
		<meta property="og:description" content="{{ $meta_description ?? 'TechHut is an online market place of tech products. It is established on the aim to provide 100% genuine technological products with the highest customer satisfaction priority to the tech lover consumers in Bangladesh.' }}">
		<meta property="og:image" content="{{ $meta_image ?? 'https://www.techhut.com.bd/frontend/img/logo/logo.png'}}">
		<meta property="og:url" content="https://techhut.com.bd">
		<meta name="twitter:title" content="{{ $meta_title ?? 'TechHut' }}">
		<meta name="twitter:description" content="{{ $meta_description ?? 'TechHut is an online market place of tech products. It is established on the aim to provide 100% genuine technological products with the highest customer satisfaction priority to the tech lover consumers in Bangladesh.' }}">
		<meta name="twitter:image" content="{{ $meta_image ?? 'https://www.techhut.com.bd/frontend/img/logo/logo.png'}}">
		<meta name="twitter:card" content="summary_large_image">
		
		
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/color/color6.css')}}">
		<link rel="icon" href="{{ asset('frontend/images/favicon/favicon.png')}}" type="image/x-icon">
		<link rel="shortcut icon" href="{{ asset('frontend/images/favicon/favicon.png')}}" type="image/x-icon">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" integrity="sha512-5A8nwdMOWrSz20fDsjczgUidUBR8liPYU+WymTZP1lmY9G6Oc7HlZv156XqnsgNUzTyMefFTcsFH/tnJE/+xBg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
		<link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/bootstrap.min.css')}}">
		<link rel="stylesheet" type="text/css" href="{{ asset('frontend/lib/css/nivo-slider.css')}}">
		<link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/core.css')}}">
		<link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/shortcode/shortcodes.css')}}">
		<link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/style.css')}}">
		<link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/responsive.css')}}">
		<link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/custom.css')}}">
		<link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/style-customizer.css')}}">
		<!-- Theme  End-->

		<script src="{{ asset('frontend/js/vendor/modernizr-2.8.3.min.js')}}"></script>

		<script src="https://kit.fontawesome.com/9575f77c5e.js" crossorigin="anonymous"></script>
        <!-- Scripts -->
        @routes
        <script src="{{ mix('js/app.js') }}" defer></script>
		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=G-QB6N97SZTN"></script>
		<script>
		  window.dataLayer = window.dataLayer || [];
		  function gtag(){dataLayer.push(arguments);}
		  gtag('js', new Date());

		  gtag('config', 'G-QB6N97SZTN');
		</script>
		<!-- Facebook Pixel Code -->
			<script>
			  !function(f,b,e,v,n,t,s)
			  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
			  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
			  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
			  n.queue=[];t=b.createElement(e);t.async=!0;
			  t.src=v;s=b.getElementsByTagName(e)[0];
			  s.parentNode.insertBefore(t,s)}(window, document,'script',
			  'https://connect.facebook.net/en_US/fbevents.js');
			  fbq('init', '1228486527665240');
			  fbq('track', 'PageView');
			</script>
			<noscript><img height="1" width="1" style="display:none"
			  src="https://www.facebook.com/tr?id=1228486527665240&ev=PageView&noscript=1"
			/></noscript>
			<!-- End Facebook Pixel Code -->
    </head>
    <body class="font-sans antialiased">
		@inertia
		<script src="{{ asset('frontend/js/vendor/jquery-3.1.1.min.js')}}"></script>
		<script src="{{ asset('frontend/js/popper.min.js')}}"></script>
		<script src="{{ asset('frontend/js/bootstrap.min.js')}}"></script>
		<script src="{{ asset('frontend/lib/js/jquery.nivo.slider.js')}}"></script>
		<script src="{{ asset('frontend/js/plugins.js')}}"></script>
		<script src="{{ asset('frontend/js/main.js')}}"></script>
		<script src="{{ asset('frontend/js/custom.js')}}"></script>
    </body>
</html>
