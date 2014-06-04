<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1" />
		<title>ObjectDB official website - Home</title>
		
		{% head_inclusions %}
		
		<script type="text/javascript">
			function createevents(){
				// delete the post-it text hen press inside 
				$('div#post-it textarea').click(function(){
					$(this).text("");
				});

				// count characters
				$('div#post-it textarea').keypress(function(){
					var x = document.getElementById("outputtext");
					$('div#post-it span').html(x.value.length+'/250 - ');
				});
			}

			function submitCharacters(){
				var text = document.getElementById("outputtext");

				if(text.value == "") { // if user make a blank post
					$('#errormsg').html("You cannot stick a blanck post-it!");
					return false;
				}

				if(text.value.length > 250) { // if user write more than 250 characters
					$('#errormsg').html("Your post-it must be 250 characters max!");
					return false;
				}

				if(text.value == "Please type your note here...") { // if user no change start text
					$('#errormsg').html("Please write something before post!");
					return false;
				}

				// ajax request of post-it save
				$.get(
					'WEB-INF/src/savepostit.php?text='+text.value,
					function (xml){
						if(xml != 1)
							$('#errormsg').html("An unexpected problem occur and your post-it cannot be sticked; please try later and conctact me if problem persist");
						else{
							$('#replymsg').html('Your post-it was succefully sticked, visit <a href="userpostit.php">this</a> link for read other user commentaries');
							text.value = "";
							$('div#post-it span').html('0/250 - ');
						}						
					});
				return false;
			}
		</script>
	</head>

	<body onload="createevents();">
		{% top %}

		<div id="content" class="left-indent right-indent">
			<h1>What is objectDB?</h1>

			<p>Accessing to databases in PHP, not compatible with object-oriented  paradigm, is complex when it is needed to store and retrieve objects instead of  separate variables. Many programmers break the encapsulation of code which  could be well modeled and easily maintainable; others create their own methods  of data access, which costs much of the development of the whole system.</p>
			<p>ObjectDB creates a general architecture for data access, which (through  inheritance) can be increased to support specific methods for every application.  With this library the programmer can obtain, save, and modify (among other  operations) objects directly from the database without having to memorize the  extensive API of PHP and possessing high knowledge of SQL. ObjectDB helps to write  clearer code (and therefore more maintainable), formable according to object-oriented  methodologies (currently the most used) and makes the programmer completely  independent of the database system to use.</p>
			<p>ObjectDB is designed to reduce work to a minimum. In use, tedious operations such as creating relationships between tables  and finding the last inserted tuple, become routine and use a few lines of  code. Although it might be thought that the work  of an extra layer of abstraction slows down the application, caching mechanisms  of objectDB avoid redundant operations and streamline the work so that no  visible notice of time delays.</p>
		
			<h1>Quick download</h1>
			<a href="downloads/core/objectdb.v4.0.zip" title="Download the last version of ObjectDB (670 kb)" alt="objectdb.v4.0.zip">
				<img class="menuelement" style="float:left; margin:0px 30px 0px 0px; width:170px;" src="images/quickdownload.png" />
			</a>
			<p>Get the last version of objectDB with just one click. For more download options, please visit the <a href="download.html">download page</a>. ObjectBD is free for download and use, but if you think it is a great software, please consider a minimal <a href="donate.html">monetary contribution</a> for project's livelihood.</p>

			<h1>Leave a post-it</h1>		
			<p>Please, leave a small commentary for next visitors. It's not necessary to type your real name, nickname or e-mail, but it will be nice. I will delete any post-it consider offensive or inmoral; the maximum number of characters per post-it is 250. Of course the engine for data access behind this page is ObjectDB! For read user's post-it, follow <a href="userpostit.html">this</a> link.</p>
			<p id="errormsg"></p><p id="replymsg"></p>
			<div id="post-it" style="background-image: url('images/post-it1.png');">
				<textarea id="outputtext">Please type your note here...</textarea><br/>
				<span style="font-size:small;">0/250 - </span><a href="#" onclick="return submitCharacters();">stick note</a>
			</div>
		</div>

		{% bottom %}
	</body>
</html>
