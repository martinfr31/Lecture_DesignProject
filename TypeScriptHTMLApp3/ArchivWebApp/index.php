<!DOCTYPE html>
<!--[if IEMobile 7 ]>    <html class="no-js iem7"> <![endif]-->
<!--[if (gt IEMobile 7)|!(IEMobile)]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <title>Energy Home</title>

        <meta name="description" content="">
        <meta name="HandheldFriendly" content="True">
        <meta name="MobileOptimized" content="320">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="cleartype" content="on">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-title" content="Energy Home">
        <meta name="viewport" content="user-scalable=1.0,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0">
		    <meta name="format-detection" content="telephone=no">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="mobile-web-app-capable" content="yes">
        <link rel="shortcut icon" sizes="196x196" href="img/contact2.jpg">
		    <link rel="apple-touch-icon" href="img/contact2.jpg" sizes="120x120">

		<!-- iPad, portrait -->
		<link href="img/splash3.png"
      		media="(device-width: 768px) and (device-height: 1024px)
         	and (orientation: portrait)
         	and (-webkit-device-pixel-ratio: 1)"
      		rel="apple-touch-startup-image">

		<!-- iPhone 5 -->
		<link href="img/splash2.png"
      		media="(device-width: 320px) and (device-height: 568px)
         	and (-webkit-device-pixel-ratio: 2)"
      		rel="apple-touch-startup-image">

		<!-- iPhone -->
		<link href="img/splash.png"
      		media="(device-width: 320px) and (device-height: 480px)
         	and (-webkit-device-pixel-ratio: 1)"
      		rel="apple-touch-startup-image">


		<link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.css" />
		<link rel="stylesheet" href="css/add2home.css">
    <link rel="stylesheet" href="normalize.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/styles.css">


  		<script type="application/javascript" src="js/canvasjs.min.js"></script>
		<script type="application/javascript" src="js/iscroll.js"></script>
		<script type="application/javascript" src="js/add2home.js"></script>
		<script type="application/javascript" src="js/addTo.js"></script>

		<script type="text/javascript">
			var myScroll;
			function loaded() {
				myScroll = new iScroll('wrapper', { scrollbarClass: 'myScrollbar' });
			}
			document.addEventListener('DOMContentLoaded', loaded, false);
		</script>
		<script src="http://code.jquery.com/jquery-2.0.2.js"></script>
		<script src="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.js"></script>
		<script>
			$(document).ready(function () {
        		$('#popupBasic2').load('detailsmodul.php?id=999999');
        		$("#popupBasic2").popup("close");

        		var currentdate = new Date();
        		var d = currentdate.getDate();
    			var m = currentdate.getMonth() + 1;
    			var y = currentdate.getFullYear();
    			var h = currentdate.getHours();
    			var mm = currentdate.getMinutes();
    			var s = currentdate.getSeconds();
    			var datetime = '' + (d <= 9 ? '0' + d : d)+'.'+(m<=9 ? '0' + m : m)+'.'+y+' '+ (h <= 9 ? '0' + h : h)+':'+(mm<=9 ? '0' + mm : mm)+':'+(s<=9 ? '0' + s : s);

        		var winW = 630, winH = 460;
				if (document.body && document.body.offsetWidth) {
 					winW = document.body.offsetWidth;
 					winH = document.body.offsetHeight;
				}
				if (document.compatMode=='CSS1Compat' &&
    				document.documentElement &&
    				document.documentElement.offsetWidth ) {
 					winW = document.documentElement.offsetWidth;
 					winH = document.documentElement.offsetHeight;
				}
				if (window.innerWidth && window.innerHeight) {
 					winW = window.innerWidth;
 					winH = window.innerHeight;
				}


    			var point = "Mobil";
    			if(location.href.indexOf("192") !== -1) {
    				point = "Local";
    			}
        		$.post('http://'+location.host+'/write.php?text="'+datetime+' WebApp: Seiten Aufruf ('+winW+'x'+winH+' - '+point+')"');
    });
		</script>
    </head>
    <body unselectable="on"  class="unselectable" style="background: #33B5E5;background-image: linear-gradient(to bottom, white 100%, white 100%);">



		<script>
   	 		$(document).on('touchmove',function(e){
  					e.preventDefault();
				});
				$('body').on('touchstart','.scrollable',function(e) {
  					if (e.currentTarget.scrollTop === 0) {
   		 				e.currentTarget.scrollTop = 1;
  					} else if (e.currentTarget.scrollHeight === e.currentTarget.scrollTop + e.currentTarget.offsetHeight) {
   	 					e.currentTarget.scrollTop -= 1;
  					}
				});
				$('body').on('touchmove','.scrollable',function(e) {
  				e.stopPropagation();
			});
		</script>

  <script>
			var int=self.setInterval(function(){clock()},3000);
			function clock() {
				$('#energie').load('items.php');
			}
		</script>

		<script src="/js/knockout-2.2.1.js"></script>
		<script src="/js/globalize.min.js"></script>
		<script src="/js/dx.chartjs.js"></script>

		<script src="/js/dx.chartjs.js"></script>


		<script>
			$(document).on('click', 'a', function(event, ui) {
				var data_id = $(this).data('id');


    			$('#popupBasic2').load('detailsmodul.php?id='+data_id);



        		var currentdate = new Date();
        		var d = currentdate.getDate();
    			var m = currentdate.getMonth() + 1;
    			var y = currentdate.getFullYear();
    			var h = currentdate.getHours();
    			var mm = currentdate.getMinutes();
    			var s = currentdate.getSeconds();
    			var datetime = '' + (d <= 9 ? '0' + d : d)+'.'+(m<=9 ? '0' + m : m)+'.'+y+' '+ (h <= 9 ? '0' + h : h)+':'+(mm<=9 ? '0' + mm : mm)+':'+(s<=9 ? '0' + s : s);

   				if (data_id == "999999") {
        		$.post('http://'+location.host+'/write.php?text="---- '+datetime+' WebApp: Gesamt-Details Aufruf"');
   				} else {
        		$.post('http://'+location.host+'/write.php?text="---- '+datetime+' WebApp: Appliance-Details Aufruf"');
   				}
   				return true;
    		});
		</script>

       	<div data-role="popup" id="popupBasic" data-history="false" data-position-to="window" data-transition="slideup" data-corners="false" data-overlay-theme="a">
       		<?php
					  include("details.php");
			?>
		</div>

		<div data-role="popup" id="popupBasic2" data-history="false" data-position-to="window" data-transition="slide" data-corners="false" data-overlay-theme="a" style="max-height: 455px; height: 455px; ">

		</div>

  	    <div id="headcontainer" class="gamesheadcontainer">
		  	  <div id="head" style="padding-top:0.8em" height="80">
				  <div id="headmiddle" style="float:left; padding-left:15%" class="blue"  unselectable="on"><div class="unselectable">Energy Monitor</div></div>
		 		  <div style="width=35%; float:right;"><a style="color: inherit; text-decoration: none;" href="#popupBasic" data-rel="popup"><div height="60" width="60"><img src="img/info.png" height="30" width="30" style="vertical-align: middle;padding-right:0.5em;"/></div></a></div>
		 	  </div>
		  </div>

		  <div id="wrapper" style="background: #FFFFFF">
			  <div  id="energie"  id="scroller" style="background: #FFFFFF">
				  <?php
					  include("items.php");
				  ?>
			  </div>
		  </div>

    </body>
</html>

