<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo __TITLE__ ?></title>
	<link href="<?php echo __CSS__ ?>__style.css.php?url=<?php echo __LOCALWWW__ ?>" rel="stylesheet" type="text/css">
	<link href="<?php echo __CSS__ ?>TGrid.css" rel="stylesheet" type="text/css">
	<link href="<?php echo __CSS__ ?>ui-lightness/jquery-ui-1.8.6.custom.css" rel="stylesheet"  type="text/css">
	<link href="<?php echo __CSS__ ?>chat.css" type="text/css" rel="stylesheet" />
	<link href="<?php echo __CSS__ ?>screen.css" type="text/css" rel="stylesheet"/>
		
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>nomina/TGrid.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>jquery-ui-1.8.6.custom.min.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>nomina/chat.js"></script>
	

	 
	<script type="text/javascript">
		$(function(){
			$("#conectados_chat" ).accordion({
				collapsible: true,
				active: 2
			});
		});
		
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
	</script>
		

	</head>
<body style="background-color: #FFFACD;" >
<center>
	<div id="cabecera"><?php echo $Menu;?></div>
	<div id="medio" >	
		
		<table border=1>
			<tr>
				<td style="width: 20px;">&nbsp;</td>
				<td><br><br><br>
				<h2> Buzon de Cr&eacute;ditos Por Liberar | <?php echo '<a href="' . base_url() . 'index.php/cooperativa/Sugerencia" border=0>Agregar Sugerencia</a>';?></h2><br></td>
				</tr><tr>
				<td></td><td align="left">
				<div id='divListar'>
				
							
				</div> </td>	</tr></table>
				<br><br>
	</div>




	<!-- 
		--------------------------------------------------------
			Inicio Menu inferior			
		--------------------------------------------------------
  --> 


	<div id="bottom" align="left">
	
	
		
	</div>
</center>
</body>
</html>
