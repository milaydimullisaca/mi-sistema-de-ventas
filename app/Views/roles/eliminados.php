<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid">
            <h3 class="mt-4"><?php echo $titulo; ?></h3>
            <div>
                <p>

                    <a href="<?php echo base_url(); ?>/roles" class="btn 
                                btn-warning">Roles</a>
                </p>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Nombre</th>
                            <th></th>
                            
                           
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($datos as $dato) { ?>

                            <tr>
                                <td><?php echo $dato['id']; ?></td>
                                <td><?php echo $dato['nombre']; ?></td>
                                  
                                <td><a href="#" data-href="<?php echo base_url() . '/roles/reingresar/' . $dato['id']; ?>"
                                        data-toggle="modal" data-target="#modal-confirma" title="Reingresar registro">
                                        <i class="fa-solid fa-circle-arrow-up"></i>
                                    </a></td>

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
                    <h5 class="modal-title">Reingresar registro</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <p>¿Desea reingresar este registro?</p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                    <a class="btn btn-danger btn-ok">Si</a>
                    
                </div>

            </div>
        </div>
    </div>