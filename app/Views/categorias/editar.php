<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid">
            <h3 class="mt-4"><?php echo $titulo; ?></h3>

            <form method="post" action="<?php echo base_url(); ?>/categorias/actualizar" autocomplete="off">

                
                <input type="hidden" name="id" value="<?php echo $dato['id']; ?>">

                <div class="form-group">
                    <div class="row">

                        <div class="col-12 col-sm-6">
                            <label>Nombre</label>
                            <input class="form-control" id="nombre" name="nombre" type="text"
                                   value="<?php echo $dato['nombre']; ?>" autofocus required />
                        </div>


                    </div>
                </div>

                <a href="<?php echo base_url(); ?>/categorias" class="btn btn-primary mt-3">Regresar</a>
                <button type="submit" class="btn btn-success mt-3">Actualizar</button>

            </form>
        </div>
    </main>
</div>
