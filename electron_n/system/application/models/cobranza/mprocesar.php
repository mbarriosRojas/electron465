<?php

/**
 * Procesar Archivos (Txt) por bancos incluye C++
 *
 *
 * Creado Por: Carlos Pena, Judelvis Rivas
 * Elaborado : 07/05/2013
 *
 * @copyright GenProg (Generacion de Programadores)
 * @author Crash
 *
 */
class MProcesar extends Model {

  /**
   * Tabla de la Base de Datos
   *
   * @param oid(int): Identificador Auto Incrementado
   * @param nmbr(char)(255): Nombre
   * @param desc(md5)(255): Descripcion
   * @param esta(int): Estatus
   * @param auto(char)(255):Autor
   * @param lina(char)(255):Linaje
   * @param crea(datetime): Fecha
   * @param tam(char)(255): Tamano del Archivo md5sum linux
   */
  protected $tbl = 'cpp_banco';

  /**
   * Generar Archivos de Texto
   * Elaborado Por: Carlos Pena
   * Fecha: 04/05/2013
   *
   * Enlace directo a C++, Crear Formato txt Basado en el intructivo de Bicentenario
   * Inserta linea para el archivo en tabla ref:($tbl) como control y seguridad para
   * cada uno de ellos genera un txt en la carpeta raiz (/tmp) que sera usado como repositorio asociados
   * a un indetificador encriptado en MD5() por seguridad
   *
   * @param Arreglo Con metodo Post
   *
   * @return string (.txt)
   */
  function Gtxt($param = array()) {
    // Banco (Bicentenario)
    $sBnc = $param['banco'];
    // Empresa ( 0: Cooperativa 1: Grupo)
    $sEmp = $param['empresa'];
    // Fecha (YYYY-MM)
    $sFec = $param['fecha'];
    //Periocidad
    $sPer = $param['periodicidad'];
    // Forma de Contrato
    $sFco = $param['fcontrato'];
    //Fecha Actual
    $sFac = date('Ymd');
    //Nominas
    $sNom = $param['nomina'];
    //Picar Archibo
    $sPic = $param['picar'];
    


    //Describe parametros de ejecucion y nombre del archivo
    $sPar = strtoupper("\"" . $sBnc . "\" " . $sEmp . " " . $sFec . " " . $sPer . " " . $sFco . " " . $sFac . " \"" . $sNom . "\"");

    $md5Nom = md5($sPar);

    $tipo_archivo = $param['tipo_archivo'];
    $sUsu = $_SESSION['usuario'];
    
    $sQuery = 'DELETE FROM ' . $this->tbl . ' WHERE  nmbr=\'' . $sPar . '\' AND esta = 1 AND tarc = \'' .$tipo_archivo . '\' LIMIT 1;';
    $Consulta = $this->db->query($sQuery);
    
    //Sin claupsula
    $sQuery = 'SELECT * FROM ' . $this->tbl . ' WHERE  nmbr=\'' . $sPar . '\' AND esta = 1 AND tarc = \'' .$tipo_archivo . '\';';
    $Consulta = $this->db->query($sQuery);
    if ($Consulta->num_rows() == 0) {    
   
      $afiliacion = '';
      if ($tipo_archivo == 'A') {
        $afiliacion = 'afiliacion/';
      }else if($tipo_archivo == 'C'){
        $afiliacion = 'cobranza/';
      }

      //$sEnl = '<a href="' . __LOCALWWW__ . '/tmp/' . $afiliacion . $md5Nom . '.txt" target="_blank">+ Descargar</a>';
      $ejecutar = exec("./system/repository/grupo/src/Gtxt " . $sPar . " '" . $md5Nom . "' '" . $tipo_archivo . "' " . $sPic . " 2>&1");
      

      $val = exec("md5sum tmp/" . $afiliacion . $md5Nom . ".txt");
      $ex = explode(" ", $val);
      $ejec = explode(";", $ejecutar);

      //Peso del Archivo
      $sInsert = 'INSERT INTO ' . $this->tbl . ' (`oid`,`nmbr`,`desc`,`esta`,`auto`,`lina`,`tama`,`empr`,`mes`,
			`peri`,`fcon`,`fact`,`nomi`,`cant`,`mont`,`tarc`) 
			VALUES (\'NULL\',\'' . $sPar . '\',\'' . $md5Nom . '\',1,\'' . $sUsu . '\', \'' . strtoupper($sBnc) . 
              '\',\'' . $ex[0] . '\',' . $sEmp . ',\'' . $sFec . '\',' . $sPer . ',' . $sFco . ',\'' . $sFac . '\',\'' . $sNom . '\',\'' . $ejec[1] . '\',\'' . $ejec[2] . '\',\'' . $tipo_archivo . '\');';
      //echo $sInsert;
      $this->db->query($sInsert);
      $sEnl = '<br><br><center><h2>El Archivo se Proceso con exito</h2> </center><br><br>
			<h3>Cantidad de personas:<b>' . $ejec[1] . '</b></h3><br><h3>Monto Total del Archivo:<b>' . $ejec[2] . '</b></h3><br><br>
			<a href="' . __LOCALWWW__ . '/tmp/' . $afiliacion . $md5Nom . '.txt" target="_blank">+ Descargar</a></h1></center>';
  
    } else {
      $afiliacion = '';
      if ($tipo_archivo == 'A') {
        $afiliacion = 'afiliacion/';
      }else if($tipo_archivo == 'C'){
        $afiliacion = 'cobranza/';
      }
      $sEnl = '<br><br><center><h2>El Archivo ya fue Procesado con exito <br><br></h2>
			<a href="' . __LOCALWWW__ . '/tmp/' . $afiliacion . $md5Nom . '.txt" target="_blank">+ Descargar</a></h1></center>';
    }
   
    
    return $sEnl;
  }

  /**
   * Leer Archivos de Texto
   * Elaborado Por: Judelvis Rivas
   * Fecha: 08/05/2013
   *
   * Enlace directo a C++, Parametros del Arreglo en minusculas, linajes (bicentenario,provincial,fondo comun)
   * Recorre los Archivos Linea Por linea e Identifica si el archivo posee forma conocida
   * hasta la actualidad Bicentenario Posee 3 Modelos Diferentes de Lecturas
   * Provincial 1 Modelo
   * Fondo Comun 1 Modelo
   *
   * @param array (Nombre del Archivo | Nombre del Banco)
   *
   * @return Json TGrid
   */
  function Ltxt($arr = array()) {
    return exec("./system/repository/grupo/src/Ltxt \"" . $arr['archivo'] . "\" \"" . strtolower($arr['banco']) . "\" \"" . strtolower($arr['tipo_archivo']) . "\" 2>&1");
  }

  /**
   * Listar los archivos pendientes por procesar
   *
   * Elaborado Por: Carlos Pena
   * Fecha: 11/05/2013
   *
   * @param Valor de Decision 1: Por Enviar 2: Enviados
   *
   */
  function Vtxt($iVal) {

    /**
     * 
     * Previo a el :
     * INSERT IGNORE INTO cpp_total( cert, suma ) SELECT mes, sum( monto ) FROM t_lista_cobros WHERE mes LIKE 'CERT%' GROUP BY mes
     * 
     * 
     */
    $sCon = '(';
    $sDependiente = '(';
    $sOr = '';
    $sMt = '';
    $lst = $this->session->userdata('lista_linaje');

    $i = 0;
    //Validar Linaje
    foreach ($lst as $sC => $sV) {
      if (trim($sV['valor']) != '') {
        ++$i;
        if ($i > 1) {
          $sOr = 'OR ';
        }
        $sCon .= $sOr . $this->tbl . '.lina = \'' . strtoupper($sV['valor']) . '\' ';
      }
    }
    if ($i > 0) {
      $sCon .= ')';
    } else {
      $sCon = '';
    }
    $sSet = '*,' . $this->tbl . '.nmbr, t_periodicidad.nmbr AS periodo,  t_formacontrato.nmbr AS forma';
    $sQuery = 'SELECT ' . $sSet . ', cpp_total.suma AS monto_total FROM ' . $this->tbl . ' INNER JOIN t_periodicidad 
		ON ' . $this->tbl . '.peri=t_periodicidad.oid INNER JOIN t_formacontrato 
		ON ' . $this->tbl . '.fcon=t_formacontrato.oid 	
		LEFT JOIN cpp_total ON cpp_total.cert like CONCAT(\'%\',cpp_banco.tama)	
		WHERE ' . $sCon . ' AND ' . $this->tbl . '.esta=' . $iVal . ' AND ' . $this->tbl . '.tarc != \'A\' 
    GROUP BY cpp_banco.tama ORDER BY ' . $this->tbl . '.crea DESC LIMIT 200;';
		


    $oCabezera[1] = array("titulo" => "oid", "atributos" => "width:10px", "oculto" => 1);
    $oCabezera[2] = array("titulo" => "Nomina Pendiente", "atributos" => "width:300px", "buscar" => 1);
    $oCabezera[3] = array("titulo" => "Certificado", "atributos" => "width:100px", "oculto" => 1);
    $oCabezera[4] = array("titulo" => "linaje", "atributos" => "width:100px");
    $oCabezera[5] = array("titulo" => "empresa", "atributos" => "width:80px");
    $oCabezera[6] = array("titulo" => "periodicidad", "atributos" => "width:80px");
    $oCabezera[7] = array("titulo" => "F. Contrato", "atributos" => "width:80px");
    $oCabezera[8] = array("titulo" => "Creado", "atributos" => "width:90px;", "buscar" => 1);
    $oCabezera[9] = array("titulo" => "Cantidad", "atributos" => "width:90px;text-align: center", "buscar" => 1);
    $oCabezera[10] = array("titulo" => "ENVIADO", "atributos" => "width:90px;text-align: left");

    if ($iVal != 2) {
      $oCabezera[11] = array("titulo" => "ACC", //
        "atributos" => "width:20px", //
        "tipo" => "enlace", //
        "metodo" => 1, //
        "ruta" => __IMG__ . "botones/aceptar1.png", //
        "funcion" => 'MDiv', //
        "atributos" => "width:20px", "parametro" => "3,4,2,8", //
        "target" => "_blank", //
      );
    } else {

      $oCabezera[11] = array("titulo" => "COBRADO", "atributos" => "width:90px;text-align: left");
    }

    $rsC = $this->db->query($sQuery);
    $i = 0;

    foreach ($rsC->result() as $row) {
      $sEmp = 'COOPERATIVA';
      if ($row->empr != 0) {
        $sEmp = 'GRUPO';
      }
      if ($iVal != 2) {
        $sMt = "";
      } else {
        $sMt = number_format($row->monto_total, 2, ",", ".") . " Bs.";
      }
      ++$i;
      $ofil[$i] = array("1" => $row->oid, //
        "2" => $row->nomi, //
        "3" => $row->tama, //
        "4" => $row->lina, //
        "5" => $sEmp, //
        "6" => $row->periodo, //
        "7" => $row->forma, //
        "8" => $row->crea, //
        "9" => $row->cant, //
        "10" => number_format($row->mont, 2, ",", ".") . " Bs.", //
        "11" => $sMt //
      );
    }
    $oTable = array("Cabezera" => $oCabezera, "Cuerpo" => $ofil, "Paginador" => 40, "Origen" => "json");

    return json_encode($oTable);
  }

  /**
   * Evaluar Archivos de Generacion y Lectura de Txt
   *
   * Elaborado Por: Carlos Pena
   * Fecha: 11/05/2013
   *
   * Array (txt = Archivo Cargado, clv = md5sum(Archivo Enviado) )
   */
  function Ptxt($arr = array()) {
    $sQuery = "SELECT A.cedula, A.nombre, A.crea, A.procesada,A.fech,t_control_pagos.contrato_id, t_control_pagos.monto_cuota,
			t_control_pagos.monto,t_control_pagos.resta, A.abono FROM t_control_pagos JOIN ";
    $sQuery += "(SELECT cedula,SUM(monto) AS abono,CONCAT(primer_nombre,' ',segundo_nombre,' ',primer_apellido,' ',segundo_apellido) 
			AS nombre, cpp_archivo.crea,t_cargar_txt.procesada,cpp_archivo.fech,t_cargar_txt.monto FROM t_cargar_txt 
			JOIN t_personas ON t_personas.documento_id=t_cargar_txt.cedula 
			JOIN cpp_archivo ON cpp_archivo.nmbr = t_cargar_txt.archivo 
			WHERE archivo='" + $arr['txt'] + "' GROUP BY cedula) AS A ON A.cedula=t_control_pagos.documento_id 
			JOIN cpp_banco ON t_control_pagos.cobrado_en=cpp_banco.lina AND t_control_pagos.empresa=cpp_banco.empr 
			AND  t_control_pagos.periocidad=cpp_banco.peri AND t_control_pagos.forma_contrato=cpp_banco.fcon 
			AND t_control_pagos.nomina_procedencia=cpp_banco.nomi 
			WHERE cpp_banco.tama='" . $arr['clv'] . "';";
  }

  /**
   * Comparar Archivo de Generacion y Carga e Insertarlo
   *
   * Elaborado Por: Carlos Pena
   * Fecha: 11/05/2013
   *
   * Insercion directa a Mysql Bajo normas de generacion (Gtxt | Ltxt)
   * Array (txt = Archivo Cargado, clv = md5sum(Archivo Enviado) )
   */
  function Itxt($arr = array()) {
  	$sInsert = "INSERT INTO t_lista_cobros (documento_id,credito_id,mes,descripcion,fecha,monto,usua,farc,mesp,anop,moda) ";
  	$usuario = $this->session->userdata ( 'oidu' );
  	$fecha = explode("-", $arr['mesCobro']);
  	
  	
  	if($arr['banco'] == 'BICENTENARIO'){
  		$sQuery = $sInsert . "SELECT t_cargar_txt.cedula,t_clientes_creditos.contrato_id, 'CERT: " . $arr['clv'] . "','PROCESADO MEDIANTE LOTE',
		 '" . $arr['mesCobro'] . "', SUM(t_cargar_txt.monto),'".$usuario."','" . $arr['fecha'] . "','" . $fecha[1] .
		 "','" . $fecha[0] . "','9'  FROM `t_cargar_txt`,t_clientes_creditos WHERE 
		 t_cargar_txt.credito_id=t_clientes_creditos.credito_id AND t_cargar_txt.archivo='" . $arr['txt'] . "' GROUP BY t_cargar_txt.credito_id";
  	}else {  		
  		$sQuery = $sInsert . "SELECT A.cedula,t_control_pagos.contrato_id, CONCAT('CERT: ',cpp_banco.tama),'PROCESADO MEDIANTE LOTE',
		 A.fech, A.abono,'".$usuario."',A.fech,month(A.fech),year(A.fech),'9' FROM t_control_pagos JOIN
		 (SELECT cedula,SUM(monto) AS abono,
			cpp_archivo.crea,t_cargar_txt.procesada,cpp_archivo.fech,t_cargar_txt.monto FROM t_cargar_txt
			JOIN t_personas ON t_personas.documento_id=t_cargar_txt.cedula
			JOIN cpp_archivo ON cpp_archivo.nmbr = t_cargar_txt.archivo
			WHERE archivo='" . $arr['txt'] . "' GROUP BY cedula) AS A ON A.cedula=t_control_pagos.documento_id
			JOIN cpp_banco ON t_control_pagos.cobrado_en=cpp_banco.lina AND t_control_pagos.empresa=cpp_banco.empr
			AND  t_control_pagos.periocidad=cpp_banco.peri AND t_control_pagos.forma_contrato=cpp_banco.fcon
			WHERE cpp_banco.tama='" . $arr['clv'] . "' AND cpp_banco.esta != 2 AND t_control_pagos.resta > 0;";
  	}
  	

    //echo $sQuery.'--'.$arr['banco'];
    
    $this->db->query($sQuery);
    $sQuery = "UPDATE cpp_banco SET esta=2 WHERE tama='" . $arr['clv'] . "' LIMIT 1;";
    $this->db->query($sQuery);
    $sQuery = "UPDATE cpp_archivo SET esta=2 WHERE nmbr='" . $arr['txt'] . "' LIMIT 1;";
    $this->db->query($sQuery);
    
    $sQuery = "UPDATE t_cargar_txt SET procesada=1 WHERE archivo='" . $arr['txt'] . "';";
    $this->db->query($sQuery);
    
    echo "FINALIZO EL PROCESO CON EXITO";
    
  }

}
?>

