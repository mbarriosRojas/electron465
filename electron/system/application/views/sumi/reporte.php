<h2>
    <span class="label label-success"><i class="fa fa-user"></i></span> &nbsp; Buscar Contratos
</h2>
<br/>
<div class="row">
    <label for="monto" class="col-sm-2 control-label">Filtrar Por:</label>
    <div class="col-sm-4">
        <select class="form-control" id="contratos" name="contratos">
            <option value="9">Contratos Por Entregar</option>
            <option value="0" selected="selected">Contratos Pendientes De Pago</option>
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
                <table class="table table-bordered datatable display" id="table-1">
                    <thead>
                    <tr>
                        <th>Factura</th><th>M.Factura</th><th>M.Limite</th><th>M.Pagado</th><th>Estatus</th>
                        <th>Informacion</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th>Totales</th>
                        <th>#</th>
                        <th>#</th>
                        <th></th>
                        <th>#</th>
                        <th>#</th>
                    </tr>
                    </tfoot>
                </table>
            </div>

        </div>

    </div>
</div>