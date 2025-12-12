<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid">
            <h3 class="mt-4"><?php echo $titulo; ?></h3>
            <div>
                <p>
                  
                    <a href="<?php echo base_url(); ?>/compras/nuevo" class="btn 
                                btn-info">Nuevo ingreso</a>
                </p>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Foliio</th>
                            <th>Total</th>
                            <th>Fecha</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($compras as $compra) { ?>

                            <tr>
                                <td><?php echo $compra['id']; ?></td>
                                <td><?php echo $compra['folio']; ?></td>
                                <td><?php echo $compra['total']; ?></td>
                                <td><?php echo $compra['fecha_alta']; ?></td>

                                <td><a href="<?php echo base_url() . '/compras/muestra-compras-pdf/' . $compra['id']; ?>"
                                    class="btn btn-primary"><i class="fa-regular fa-file"></i></a></td>

                            </tr>
                        <?php  } ?>
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