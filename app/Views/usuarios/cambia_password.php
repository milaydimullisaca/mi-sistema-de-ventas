<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid">
            <h3 class="mt-4"><?php echo $titulo; ?></h3>
<?php if (isset($validation)): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>¡Atención!</strong><br>
        <?= $validation->listErrors() ?>
    </div>
    <?php endif; ?>
            <form method="post" action="<?php echo base_url(); ?>/usuarios/actualizar_password" autocomplete="off">


                <div class="form-group">
                    <div class="row">

                        <div class="col-12 col-sm-6">
                            <label>Usuario</label>
                            <input class="form-control" id="usuario" name="usuario" type="text"
                                value="<?php echo $usuario['usuario']; ?>" disabled />
                        </div>

                        <div class="col-12 col-sm-6">
                            <label>Nombre</label>
                            <input class="form-control" id="nombre" name="nombre" type="text"
                                value="<?php echo $usuario['nombre']; ?>" disabled />
                        </div>

                    </div>
                </div>
                <div class="form-group">
                    <div class="row">

                        <div class="col-12 col-sm-6">
                            <label>Contraseña</label>
                            <input class="form-control" id="password" name="password" type="password"
                               required />
                        </div>

                        <div class="col-12 col-sm-6">
                            <label>Confirma contraseña</label>
                            <input class="form-control" id="repassword" name="repassword" type="password"
                                 required />
                        </div>

                    </div>
                </div>

                <a href="<?php echo base_url(); ?>/usuarios" class="btn btn-primary mt-3">Regresar</a>
                <button type="submit" class="btn btn-success mt-3">Guardar</button>
                
                <?php if (isset($mensaje)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                 <strong>¡Atención!</strong><br>
                     <?= $mensaje;?>
                 
                    </div>
                    <?php endif; ?>

            </form>
        </div>
    </main>
</div>
