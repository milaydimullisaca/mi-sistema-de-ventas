<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid">

            <?php $idVentaTmp = uniqid(); ?>
            <br>
            <form id="form_venta" name="form_venta" class="form-horizontal" method="POST" action="<?php echo base_url(); ?>/ventas/guarda" autocomplete="off">

                <input type="hidden" id="id_venta" name="id_venta" value="<?php echo $idVentaTmp; ?>" />
                <div class="form-group">
                    <div class="row">
                        <div class="col-12 col-sm-6">
                            <div class="ui-widget">
                                <label>Cliente:</label>
                                <input type="hidden" id="id_cliente" name="id_cliente" value="1" />
                                <input type="text" class="form-control" id="cliente" name="cliente"
                                    placeholder="Escribe el nombre del cliente" value="Público en general" autocomplete="off" required />
                            </div>
                        </div>

                        <div class="col-12 col-sm-6">
                            <label>Forma de pago:</label>
                            <select id="forma_pago" name="forma_pago" class="form-control" required>
                                <option value="001">Efectivo</option>
                                <option value="002">Tarjeta</option>
                                <option value="003">Transferencia</option>
                                <option value="004">Yape</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row mb-3 align-items-end">
                    <div class="col-12 col-lg-3">
                        <input type="hidden" id="id_producto" name="id_producto" />
                        <label>Código de barras</label>
                        <input type="text" id="codigo" name="codigo" class="form-control"
                            placeholder="Escribe el código y enter"
                            onkeyup="agregarProducto(event, this.value,1, '<?php echo $idVentaTmp; ?>')" autofocus />
                        <label id="resultado_error" style="color:red"></label>
                    </div>

                    <div class="col-12 col-lg-3 text-center">
                       <label class="h3 font-weight-bold mb-0 mr-2">Total $</label>
                       <input type="text" class="form-control form-control-lg text-center font-weight-bold mr-2" id="total" 
                       name="total" readonly value="0.00" style="width: 150px; height: 50px;" />
                    </div>

                    <div class="col-12 col-lg-3">
                        <button type="button" id="completa_venta" class="btn btn-info btn-lg" 
                        style="height: 50px; margin-top: 25px;">Completar venta</button>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <table id="tablaProductos" class="table table-hover table-striped table-sm table-responsive" width="100%">
                            <thead class="thead-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Código</th>
                                    <th>Nombre</th>
                                    <th>Precio</th>
                                    <th>Cantidad</th>
                                    <th>Total</th>
                                    <th width="1%"></th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

            </form>
        </div>
    </main>


    <script>

        $(function () {
            $("#cliente").autocomplete({
                source: "<?= base_url(); ?>/clientes/autocompleteData",
                minLength: 3,
                select: function (event, ui) {
                    event.preventDefault();
                    $("#id_cliente").val(ui.item.id);
                    $("#cliente").val(ui.item.value);
                }
            });
        });

        $(function () {
            $("#codigo").autocomplete({
                source: "<?= base_url(); ?>/productos/autocompleteData",
                minLength: 3,
                select: function (event, ui) {
                    event.preventDefault();
                    $("#codigo").val(ui.item.value);

                    setTimeout(function () {
                        let e = jQuery.Event("keypress");
                        e.which = 13;
                        agregarProducto(e, ui.item.id, 1, '<?= $idVentaTmp ?>');
                    }, 0);
                }
            });
        });

        function agregarProducto(e, id_producto, cantidad, id_venta) {
            let enterkey = 13;
            let codigo_val = $('#codigo').val();
            if (codigo_val != '') {
                if (e.which === enterkey) {
                    if (id_producto != null && id_producto != 0 && cantidad > 0) {
                        $.ajax({
                            url: '<?= base_url(); ?>/TemporalCompras/inserta/' + id_producto + "/" + cantidad + "/" + id_venta,
                            success: function (resultado) {
                                if (resultado != 0) {
                                    let res = JSON.parse(resultado);
                                    if (res.error == "") {
                                        $("#tablaProductos tbody").empty();
                                        $("#tablaProductos tbody").append(res.datos);
                                        $("#total").val(res.total);
                                        $('#id_producto').val('');
                                        $('#codigo').val('');
                                    }
                                }
                            }
                        });
                    }
                }
            }
        }

        function eliminarProducto(id_producto, id_venta) {
            $.ajax({
                url: '<?= rtrim(base_url(), '/') ?>/TemporalCompras/eliminar/' + id_producto + '/' + id_venta,
                success: function (resultado) {
                    if (resultado == 0) {
                        $(tagCodigo).val("");
                    } else {
                        var resultado = JSON.parse(resultado);
                        $("#tablaProductos tbody").empty();
                        $("#tablaProductos tbody").append(resultado.datos);
                        $("#total").val(resultado.total);
                    }
                }


            });
        }

        $(function () {
            $("#completa_venta").click(function () {
                let nFilas = $("#tablaProductos tr").length;
                if (nFilas < 2) {
                    alert("Debe agregra un producto");
                } else {
                   $("#form_venta").submit();
                }
            });
        });

    </script>