<?include('header.tpl')?>

<div id="preview-container" class="ui-corner-all">
	<?if(get_data('preview')):?>
	<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" width="800" height="600" id="tao_item" align="middle">
		<param name="allowScriptAccess" value="sameDomain" />
		<param name="movie" value="<?=get_data('swf')?>?localXmlFile=<?=get_data('dataPreview')?>&xml=<?=get_data('dataPreview')?>" />
		<param name="quality" value="high" />
		<param name="bgcolor" value="#ffffff" />
		<embed src="<?=get_data('swf')?>?localXmlFile=<?=get_data('dataPreview')?>&xml=<?=get_data('dataPreview')?>" quality="high" bgcolor="#ffffff" width="800" height="600" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
	</object>
	<?else:?>
		<?=__('PREVIEW BOX')?>
	<?endif?>
</div>
<div class="main-container">
	<div id="form-title" class="ui-widget-header ui-corner-top ui-state-default">
		<?=get_data('formTitle')?>
	</div>
	<div id="form-container" class="ui-widget-content ui-corner-bottom ui-state-default">
		<?=get_data('myForm')?>
	</div>
</div>

<?include('footer.tpl');?>
