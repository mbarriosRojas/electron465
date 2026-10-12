
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	
	<title><?php echo __TITLE__ ?></title>
	<link href="<?php echo __CSS__ ?>__style.css.php?url=<?php echo __LOCALWWW__ ?>" rel="stylesheet" type="text/css" />	
	<link type="text/css" href="<?php echo __CSS__ ?>/ui-lightness/jquery-ui-1.8.6.custom.css" rel="stylesheet" />	
	<link type="text/css" rel="stylesheet" media="all" href="<?php echo __CSS__ ?>chat.css" />
	<link type="text/css" rel="stylesheet" media="all" href="<?php echo __CSS__ ?>screen.css" />
	
	
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>jquery-ui-1.8.6.custom.min.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>nomina/chat.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>nomina/jquery.js"></script>
	
	<script type="text/javascript">
		$(document).ready(function(){
			originalTitle = document.title;
			startChatSession("<?php echo __LOCALWWW__ ?>");

			$([window, document]).blur(function(){
				windowFocus = false;
			}).focus(function(){
				windowFocus = true;
				document.title = originalTitle;
			});
		});
		
		$(document).ready(function(){
			alert("judelvis");
		});

	</script>	

	</head>
<body style="background-color: #FFFACD;" >
<center>
	<!-- 
			Cuerpo del documento para el datatable 
			Componetes de YUI Loader
	-->
	<div id="cabecera" >
		<!-- 
			--------------------------------------------------------
				Inicio del Menu Superior			
			--------------------------------------------------------
	  --> <?php echo $Menu;?>
	</div>


	<div id="medio" >	
		<br><br><br>
		<?php echo $Conectados;?>
		
		
		<br><br>
		
	</div>


</center>
</body>
</html>
