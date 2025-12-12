<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid">
            <h3 class="mt-4"><?php echo $titulo; ?></h3>
    <?= \Config\Services::validation()->listErrors(); ?>

            <form method="post" action="<?php echo base_url(); ?>/unidades/insertar" autocomplete="off">
                <div class="form-group">
                    <div class="row">

                        <div class="col-12 col-sm-6">
                            <label>Nombre</label>
                            <input class="form-control" id="nombre" name="nombre" type="text" autofocus required />
                        </div>

                        <div class="col-12 col-sm-6">
                            <label>Nombre corto</label>
                            <input class="form-control" id="nombre_corto" name="nombre_corto" type="text" required />
                        </div>

                    </div>
                </div>

                <a href="<?php echo base_url(); ?>/unidades" class="btn btn-primary mt-3">Regresar</a>
                <button type="submit" class="btn btn-success mt-3">Guardar</button>
            </form>

        </div>
    </main>
</div>
