<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid">
            <h3 class="mt-4"><?php echo $titulo; ?></h3>
            <div>
                <p>

                    <a href="<?php echo base_url(); ?>/ventas/eliminados" class="btn 
                                btn-warning">Eliminados</a>
                </p>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Folio</th>
                            <th>Cliente</th>
                            <th>Total</th>
                            <th>Cajero</th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($datos as $dato) { ?>
                            <tr>
                                <td><?php echo $dato['fecha_alta']; ?></td>
                                <td><?php echo $dato['folio']; ?></td>
                                <td><?php echo $dato['cliente']; ?></td>
                                <td><?php echo $dato['total']; ?></td>
                                <td><?php echo $dato['cajero']; ?></td>

                                <td>
                                    <a href="<?php echo base_url() . '/factura/facturar/' . $dato['id']; ?>"
                                        class="btn btn-info">
                                        <i class="fa-solid fa-list"></i>
                                    </a>
                                </td>




                                <td>
                                    <a href="<?php echo base_url() . '/ventas/muestra-ticket-pdf/' . $dato['id']; ?>"
                                        class="btn btn-primary">
                                        <i class="fa-solid fa-list-alt"></i>
                                    </a>
                                </td>

                                <td>
                                    <a href="#" data-href="<?php echo base_url() . '/ventas/eliminar/' . $dato['id']; ?>"
                                        data-toggle="modal" data-target="#modal-confirma" title="Eliminar registro"
                                        class="btn btn-danger">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>

                </table>
            </div>
        </div>
    </main>

    <div class="modal" tabindex="-1" role="dialog" id="modal-confirma">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Eliminar registro</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <p>¿Desea eliminar este registro?</p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                    <a class="btn btn-danger btn-ok">Si</a>

                </div>

            </div>
        </div>
    </div>