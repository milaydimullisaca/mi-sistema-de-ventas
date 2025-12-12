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

            <form method="post" enctype="multipart/form-data" action="<?php echo base_url(); ?>/configuracion/actualizar" autocomplete="off">
                <div class="form-group">
                    <div class="row">

                        <div class="col-12 col-sm-6">
                            <label>Nombre de la tienda</label>
                            <input class="form-control" id="tienda_nombre" name="tienda_nombre" type="text"
                                value="<?php echo $nombre["valor"]; ?>" autofocus required />

                        </div>

                        <div class="col-12 col-sm-6">
                            <label>RFC</label>
                            <input class="form-control" id="tienda_rfc" name="tienda_rfc" type="text"
                                value="<?php echo $rfc["valor"]; ?>" required />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">

                        <div class="col-12 col-sm-6">
                            <label>Telefono de la tienda</label>
                            <input class="form-control" id="tienda_telefono" name="tienda_telefono" type="text"
                                value="<?php echo $telefono["valor"]; ?>" required />

                        </div>

                        <div class="col-12 col-sm-6">
                            <label>Correo de la tienda</label>
                            <input class="form-control" id="tienda_email" name="tienda_email" type="text"
                                value="<?php echo $email["valor"]; ?>" required />
                        </div>

                    </div>
                </div>
                <div class="form-group">
                    <div class="row">

                        <div class="col-12 col-sm-6">
                            <label>Direccion de la tienda</label>
                            <textarea class="form-control" id="tienda_direccion" name="tienda_direccion" type="text"
                                required><?php echo $direccion["valor"]; ?></textarea>

                        </div>

                        <div class="col-12 col-sm-6">
                            <label>Leyenda del ticket</label>
                            <textarea class="form-control" id="ticket_leyenda" name="ticket_leyenda" type="text"
                                required> <?php echo $leyenda["valor"]; ?></textarea>

                        </div>

                    </div>
                </div>

        

                <a href="<?php echo base_url(); ?>/configuracion" class="btn btn-primary mt-3">Regresar</a>
                <button type="submit" class="btn btn-success mt-3">Guardar</button>
            </form>

        </div>
    </main>