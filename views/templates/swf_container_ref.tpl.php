<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title><?=$label?></title>
	
	<!-- LIB -->
	<script type="text/javascript" src="<?=$ctx_taobase_www?>js/jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="<?=$ctx_taobase_www?>js/jquery-ui-1.8.4.custom.min.js"></script>
	<script type="text/javascript" src="<?=$ctx_taobase_www?>js/json.min.js"></script>
	<script type="text/javascript">
		var root_url = "<?=$ctx_root_url?>";
	</script>
	
	<!-- JS REQUIRED -->
	<?if(!$ctx_raw_preview):?>
	<script type="text/javascript" src="<?=$ctx_root_url?>/wfEngine/views/js/wfApi/wfApi.min.js"></script>
	<?endif?>
	<script type="text/javascript" src="<?=$ctx_base_www?>js/taoApi/taoApi.min.js"></script>
	<script type="text/javascript" src="<?=$ctx_base_www?>js/taoMatching/taoMatching.min.js"></script>
</head>
<body>
	<div class="swf_item">
		<object 
			id="tao_item" 
			classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" 
			codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" 
			width="700" 
			height="550"  
			align="middle">
				<param name="allowScriptAccess" value="sameDomain" />
				<param name="movie" value="<?=$runtime?>?localXmlFile=<?=$contentUrl?>&instance=<?=$uri?>" />
				<param name="quality" value="high" />
				<param name="bgcolor" value="#ffffff" />
				<param name="wmode" value="transparent" />
				<embed 
					src="<?=$runtime?>?localXmlFile=<?=$contentUrl?>&instance=<?=$uri?>" 
					quality="high" 
					bgcolor="#ffffff"  
					width="700" 
					height="550" 
					align="middle" 
					wmode="transparent"
					allowScriptAccess="sameDomain" 
					type="application/x-shockwave-flash" 
					pluginspage="http://www.macromedia.com/go/getflashplayer"
				 />
		</object>
	</div>
	
</body>
</html>