<?php
$id_compra = uniqid();

?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid">

            <form method="POST" id="form_compra" name="form_compra" action="<?php echo base_url(); ?>/compras/guarda"
                autocomplete="off">

                <div class="form-group"></div>
                <div class="row">
                    <div class="col-12 col-lg-3 mb-3">
                        <input type="hidden" id="id_producto" name="id_producto" />
                        <input type="hidden" id="id_compra" name="id_compra" value="<?php echo $id_compra; ?>" />
                        <label>Código</label>

                        <input type="text" id="codigo" name="codigo" class="form-control"
                        placeholder="Escribe el código y enter" onkeyup="buscarProducto(event, this)" autofocus />

                        <label for="codigo" id="resultado_error" style="color:red"></label>
                    </div>

                    <div class="col-12 col-lg-5 mb-3">
                        <label>Nombre del producto</label>
                        <input class="form-control" id="nombre" name="nombre" type="text" disabled />
                    </div>

                    <div class="col-12 col-lg-4 mb-3">
                        <label>Cantidad</label>
                        <input type="number" id="cantidad" name="cantidad" class="form-control"
                            onkeyup="actualizarSubtotal()">

                    </div>

                </div>

                <div class="row mb-4">

                    <div class="col-12 col-lg-3">
                        <label>Precio de compra</label>
                        <input class="form-control" id="precio_compra" name="precio_compra" type="text" disabled />
                    </div>

                    <div class="col-12 col-lg-5">
                        <label>Subtotal</label>
                        <input class="form-control" id="subtotal" name="subtotal" type="text" readonly />
                    </div>

                    <div class="col-12 col-lg-4 d-flex align-items-end">
                        <button id="agregar_producto" name="agregar_producto" type="button"
                            class="btn btn-primary btn-block"
                            onclick="agregarProducto(id_producto.value, cantidad.value, '<?= $id_compra ?>')">Agregar
                            producto</button>
                    </div>

                </div>

                <div class="row">
                    <div class="col-12">
                        <table id="tablaProductos" class="table table-hower table-striped table-sm
                            table-responsive tablaProductos" width="100%">
                            <thead class="thead-dark">
                                <th>#</th>
                                <th>Código</th>
                                <th>Nombre</th>
                                <th>Precio</th>
                                <th>Cantidad</th>
                                <th>Total</th>

                                <th width="1%"></th>

                            </thead>

                            <tbody></tbody>

                        </table>
                    </div>
                </div>

                <div class="row justify-content-end mt-3 mb-5">
                    <div class="col-12 col-md-auto d-flex align-items-center">

                        <label class="h3 font-weight-bold mb-0 mr-2">Total $</label>

                        <input type="text" class="form-control form-control-lg text-center font-weight-bold mr-2"
                            id="total" name="total" readonly value="0.00" style="width: 150px; height: 50px;" />

                        <button type="button" id="completar" name="completar" class="btn btn-info btn-lg"
                            style="height: 50px;">Completar compra</button>
                    </div>
                </div>
            </form>
        </div>
    </main>

    <script>

        $(document).ready(function () {
            $("#completar").click(function () {
                let nFila = $("#tablaProductos tbody tr").length; // contar solo filas del tbody
                if (nFila < 1) {
                    alert("Agrega al menos un producto antes de completar la compra");
                } else {
                    $("#form_compra").submit(); // enviar formulario
                }
            });
        });


        function buscarProducto(e, tagCodigo) {
            if (e.key === "Enter") {
                e.preventDefault();
                var codigo = $(tagCodigo).val().trim();
                if (codigo === '') return;

                $.ajax({
                    url: '<?php echo base_url(); ?>/productos/buscarPorCodigo/' + codigo,
                    dataType: 'json',
                    success: function (resultado) {
                        if (!resultado.existe) {
                            $('#resultado_error').text(resultado.error);
                            $('#id_producto').val('');
                            $('#nombre').val('');
                            $('#cantidad').val('');
                            $('#precio_compra').val('');
                            $('#subtotal').val('');
                        } else {
                            $('#resultado_error').text('');
                            $('#id_producto').val(resultado.datos.id);
                            $('#nombre').val(resultado.datos.nombre);
                            $('#cantidad').val(1);
                            $('#precio_compra').val(resultado.datos.precio_compra);
                            $('#subtotal').val(parseFloat(resultado.datos.precio_compra).toFixed(2));
                            $('#cantidad').focus();
                        }
                    }

                });
            }
        }

        function agregarProducto(id_producto, cantidad, id_compra) {

            if (id_producto != null && id_producto != 0 && cantidad > 0) {
                $.ajax({
                    url: '<?php echo base_url(); ?>/TemporalCompras/inserta/' + id_producto + "/" + cantidad + "/" + id_compra,
                    success: function (resultado) {

                        if (resultado == 0) {

                        } else {
                            var resultado = JSON.parse(resultado);
                            if (resultado.error == "") {

                                $("#tablaProductos tbody").empty();
                                $("#tablaProductos tbody").append(resultado.datos);
                                $("#total").val(resultado.total);
                                $('#id_producto').val('');
                                $('#codigo').val('');
                                $('#nombre').val('');
                                $('#cantidad').val('');
                                $('#precio_compra').val('');
                                $('#subtotal').val('');

                            }
                        }
                    }
                });

            }

        }

        function eliminarProducto(id_producto, id_compra) {
            $.ajax({
                url: '<?= rtrim(base_url(), '/') ?>/TemporalCompras/eliminar/' + id_producto + '/' + id_compra,
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



    </script>
    <script>
        function actualizarSubtotal() {
            let cantidad = parseFloat($('#cantidad').val());
            let precio = parseFloat($('#precio_compra').val());

            if (isNaN(cantidad) || isNaN(precio)) {
                $('#subtotal').val("0.00");
                return;
            }

            let sub = cantidad * precio;
            $('#subtotal').val(sub.toFixed(2));
        }
    </script>