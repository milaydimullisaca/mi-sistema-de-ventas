<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid">
            <h3 class="mt-4"><?php echo $titulo; ?></h3>
            <div>
                <p>

                    <a href="<?php echo base_url(); ?>/categorias" class="btn 
                                btn-warning">Unidades</a>
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
                    
                                <td><a href="<?php echo base_url() . '/categorias/reingresar/' . $dato['id']; ?>"
                                        class="btn btn-success btn-sm">
                                        <i class="fa-solid fa-circle-arrow-up"></i>
                                    </a>
                                </td>

F
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>