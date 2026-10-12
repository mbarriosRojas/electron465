<h2>
    <span class="label label-success"><i class="fa fa-file"></i></span> &nbsp; Crear Bien
</h2>
<br/>

<br>
<div class="row fila-datos" id="fila-lista" style="display: none">
    <div class="col-md-12">

        <div class="panel panel-dark" data-collapsed="0">

            <!-- panel head -->
            <div class="panel-heading">
                <div class="panel-title">Ingrese nombre o modelo del bien</div>
            </div>

            <!-- panel body -->
            <div class="panel-body" >
                <div class="col-md-12" id="persona">
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="unidad" value="">
<div class="row fila-datos" id="fila-agregar">
    <div class="col-md-12">
        <div class="panel panel-dark" data-collapsed="0">
            <div class="panel-heading">
                <div class="panel-title">
Datos del Bien
                </div>
            </div>

            <div class="panel-body">

                <form role="form" id="frmPag" class="form-horizontal form-groups-bordered" action="#" method="post" onsubmit="return guardar();">
                    <div class="form-group">
                        <label for="cedula" class="col-sm-2 control-label">Nombre o Modelo</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="nombre" name="nombre" required="required" value="" />
                        </div>
                       
                        <div class="col-sm-5">
                            <button type="submit" class="btn btn-green btn-icon" id="btnGuardar">Guardar <i class="fa fa-save"></i></button>
                        </div>
                    </div>
                </form>

            </div>

        </div>
    </div>
</div>
