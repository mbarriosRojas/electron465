
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  
  <title><?php echo __TITLE__ ?></title>
  <link href="<?php echo __CSS__ ?>__style.css.php?url=<?php echo __LOCALWWW__ ?>" rel="stylesheet" type="text/css" />
	<link type="text/css" href="<?php echo __CSS__ ?>/ui-lightness/jquery-ui-1.8.6.custom.css" rel="stylesheet" />
	
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>jquery-ui-1.8.6.custom.min.js"></script>

  </head>
<body  style="background-color: #FFFACD;">
<center>
  <div id="cabecera">
    <!-- 
      --------------------------------------------------------
        Inicio del Menu Superior      
      --------------------------------------------------------
    --> <?php echo $Menu;?>
  </div>


  <div id="medio" > 
    <table style="width: 800px" border=0>
      <tr>
        <td style="width: 20px">
    
        </td>
        <td>
          
          <table border=0  >
            <tr >
              <td style="width :700px" align="left">              
              <br><br>
              <h2>Control de ventas generales</h2>
              <br><br>	
            	<?php echo $Contenido;?>
            	
								
              </td>
            </tr>
          </table>
        	
        
        
        </td>
      </tr>
    </table>
    <br>

  </div>
  




  <!-- 
    --------------------------------------------------------
      Inicio Menu inferior      
    --------------------------------------------------------
  --> 



</center>
</body>
</html>
