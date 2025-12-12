<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid">
            <h3 class="mt-4"><?php echo $titulo; ?></h3>
            <?php \Config\Services::validation()->listErrors(); ?>

            <?php if (session('errores')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>¡Atención!</strong><br>
                    <?php foreach (session('errores') as $error): ?>
                        • <?= $error ?><br>
                    <?php endforeach; ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>


            <form method="POST" action="<?php echo base_url('cajas/actualizar') ?>" autocomplete="off">
                <?php csrf_field() ?>
                <input type="hidden" id="id" name="id" value="<?php echo $cajas['id']; ?>" />
                <div class="form-group">
                    <div class="row">

                        <div class="col-12 col-sm-6">
                            <label>Nombre</label>
                            <input class="form-control" id="nombre" name="nombre" type="text"
                                value="<?php echo $cajas['nombre']; ?>" autofocus  />
                        </div>

                        <div class="col-12 col-sm-6">
                            <label>Folio </label>
                            <input class="form-control" id="folio" name="folio" type="text"
                                value="<?php echo $cajas['folio']; ?>" />
                        </div>

                    </div>
                </div>

               
                

                <a href="<?php echo base_url(); ?>/cajas" class="btn btn-primary ">Regresar</a>
                <button type="submit" class="btn btn-success">Guardar</button>
            </form>

        </div>
    </main>
</div>