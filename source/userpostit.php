<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1" />
		<title>ObjectDB official website - User's post-it</title>
		
				<!--jquery 1.4-->
		<script type="text/javascript" src="scripts/jquery-1.4.2.min.js"></script>

		<!--table of contents-->
		<script type="text/javascript" src="scripts/toc/toc.js"></script>
		<link rel="stylesheet" href="styles/toc.css" />

		<!--code highlight-->
		<script type="text/javascript" src="scripts/shjs/sh_main.min.js"></script>
		<script type="text/javascript" src="scripts/shjs/lang/sh_sql.min.js"></script>
		<script type="text/javascript" src="scripts/shjs/lang/sh_php.min.js"></script>
		<link type="text/css" rel="stylesheet" href="styles/sh_emacs.min.css">
		
		<!--page functionality-->
		<script type="text/javascript" src="scripts/load.js"></script>
		
		<!--google analytics-->
		<script type="text/javascript" src="scripts/analytics.js"></script>
		
		<link type="text/css" rel="stylesheet" href="styles/theme.css">

		
		<style type="text/css">
			#content{
				background-color: white;
				padding: 0px 30px 40px 30px;
			}

			.postit{
				width: 300px;
				height: 330px;
				float: left;
				background-repeat: no-repeat;
				background-position: center center;
			}
			.postittype{
				display: none;
			}
			.date{
				margin-top: 50px;
				float: right;
			}
			.text{
				position: relative;
				text-align: left;
				top: 75px;
				right: -45px;
				width: 160px;
			}
		</style>
		
	</head>
	<body>
		<div id="top" class="left-indent">
	<table id="menu" border="0">
		<tr>
			<td colspan="6" id="menutitle">Select a menu element to begin</td>
		</tr>
		<tr>
			<td><a class="menuelement" href="index.html"><img src="images/home.png" title="Go to start page"/></a></td>
			<td><a class="menuelement" href="doc.html"><img src="images/help.png" title="Read documentation, start with objectBD from cero"/></a></td>
			<td><a class="menuelement" href="api.odbexception.html"><img src="images/api.png" title="Consult API for reference"/></a></td>
			<td><a class="menuelement" href="download.html"><img src="images/download.png" title="Freely download objectDB"/></a></td>
			<td><a class="menuelement" href="about.html"><img src="images/about.png" title="About me and contact"/></a></td>
			<td><a class="menuelement" href="donate.html"><img src="images/donate.png" title="Please consider a minimal donation for project's livelihood"/></a></td>
		</tr>
		<tr>
			<td>Home</td>
			<td>Documentation</td>
			<td>API</td>
			<td>Download</td>
			<td>Contact</td>
			<td>Donate</td>
		</tr>
	</table>

	<div id="logo">
		<h1 id="name">ObjectDB</h1>
		<p id="banner">simple as write in paper</p>
	</div>
</div>

		
		<div id="content">
				<h1>Last 20 user's post-it</h1>
				<p>Those are the 20 last post-it inserted by users. When you become tired of read, follow next link to the <a href="index.php">index page	</a> of ObjectDB official website.</p>
			<?php
				require_once "../objectdb/objectDB-mysql-v3.0.php";
				require_once "../postit.php";
			
				$db = new objectDB();
				$postit_list = $db->getObjs('postit',"1=1 ORDER BY id_postit DESC LIMIT 20");

				foreach($postit_list as $postit){
					$date = $postit->date;
					$text = $postit->text;
					$type = $postit->postittype;
					echo "
						<div class='postit' style='background-image:url(\"images/post-it$type.png\")'><p class='date'>$date</p>
							<p class='text'>$text</p>
							<span class='postittype'>$type</span>
						</div>
					";
				}
			?>

		<div id="bottom" class="left-indent center">
	<a href="http://validator.w3.org/check?uri=referer">
		<img style="border:0;width:88px;height:31px" src="http://www.w3.org/Icons/valid-xhtml10-blue" alt="Valid XHTML 1.0 Transitional" height="31" width="88" />
	</a>
	<a href="http://jigsaw.w3.org/css-validator/check/referer">
		<img style="border:0;width:88px;height:31px" src="http://www.w3.org/Icons/valid-css2-blue" alt="Valid CSS!" />
	</a>
</div>
	</body>
</html>
