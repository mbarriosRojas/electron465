<?php
/**
 * Precio del dólar: dos tipos independientes (BCV y COBRANZA) + bitácora histórica de cada uno.
 *
 * Todos los métodos aceptan un parámetro $tipo que por defecto es TIPO_BCV, para no romper
 * a los consumidores existentes (panel, generadores de TXT/XLS de cobranza, carga Venezuela)
 * que hoy solo conocen la tasa BCV.
 */
class MPrecio_dolar extends Model {

	const TIPO_BCV = 'BCV';
	const TIPO_COBRANZA = 'COBRANZA';

	/** '' (vacío) = "General" — tasa histórica sin banco asociado, o registro
	 * manual que aplica a cualquier banco que no tenga su propia tasa ese día. */
	const BANCO_GENERAL = '';

	private $tbl = 't_precio_dolar';
	private $tblBitacora = 't_precio_dolar_bitacora';

	public function __construct() {
		parent::__construct();
		$this->ensureTable();
	}

	public function tiposValidos() {
		return array(self::TIPO_BCV, self::TIPO_COBRANZA);
	}

	public function bancosValidos() {
		return array('VENEZUELA', 'BICENTENARIO', 'BANFAN');
	}

	private function normalizarTipo($tipo) {
		$tipo = strtoupper(trim((string) $tipo));
		return in_array($tipo, $this->tiposValidos(), true) ? $tipo : self::TIPO_BCV;
	}

	/**
	 * '' = General (aplica a cualquier banco sin tasa propia ese día). Un
	 * valor no reconocido también cae en General, nunca en un banco distinto
	 * al pedido.
	 */
	private function normalizarBanco($banco) {
		$banco = strtoupper(trim((string) $banco));
		return in_array($banco, $this->bancosValidos(), true) ? $banco : self::BANCO_GENERAL;
	}

	public function ensureTable() {
		$sql = "CREATE TABLE IF NOT EXISTS `{$this->tbl}` (
			`oid` INT(11) NOT NULL AUTO_INCREMENT,
			`fecha` DATE NOT NULL,
			`tipo` VARCHAR(16) NOT NULL DEFAULT 'BCV',
			`banco` VARCHAR(32) NOT NULL DEFAULT '',
			`precio` DECIMAL(18,4) NOT NULL DEFAULT 0.0000,
			`activo` TINYINT(1) NOT NULL DEFAULT 0,
			`usuario` VARCHAR(64) NOT NULL DEFAULT '',
			`fecha_registro` DATETIME NOT NULL,
			`fecha_actualizacion` DATETIME NOT NULL,
			PRIMARY KEY (`oid`),
			UNIQUE KEY `uk_precio_dolar_fecha_tipo_banco` (`fecha`,`tipo`,`banco`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8";
		$this->db->query($sql);

		$sqlBit = "CREATE TABLE IF NOT EXISTS `{$this->tblBitacora}` (
			`oid` INT(11) NOT NULL AUTO_INCREMENT,
			`fecha` DATE NOT NULL,
			`tipo` VARCHAR(16) NOT NULL DEFAULT 'BCV',
			`banco` VARCHAR(32) NOT NULL DEFAULT '',
			`precio` DECIMAL(18,4) NOT NULL DEFAULT 0.0000,
			`usuario` VARCHAR(64) NOT NULL DEFAULT '',
			`fecha_registro` DATETIME NOT NULL,
			`activo` TINYINT(1) NOT NULL DEFAULT 1,
			PRIMARY KEY (`oid`),
			KEY `idx_bitacora_fecha` (`fecha`),
			KEY `idx_bitacora_registro` (`fecha_registro`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8";
		$this->db->query($sqlBit);

		$this->ensureTipoColumna();
		$this->ensureBancoColumna();
		$this->migrarHistorialABitacora();
	}

	/**
	 * Migración defensiva para instalaciones previas a la existencia de la columna `tipo`
	 * (todo lo registrado hasta ahora era, de hecho, tasa BCV).
	 */
	private function ensureTipoColumna() {
		if ($this->db->table_exists($this->tbl)) {
			if (!$this->db->field_exists('tipo', $this->tbl)) {
				$this->db->query(
					"ALTER TABLE `{$this->tbl}` ADD COLUMN `tipo` VARCHAR(16) NOT NULL DEFAULT '" . self::TIPO_BCV . "' AFTER `fecha`"
				);
			}
			if ($this->indiceExiste($this->tbl, 'uk_precio_dolar_fecha')) {
				$this->db->query("ALTER TABLE `{$this->tbl}` DROP INDEX `uk_precio_dolar_fecha`");
			}
			if (!$this->indiceExiste($this->tbl, 'uk_precio_dolar_fecha_tipo')
				&& !$this->indiceExiste($this->tbl, 'uk_precio_dolar_fecha_tipo_banco')) {
				$this->db->query(
					"ALTER TABLE `{$this->tbl}` ADD UNIQUE KEY `uk_precio_dolar_fecha_tipo` (`fecha`,`tipo`)"
				);
			}
		}
		if ($this->db->table_exists($this->tblBitacora) && !$this->db->field_exists('tipo', $this->tblBitacora)) {
			$this->db->query(
				"ALTER TABLE `{$this->tblBitacora}` ADD COLUMN `tipo` VARCHAR(16) NOT NULL DEFAULT '" . self::TIPO_BCV . "' AFTER `fecha`"
			);
		}
	}

	/**
	 * Migración: agrega `banco` a instalaciones existentes ('' = General,
	 * todo lo histórico queda ahí sin reasignar — se usa como respaldo
	 * cuando se busca la tasa de un banco específico y no existe una propia
	 * para esa fecha). Reemplaza el índice único (fecha,tipo) por
	 * (fecha,tipo,banco) para permitir una fila por banco por día.
	 */
	private function ensureBancoColumna() {
		if ($this->db->table_exists($this->tbl) && !$this->db->field_exists('banco', $this->tbl)) {
			$this->db->query(
				"ALTER TABLE `{$this->tbl}` ADD COLUMN `banco` VARCHAR(32) NOT NULL DEFAULT '' AFTER `tipo`"
			);
			if ($this->indiceExiste($this->tbl, 'uk_precio_dolar_fecha_tipo')) {
				$this->db->query("ALTER TABLE `{$this->tbl}` DROP INDEX `uk_precio_dolar_fecha_tipo`");
			}
			if (!$this->indiceExiste($this->tbl, 'uk_precio_dolar_fecha_tipo_banco')) {
				$this->db->query(
					"ALTER TABLE `{$this->tbl}` ADD UNIQUE KEY `uk_precio_dolar_fecha_tipo_banco` (`fecha`,`tipo`,`banco`)"
				);
			}
		}
		if ($this->db->table_exists($this->tblBitacora) && !$this->db->field_exists('banco', $this->tblBitacora)) {
			$this->db->query(
				"ALTER TABLE `{$this->tblBitacora}` ADD COLUMN `banco` VARCHAR(32) NOT NULL DEFAULT '' AFTER `tipo`"
			);
		}
	}

	private function indiceExiste($tabla, $nombreIndice) {
		$q = $this->db->query(
			'SELECT COUNT(*) AS n FROM information_schema.statistics'
			. ' WHERE table_schema = DATABASE() AND table_name = ' . $this->db->escape($tabla)
			. ' AND index_name = ' . $this->db->escape($nombreIndice)
		);
		$row = $q->num_rows() > 0 ? $q->row() : null;
		return $row && (int) $row->n > 0;
	}

	private function migrarHistorialABitacora() {
		$n = $this->db->count_all($this->tblBitacora);
		if ($n > 0) {
			return;
		}
		$q = $this->db->query(
			"SELECT * FROM `{$this->tbl}` WHERE precio > 0 AND usuario <> '' ORDER BY fecha ASC, oid ASC"
		);
		if ($q->num_rows() === 0) {
			return;
		}
		foreach ($q->result() as $row) {
			$this->db->insert($this->tblBitacora, array(
				'fecha' => $row->fecha,
				'tipo' => isset($row->tipo) ? $row->tipo : self::TIPO_BCV,
				'precio' => $row->precio,
				'usuario' => $row->usuario,
				'fecha_registro' => $row->fecha_actualizacion ? $row->fecha_actualizacion : $row->fecha_registro,
				'activo' => (int) $row->activo
			));
		}
	}

	/**
	 * @param string $banco '' = General. Cada banco (y General) tiene su
	 *   propio "hoy"/vigente independiente — sincronizar uno no debe
	 *   desactivar el de otro banco.
	 */
	public function syncEstadoDiario($tipo = self::TIPO_BCV, $banco = self::BANCO_GENERAL) {
		$this->ensureTable();
		$tipo = $this->normalizarTipo($tipo);
		$banco = $this->normalizarBanco($banco);
		date_default_timezone_set('America/Caracas');

		$hoy = date('Y-m-d');
		$hora = (int) date('G');
		$ahora = date('Y-m-d H:i:s');

		if ($hora >= 7) {
			$this->db->query(
				"UPDATE `{$this->tbl}` SET `activo` = 0 WHERE `fecha` < " . $this->db->escape($hoy)
				. " AND `tipo` = " . $this->db->escape($tipo)
				. " AND `banco` = " . $this->db->escape($banco)
			);
			$this->db->query(
				"UPDATE `{$this->tblBitacora}` SET `activo` = 0 WHERE `fecha` < " . $this->db->escape($hoy)
				. " AND `tipo` = " . $this->db->escape($tipo)
				. " AND `banco` = " . $this->db->escape($banco)
			);

			$rowHoy = $this->db->get_where($this->tbl, array('fecha' => $hoy, 'tipo' => $tipo, 'banco' => $banco))->row();
			if (!$rowHoy) {
				$ultimo = $this->getUltimoRegistroVigente($tipo, $banco);
				$this->db->insert($this->tbl, array(
					'fecha' => $hoy,
					'tipo' => $tipo,
					'banco' => $banco,
					'precio' => $ultimo ? $ultimo->precio : 0,
					'activo' => 0,
					'usuario' => '',
					'fecha_registro' => $ahora,
					'fecha_actualizacion' => $ahora
				));
			}
		}
	}

	/**
	 * @param string $banco '' = General. Si se pide un banco específico y no
	 *   tiene ningún registro propio, cae en General como respaldo.
	 */
	public function getUltimoRegistroVigente($tipo = self::TIPO_BCV, $banco = self::BANCO_GENERAL) {
		$tipo = $this->normalizarTipo($tipo);
		$banco = $this->normalizarBanco($banco);
		$q = $this->db->query(
			"SELECT * FROM `{$this->tbl}` WHERE precio > 0 AND `tipo` = " . $this->db->escape($tipo)
			. " AND `banco` = " . $this->db->escape($banco)
			. " ORDER BY `fecha` DESC, `oid` DESC LIMIT 1"
		);
		if ($q->num_rows() > 0) {
			return $q->row();
		}
		if ($banco !== self::BANCO_GENERAL) {
			return $this->getUltimoRegistroVigente($tipo, self::BANCO_GENERAL);
		}
		return null;
	}

	public function getPrecioActivo($tipo = self::TIPO_BCV, $banco = self::BANCO_GENERAL) {
		$tipo = $this->normalizarTipo($tipo);
		$banco = $this->normalizarBanco($banco);
		$this->syncEstadoDiario($tipo, $banco);
		$q = $this->db->query(
			"SELECT * FROM `{$this->tbl}` WHERE `activo` = 1 AND `tipo` = " . $this->db->escape($tipo)
			. " AND `banco` = " . $this->db->escape($banco)
			. " ORDER BY `fecha` DESC LIMIT 1"
		);
		if ($q->num_rows() > 0) {
			return $q->row();
		}
		if ($banco !== self::BANCO_GENERAL) {
			return $this->getPrecioActivo($tipo, self::BANCO_GENERAL);
		}
		return null;
	}

	/**
	 * Tasa registrada para una fecha exacta (envío de domiciliación / fecha de cobro del banco).
	 * Sin fallback a fecha cercana: si no hay tasa exacta, devuelve null.
	 *
	 * Regla de oro: solo cuenta una tasa REGISTRADA MANUALMENTE por un usuario (`usuario <> ''`)
	 * y con `precio > 0`. El flag `activo` NO sirve para esta validación: por diseño, toda tasa
	 * de una fecha pasada queda con `activo = 0` (tanto la registrada a mano como el "stub" que
	 * syncEstadoDiario() inserta automáticamente para el día en curso, con usuario=''). Exigir
	 * `usuario <> ''` es lo que distingue una tasa real de ese stub arrastrado del último precio
	 * conocido, evitando que se use un valor no verificado para la fecha exacta solicitada.
	 *
	 * @param string $fecha Y-m-d
	 * @param string $tipo TIPO_BCV | TIPO_COBRANZA
	 * @param string $banco '' = General. Si se pide un banco específico y no
	 *   tiene tasa propia registrada para esa fecha exacta, cae en General
	 *   como respaldo (así no se rompe nada de lo ya cargado antes de que
	 *   existiera esta columna).
	 * @return object|null fila con precio, fecha
	 */
	public function getPrecioPorFecha($fecha, $tipo = self::TIPO_BCV, $banco = self::BANCO_GENERAL) {
		$this->ensureTable();
		$tipo = $this->normalizarTipo($tipo);
		$banco = $this->normalizarBanco($banco);
		$fecha = $this->normalizarFecha($fecha);
		if ($fecha === '') {
			return null;
		}

		$q = $this->db->query(
			"SELECT * FROM `{$this->tbl}` WHERE `fecha` = " . $this->db->escape($fecha)
			. " AND `tipo` = " . $this->db->escape($tipo)
			. " AND `banco` = " . $this->db->escape($banco)
			. " AND `precio` > 0 AND `usuario` <> '' ORDER BY `oid` DESC LIMIT 1"
		);
		if ($q->num_rows() > 0) {
			return $q->row();
		}

		$qBit = $this->db->query(
			"SELECT fecha, precio, usuario, fecha_registro FROM `{$this->tblBitacora}`"
			. " WHERE `fecha` = " . $this->db->escape($fecha)
			. " AND `tipo` = " . $this->db->escape($tipo)
			. " AND `banco` = " . $this->db->escape($banco)
			. " AND `precio` > 0 AND `usuario` <> '' ORDER BY `oid` DESC LIMIT 1"
		);
		if ($qBit->num_rows() > 0) {
			return $qBit->row();
		}

		if ($banco !== self::BANCO_GENERAL) {
			return $this->getPrecioPorFecha($fecha, $tipo, self::BANCO_GENERAL);
		}

		return null;
	}

	private function normalizarFecha($fecha) {
		$fecha = trim((string) $fecha);
		if ($fecha === '') {
			return '';
		}
		$ts = strtotime($fecha);
		if ($ts === false) {
			return '';
		}
		return date('Y-m-d', $ts);
	}

	/**
	 * @param string|null $tipo null = ambos tipos mezclados (para la vista combinada del panel)
	 * @param string|null $banco null = todos los bancos mezclados; '' = solo General;
	 *   'VENEZUELA'|'BICENTENARIO'|'BANFAN' = solo ese banco.
	 */
	public function getBitacora($limite = 200, $tipo = null, $banco = null) {
		$limite = (int) $limite;
		if ($limite <= 0) {
			$limite = 200;
		}
		if ($limite > 500) {
			$limite = 500;
		}
		$cond = array();
		if ($tipo !== null) {
			$cond[] = '`tipo` = ' . $this->db->escape($this->normalizarTipo($tipo));
		}
		if ($banco !== null) {
			$cond[] = '`banco` = ' . $this->db->escape($this->normalizarBanco($banco));
		}
		$where = !empty($cond) ? (' WHERE ' . implode(' AND ', $cond)) : '';
		return $this->db->query(
			"SELECT oid, fecha, tipo, banco, precio, usuario, fecha_registro, activo
			 FROM `{$this->tblBitacora}`{$where}
			 ORDER BY fecha_registro DESC, oid DESC
			 LIMIT {$limite}"
		)->result();
	}

	/**
	 * @param string|null $tipo null = ambos tipos mezclados (una fila por fecha+tipo+banco)
	 * @param string|null $banco null = todos los bancos mezclados; '' = solo General; o un banco puntual.
	 */
	public function getResumenPorDia($limite = 60, $tipo = null, $banco = null) {
		$limite = (int) $limite;
		if ($limite <= 0) {
			$limite = 60;
		}
		$filtro = '';
		if ($tipo !== null) {
			$filtro .= ' AND b.tipo = ' . $this->db->escape($this->normalizarTipo($tipo));
		}
		if ($banco !== null) {
			$filtro .= ' AND b.banco = ' . $this->db->escape($this->normalizarBanco($banco));
		}
		return $this->db->query(
			"SELECT b.fecha, b.tipo, b.banco, b.precio, b.usuario, b.fecha_registro, b.activo
			 FROM `{$this->tblBitacora}` b
			 INNER JOIN (
				SELECT fecha, tipo, banco, MAX(oid) AS ultimo_oid
				FROM `{$this->tblBitacora}`
				GROUP BY fecha, tipo, banco
			 ) u ON b.oid = u.ultimo_oid
			 WHERE 1=1{$filtro}
			 ORDER BY b.fecha DESC, b.tipo ASC
			 LIMIT {$limite}"
		)->result();
	}

	private function estadoPorTipo($tipo, $banco = self::BANCO_GENERAL) {
		$tipo = $this->normalizarTipo($tipo);
		$banco = $this->normalizarBanco($banco);
		$this->syncEstadoDiario($tipo, $banco);
		date_default_timezone_set('America/Caracas');

		$hoy = date('Y-m-d');
		$hora = (int) date('G');
		$activo = $this->getPrecioActivo($tipo, $banco);
		$rowHoy = $this->db->get_where($this->tbl, array('fecha' => $hoy, 'tipo' => $tipo, 'banco' => $banco))->row();

		$requiere = false;
		if ($hora >= 7) {
			$requiere = (!$rowHoy || (int) $rowHoy->activo === 0 || (float) $rowHoy->precio <= 0);
		}

		return array(
			'tipo' => $tipo,
			'requiere_actualizacion' => $requiere,
			'precio_activo' => $activo ? (float) $activo->precio : 0,
			'fecha_activo' => $activo ? $activo->fecha : '',
			'usuario_activo' => $activo ? $activo->usuario : '',
			'precio_hoy' => $rowHoy ? (float) $rowHoy->precio : 0,
			'activo_hoy' => $rowHoy ? (int) $rowHoy->activo : 0,
			'usuario_hoy' => $rowHoy ? $rowHoy->usuario : '',
			'actualizado_hoy' => $rowHoy ? $rowHoy->fecha_actualizacion : ''
		);
	}

	/**
	 * @param string $banco '' = General (comportamiento de siempre); o
	 *   'VENEZUELA'|'BICENTENARIO'|'BANFAN' para ver/editar la tasa propia
	 *   de ese banco.
	 */
	public function getEstadoPanel($banco = self::BANCO_GENERAL) {
		date_default_timezone_set('America/Caracas');
		$banco = $this->normalizarBanco($banco);

		return array(
			'banco' => $banco,
			'bancos_validos' => $this->bancosValidos(),
			'fecha_hoy' => date('Y-m-d'),
			'hora_servidor' => date('H:i'),
			'bcv' => $this->estadoPorTipo(self::TIPO_BCV, $banco),
			'cobranza' => $this->estadoPorTipo(self::TIPO_COBRANZA, $banco),
			'bitacora' => $this->getBitacora(200, null, $banco),
			'resumen_dias' => $this->getResumenPorDia(60, null, $banco)
		);
	}

	/**
	 * @param string|null $fecha Y-m-d a registrar. Null/vacío = hoy. Si es distinto al día
	 *   actual del servidor, el registro queda como histórico (activo = 0) y no toca el
	 *   precio activo vigente.
	 * @param string $banco '' = General (comportamiento de siempre); o un banco puntual —
	 *   en ese caso solo afecta la tasa vigente DE ESE banco, nunca la de otro.
	 */
	public function guardar($precio, $usuario, $tipo = self::TIPO_BCV, $fecha = null, $banco = self::BANCO_GENERAL) {
		$tipo = $this->normalizarTipo($tipo);
		$banco = $this->normalizarBanco($banco);
		$this->syncEstadoDiario($tipo, $banco);
		date_default_timezone_set('America/Caracas');

		$precio = (float) str_replace(',', '.', trim($precio));
		if ($precio <= 0) {
			return array('ok' => false, 'msg' => 'Ingrese un precio del dólar (' . $tipo . ') mayor a cero.');
		}

		$hoy = date('Y-m-d');
		$fechaRegistro = ($fecha === null || trim((string) $fecha) === '') ? $hoy : $this->normalizarFecha($fecha);
		if ($fechaRegistro === '') {
			return array('ok' => false, 'msg' => 'Fecha inválida para registrar el precio (' . $tipo . ').');
		}
		$esHoy = ($fechaRegistro === $hoy);

		$ahora = date('Y-m-d H:i:s');
		$usuario = substr(trim($usuario), 0, 64);
		$activo = $esHoy ? 1 : 0;

		if ($esHoy) {
			// El precio de hoy pasa a ser el vigente de ESTE banco (o General):
			// desactiva cualquier otro activo previo del MISMO banco, sin tocar
			// la tasa vigente de otros bancos.
			$this->db->query(
				"UPDATE `{$this->tbl}` SET `activo` = 0 WHERE `tipo` = " . $this->db->escape($tipo)
				. " AND `banco` = " . $this->db->escape($banco)
			);
			$this->db->query(
				"UPDATE `{$this->tblBitacora}` SET `activo` = 0 WHERE `tipo` = " . $this->db->escape($tipo)
				. " AND `banco` = " . $this->db->escape($banco)
			);
		}

		$this->db->insert($this->tblBitacora, array(
			'fecha' => $fechaRegistro,
			'tipo' => $tipo,
			'banco' => $banco,
			'precio' => $precio,
			'usuario' => $usuario,
			'fecha_registro' => $ahora,
			'activo' => $activo
		));

		$rowFecha = $this->db->get_where($this->tbl, array('fecha' => $fechaRegistro, 'tipo' => $tipo, 'banco' => $banco))->row();
		if ($rowFecha) {
			$this->db->where('oid', $rowFecha->oid);
			$this->db->update($this->tbl, array(
				'precio' => $precio,
				'activo' => $activo,
				'usuario' => $usuario,
				'fecha_actualizacion' => $ahora
			));
		} else {
			$this->db->insert($this->tbl, array(
				'fecha' => $fechaRegistro,
				'tipo' => $tipo,
				'banco' => $banco,
				'precio' => $precio,
				'activo' => $activo,
				'usuario' => $usuario,
				'fecha_registro' => $ahora,
				'fecha_actualizacion' => $ahora
			));
		}

		$etiquetaBanco = $banco !== self::BANCO_GENERAL ? (' (' . $banco . ')') : ' (General)';
		$msj = $esHoy
			? 'Precio del dólar (' . $tipo . ')' . $etiquetaBanco . ' registrado en bitácora para el día ' . $fechaRegistro . '.'
			: 'Precio del dólar (' . $tipo . ')' . $etiquetaBanco . ' registrado como histórico para el día ' . $fechaRegistro
				. ' (no afecta el precio activo vigente).';

		return array(
			'ok' => true,
			'msg' => $msj,
			'fecha' => $fechaRegistro,
			'es_hoy' => $esHoy,
			'actualizado' => $rowFecha ? true : false
		);
	}

	/**
	 * Registro obligatorio de ambos tipos en una sola operación (HU: ninguno se guarda si falta el otro).
	 *
	 * @param string|null $fecha Y-m-d. Null/vacío = hoy.
	 * @param string $banco '' = General; o un banco puntual.
	 * @return array ['ok'=>bool,'msg'=>string,'estado'=>array]
	 */
	public function guardarAmbos($precioBcv, $precioCobranza, $usuario, $fecha = null, $banco = self::BANCO_GENERAL) {
		$banco = $this->normalizarBanco($banco);
		$bcv = (float) str_replace(',', '.', trim((string) $precioBcv));
		$cobranza = (float) str_replace(',', '.', trim((string) $precioCobranza));

		$faltantes = array();
		if ($bcv <= 0) {
			$faltantes[] = 'Dólar BCV';
		}
		if ($cobranza <= 0) {
			$faltantes[] = 'Dólar Cobranza';
		}
		if (!empty($faltantes)) {
			return array(
				'ok' => false,
				'msg' => 'Debe registrar ambas tasas. Falta: ' . implode(' y ', $faltantes) . '.'
			);
		}

		$resBcv = $this->guardar($bcv, $usuario, self::TIPO_BCV, $fecha, $banco);
		if (!$resBcv['ok']) {
			return $resBcv;
		}
		$resCobranza = $this->guardar($cobranza, $usuario, self::TIPO_COBRANZA, $fecha, $banco);
		if (!$resCobranza['ok']) {
			return $resCobranza;
		}

		date_default_timezone_set('America/Caracas');
		$fechaRegistro = $resBcv['fecha'];
		$etiquetaBanco = $banco !== self::BANCO_GENERAL ? (' (' . $banco . ')') : ' (General)';
		$msj = $resBcv['es_hoy']
			? 'Precio del dólar BCV y Cobranza' . $etiquetaBanco . ' registrados para el día ' . $fechaRegistro . '.'
			: 'Precio del dólar BCV y Cobranza' . $etiquetaBanco . ' registrados como histórico para el día ' . $fechaRegistro . '.';

		return array(
			'ok' => true,
			'msg' => $msj,
			'estado' => $this->getEstadoPanel($banco)
		);
	}
}
