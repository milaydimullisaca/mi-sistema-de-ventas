<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid">
            <h3 class="mt-4"><?php echo $titulo; ?></h3>

            <form method="post" action="<?php echo base_url(); ?>/unidades/actualizar" autocomplete="off">

                <!-- Input oculto para enviar el ID -->
                <input type="hidden" name="id" value="<?php echo $dato['id']; ?>">

                <div class="form-group">
                    <div class="row">

                        <div class="col-12 col-sm-6">
                            <label>Nombre</label>
                            <input class="form-control" id="nombre" name="nombre" type="text"
                                   value="<?php echo $dato['nombre']; ?>" autofocus required />
                        </div>

                        <div class="col-12 col-sm-6">
                            <label>Nombre corto</label>
                            <input class="form-control" id="nombre_corto" name="nombre_corto" type="text"
                                   value="<?php echo $dato['nombre_corto']; ?>" required />
                        </div>

                    </div>
                </div>

                <a href="<?php echo base_url(); ?>/unidades" class="btn btn-primary mt-3">Regresar</a>
                <button type="submit" class="btn btn-success mt-3">Actualizar</button>

            </form>
        </div>
    </main>
</div>
