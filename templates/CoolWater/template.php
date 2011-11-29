<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="Robots" content="index,follow" />

<link rel="stylesheet" href="templates/CoolWater/images/CoolWater.css" type="text/css" />
<link rel="stylesheet" href="templates/CoolWater/images/phpVana.css" type="text/css" />

<title><?php echo $html_title; ?></title>

</head>

<body>
<!-- wrap starts here -->
<div id="wrap">
		
	<!--header -->
	<div id="header">
	
		<h1 id="logo-text"><a href="<?php echo $_SERVER['PHP_SELF']; ?>"><?php echo $site_name; ?></a></h1>
		<p id="slogan"><?php echo $site_slogan; ?></p>
	
	</div>
	
	<!-- navigation -->
	<div  id="menu">
		<ul>
<?php navigation("horizontal", "<li>", "<li id=\"current\">", "</li>\n", "", "", "", "", ""); ?>
		</ul>
	</div>
	
	<!-- content-wrap starts here -->
	<div id="content-wrap">
		
		<div id="main">
<?php echo $content;?>
		</div>
		
		
		<div id="sidebar">
			<div class="center">
<?php module("total_users"); ?><br />
<?php module("total_characters"); ?><br />				
<?php module("ranking"); ?><br />				
<?php module("players_online"); ?><br />				
<?php module("banned_users"); ?><br />
			</div>
		</div>
	
	<!-- content-wrap ends here -->	
	</div>
	
	<!--footer starts here-->
	<div id="footer">
		<p>
			<?php copyright_footer(); ?> |
			Design by: <a href="http://www.styleshout.com/">styleshout</a> | 
			Valid <a href="http://validator.w3.org/check?uri=referer">XHTML</a> | 
			<a href="http://jigsaw.w3.org/css-validator/check/referer">CSS</a> |
			Page created in <?php echo $render_time; ?> seconds
		</p>
	</div>

<!-- wrap ends here -->
</div>

</body>
</html>
