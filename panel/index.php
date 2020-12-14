<!DOCTYPE HTML>
<html>
	<head>
		<!-- Basic <head> stuff: <title>, <style>s, <script>s, etc. -->
		<title>Shinjuku Panel</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<link href="//fonts.googleapis.com/css?family=Source+Sans+Pro:300,400" rel="stylesheet" />
		<script src="js/jquery.min.js"></script>
		<script src="js/config.js"></script>
		<script src="js/skel.min.js"></script>
		<noscript>
			<!-- Load some seperate styles in for clients that aren't running the javascript -->
			<link rel="stylesheet" href="css/skel-noscript.css" />
			<link rel="stylesheet" href="css/style.css" />
			<link rel="stylesheet" href="css/style-desktop.css" />
			<link rel="stylesheet" href="css/noscript.css" />
		</noscript>
		<!--[if lte IE 8]><script src="js/html5shiv.js"></script><link rel="stylesheet" href="css/ie8.css" /><![endif]-->
	</head>
	<body class="homepage">
		<!-- Wrapper-->
		<div id="wrapper">
			<!-- Nav -->
			<nav id="nav">
				<a href="#me" class="fa fa-home active"><span>Home</span></a>
				<a href="search.php" class="fa fa-folder"><span>Files</span></a>
			</nav>
			<!-- Main -->
			<div id="main">
				<!-- Me -->
				<article id="me" class="panel">
					<header>
						<h1>
							<?php session_start();
							// If they're not logged in, redirect them.
                                    			if (!isset($_SESSION['id'])) {
                                        			header('Location: ../login/');
                                    			}
                                    			echo 'Hi '.$_SESSION['email'];?>
                                    		</h1>
                                    		<span class="byline">How are you today?</span>
                                	</header>
                                	<a href="#files" class="jumplink pic">
                                    		<img src="images/bepis.jpg" alt="" />
                                	</a>
                            	</article>
                        </div>
                	<!-- Footer -->
                    	<div id="footer">
                        	<ul class="links">                            		
                            		<li><a href="https://derelictpillows.tumblr.com">Blog</a></li>
                            		<li><a href="../includes/api.php?do=logout">Logout</a></li>
                        	</ul>
                    	</div>
            	</div>
    	</body>
</html>
