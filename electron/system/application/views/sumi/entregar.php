<h2>
    <span class="label label-success"><i class="fa fa-user"></i></span> &nbsp; Buscar Contratos
</h2>
<br/>
<div class="row">
    <label for="monto" class="col-sm-2 control-label">Filtrar Por:</label>
    <div class="col-sm-4">
        <select class="form-control" id="contratos" name="contratos">
            <option value="0" selected="selected">Contratos Pendientes</option>
            <option value="1">Contratos Entregados</option>
        </select>                     </div>
    <div class="col-md-4">
        <div class="input-group">
            <span class="input-group-btn">
					<button class="btn btn-primary" id="btn-buscar">BUSCAR <i class="entypo-search"></i></button>
					</span>
        </div>
    </div>
</div>
<br>
<div class="row fila-datos" id="fila-lista">
    <div class="col-md-12">
        <div class="panel panel-dark" data-collapsed="0">
            <!-- panel head -->
            <div class="panel-heading">
                <div class="panel-title">Resultado de la Busqueda</div>
            </div>

            <!-- panel body -->
            <div class="panel-body" >
                <div class="col-md-12" id="respuesta">
                </div>
            </div>

        </div>

    </div>
</div>

<div class="modal fade" id="mdl-entrega">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Datos de entrega</h4>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <label>Imgrese Datos de entrega</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <input type="text" id="fact_entregar" name="fact_entregar" class="form-control" readonly="readonly"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <textarea class="form-control" id="det_entrega" name="det_entrega"></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <button class="btn btn-success fa fa-save" onclick="guardarEntrega()">Entregar</button>
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
