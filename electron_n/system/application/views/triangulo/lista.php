<h2>
    <span class="label label-success"><i class="fa fa-user"></i></span> &nbsp; Buscar Facturas Afiliadas
</h2>
<br/>
<div class="row">
    <label for="monto" class="col-sm-2 control-label">Filtrar Por:</label>
    <div class="col-sm-4">
        <select class="form-control" id="contratos" name="contratos">
            <option value="0" selected="selected">Triangulos Activos</option>
            <option value="1">Triangulos Cancelados</option>
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
        <div class="panel panel-gray" data-collapsed="0">
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