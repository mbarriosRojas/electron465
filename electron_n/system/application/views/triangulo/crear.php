<h2>
    <span class="label"><i class="fa fa-file"></i></span> &nbsp; Asociacion de Triangulo de Pago
</h2>
<br/>

<br>
<div class="row fila-datos" id="fila-lista" style="display: none">
    <div class="col-md-12">

        <div class="panel panel-dark" data-collapsed="0">

            <!-- panel head -->
            <div class="panel-heading">
                <div class="panel-title">bla bla bla</div>
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
        <div class="panel panel-grey" data-collapsed="0">
            <div class="panel-heading">
                <div class="panel-title">
Afiliaci&oacute;n de Triangulo
                </div>
            </div>

            <div class="panel-body">

                <form role="form" id="frmPag" class="form-horizontal form-groups-bordered" action="#" method="post" onsubmit="return guardar();">
                    <div class="form-group">
                        <label for="cedula" class="col-sm-2 control-label">N Factura Afiliado 1</label>
                        <div class="col-sm-2">
                            <input type="text" class="form-control" id="fact1" name="fact1" required="required" value="" onblur="buscar(1)" />
                            <input type="hidden" id="ced1" name="ced1" />
                        </div>
                        <label for="cedula" class="col-sm-2 control-label">N Factura Afiliado 2</label>
                        <div class="col-sm-2">
                            <input type="text" class="form-control" id="fact2" name="fact2" required="required" value="" onblur="buscar(2)" />
                            <input type="hidden" id="ced2" name="ced2" />
                        </div>
                        <label for="cedula" class="col-sm-2 control-label">N Factura Afiliado 3</label>
                        <div class="col-sm-2">
                            <input type="text" class="form-control" id="fact3" name="fact3" required="required" value="" onblur="buscar(3)"/>
                            <input type="hidden" id="ced3" name="ced3" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-4" id="afiliado1">A
                        </div>
                        <div class="col-md-4" id="afiliado2">B
                        </div>
                        <div class="col-md-4" id="afiliado3">C
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                            <button type="submit" class="btn btn-green btn-icon" id="btnGuardar">Guardar <i class="fa fa-save"></i></button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
