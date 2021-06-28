<!doctype html>
<html>
    <head>
	<title>ODV-online</title>
	<link rel="stylesheet" href="{{ $resourceRoot.'css/odv-online.min.css'.$version_js_css }}">
	<link rel="shortcut icon" type="image/gif" href="{{ session('resourceRoot').'img/odv-icon.gif' }}">
    </head>
    
    <body>
	<!-- <noscript>Your browser does not support JavaScript!</noscript> -->

	<div id="MainMenuBar" >
	    <ul id="MainMenu">
	    </ul>
	</div> <!-- MainMenuBar -->

	<div id="PopupBckgrd">
	</div> <!-- PopupBckgrd -->

	<div id="GridContainer">

	    <div id="ControlBlock">
	    </div> <!-- ControlBlock -->

	    <div id="TitleBar">
	    </div>
	    
	    <div id="CanvasContainer">
		<img id="ImageArea">
		<canvas id="CanvasArea"></canvas>
	    </div> <!-- CanvasContainer -->

	    <div id="CurrStatListTitleBar">
	    </div>

	    <div id="CurrStatList">
	    </div>

	    <div id="CurrSmplListTitleBar">
	    </div>

	    <div id="CurrSmplList">
	    </div>

	    <div id="CurrIsoVarListTitleBar">
		<div id="CurrIsoVarListTitleBarInfoPane" class="LbPane"></div>
	    </div>

	    <div id="CurrIsoVarList">
	    </div>

	    <div id="StatusBar">
	    </div>
	    
	</div><!-- GridContainer -->

	<script type="text/javascript" src="{{ $resourceRoot.'js/odv-online.min.js'.$version_js_css }}"></script>
    </body>
</html>
