<?php
	/**
	 * Determines the base domain name that was hit by the request
	 */
	$domain = $_SERVER ['HTTP_HOST'];
	$base_domain = null;
	if(strpos($domain, 'www.') === 0) {
		$base_domain = substr($domain, 4);
	}else{
		$base_domain = $domain;
	}
	
	/**
	 * Parses configuration files such that display keys are available
	 */
	function load_configuration() {
		global $domain;
		global $base_domain;
		$defaults_content = file_get_contents('defaults.json');
		$config = json_decode($defaults_content, true);
		
		$filename = $base_domain . '.json';
		while(file_exists($filename)) {
			$domain_content = file_get_contents($filename);
			$domain_config = json_decode($domain_content, true);
			
			if(isset($domain_config['forward'])) {
				$base_domain = $domain_config['forward'];
				$filename = $base_domain . '.json';
			}else{
				$config = array_merge($config, $domain_config);
				$filename = null;
			}
		}
		
		if(!isset($config['domain'])) {
			$config['domain'] = $domain;
		}
		if(!isset($config['base_domain'])) {
			$config['base_domain'] = $base_domain;
		}
		
		return $config;
	}
	
	/**
	 * Lookup a single key from configuration, if none is specified the loaded
	 * global configuration is used
	 * 
	 * @param unknown $key
	 * @param unknown $config_array
	 */
	function lookup($key, &$config_array = null) {
		global $config;
		if($config_array == null) {
			$config_array = $config;
		}
		return $config_array[$key];
	}
	
	/**
	 * Perform curly braces string replacements in the provided configuration
	 * 
	 * @param unknown $config_array
	 */
	function run_replacements(&$config_array) {
		foreach ($config_array as $key => $value) {
			$needle = '{' . $key . '}';
			foreach ($config_array as $rkey => $rvalue) {
				$config_array[$rkey] = str_replace($needle, $value, $rvalue);
			}
		}
	}
	
	/**
	 * Try to load an HTML fragment for the content div
	 * 
	 * @param unknown $config_array
	 */
	function load_content_fragment(&$config_array = null) {
		global $config;
		if($config_array == null) {
			$config_array = $config;
		}
		if(isset($config['contentfragment']) && file_exists($config['contentfragment'])) {
			$content = file_get_contents($config['contentfragment']);
			return $content;
		}
		return null;
	}
	
	$config = load_configuration();
	run_replacements($config);
	$content = load_content_fragment($config);
	
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
<title><?=$config['title']?></title>

<script
	src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<!-- Bootstrap -->
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet"
	href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
	integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u"
	crossorigin="anonymous">
<!-- Optional theme -->
<link rel="stylesheet"
	href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css"
	integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp"
	crossorigin="anonymous">
<!-- Latest compiled and minified JavaScript -->
<script
	src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
	integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
	crossorigin="anonymous"></script>
<!-- <link href="css/bootstrap.min.css" rel="stylesheet"> -->
<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
	<nav class="navbar navbar-inverse navbar-fixed-top">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed"
					data-toggle="collapse" data-target="#navbar" aria-expanded="false"
					aria-controls="navbar">
					<span class="sr-only">Toggle navigation</span> <span
						class="icon-bar"></span> <span class="icon-bar"></span> <span
						class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="<?=$config['mailtocontent']?>"><?=$config['header']?></a>
			</div>
			<div id="navbar" class="navbar-collapse collapse">
				<form class="navbar-form navbar-right">
					<a class="btn btn-success" href="<?=$config['mailtocontent']?>"><?=$config['headercta']?></a>
				</form>
			</div>
			<!--/.navbar-collapse -->
		</div>
	</nav>

	<div class="jumbotron">
		<div class="container">
			<h1><?=$config['heading']?></h1>
			<p><?=$config['teaser']?></p>
			<p>
				<a id="cta" class="btn btn-primary btn-lg" href="<?=$config['mailtocontent']?>" role="button"><span class="<?=$config['teaserctaclass']?>" style="padding-right:10px;"></span><?=$config['teasercta']?></a>
			</p>
		</div>
	</div>
	
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<script type="text/javascript" src="//cdn.playbuzz.com/widget/feed.js"></script>
				<div class="pb_feed" data-embed-by="a0fc949e-55c3-40fe-b5b9-428b8d440bec" data-item="ce874d2b-f320-4803-8901-300bc1a6891d" data-version="2"></div>
			</div>
		</div>
		<div class="row">
			<?php if($content != null) { ?>
			<div class="col-md-6">
				<?=$content?>
			</div>
			<?php } ?>
			<?php if(isset($config['twitterwidgethref']) && isset($config['twitterwidgetid'])) {?>
			<div class="col-md-6">
				<a class="twitter-timeline" href="<?=$config['twitterwidgethref']?>"
					chrome="noheader"
					data-widget-id="<?=$config['twitterwidgetid']?>"><?=$config['twitterwidgettitle']?></a>
				<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
			</div>
			<?php } ?>
			<?php if(isset($config['googleadwordsclient']) && isset($config['googleadwordsslot'])) { ?>
			<div class="col-md-6">
				<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
				<!-- domain-parking -->
				<ins class="adsbygoogle"
				     style="display:block"
				     data-ad-client="<?=$config['googleadwordsclient']?>"
				     data-ad-slot="<?=$config['googleadwordsslot']?>"
				     data-ad-format="auto"></ins>
				<script>
				(adsbygoogle = window.adsbygoogle || []).push({});
				</script>
			</div>
			<?php } ?>
		</div>
		<?php if(isset($config['contactforminfo'])) { ?>
		<div class="row">
			<div class="col-md-12">
				<?php if(isset($config['contactformheading'])) { ?>
					<h2><?=$config['contactformheading']?></h2>
				<?php } ?>
				<p><?=$config['contactforminfo']?></p>
			</div>
		</div>
		<?php } ?>
		<hr />
		<footer>
			<p>&copy; <?php echo date("Y"); ?> <a href="https://steinbauer.org/about">Matthias Steinbauer</a></p>
		</footer>
	</div>

	<?php if(isset($config['googleanalytics'])) { ?>
	<!-- Google Analytics -->
	<script>
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
	
	  ga('create', '<?=$config['googleanalytics']?>', 'auto');
	  ga('send', 'pageview');
	
	</script>
	<?php } ?>
</body>
</html>
