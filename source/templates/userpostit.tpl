<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1" />
		<title>ObjectDB official website - User's post-it</title>
		
		{% head_inclusions %}
		
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
		{% top %}
		
		<div id="content">
				<h1>Last 20 user's post-it</h1>
				<p>Those are the 20 last post-it inserted by users. When you become tired of read, follow next link to the <a href="index.php">index page	</a> of ObjectDB official website.</p>
			{ignore}<?php
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
			?>{/ignore}

		{% bottom %}
	</body>
</html>
