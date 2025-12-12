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


            <form method="POST" enctype="multipart/form-data" action="<?php echo base_url(); ?>/productos/insertar" 
            autocomplete="off">

                <?php csrf_field() ?>
                <div class="form-group">
                    <div class="row">

                        <div class="col-12 col-sm-6">
                            <label>Código</label>
                            <input class="form-control" id="codigo" name="codigo" type="text" autofocus required />
                        </div>

                        <div class="col-12 col-sm-6">
                            <label>Nombre </label>
                            <input class="form-control" id="nombre" name="nombre" type="text" required />
                        </div>

                    </div>
                </div>

                <div class="form-group">
                    <div class="row">

                        <div class="col-12 col-sm-6">
                            <label>Unidad</label>
                            <select class="form-control" id="id_unidad" name="id_unidad" required>
                                <option value="">Seleccionar unidad</option>
                                <?php foreach ($unidades as $unidad) { ?>
                                    <option value="<?php echo $unidad['id']; ?>"><?php echo $unidad['nombre']; ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="col-12 col-sm-6">
                            <label>Categoria </label>
                            <select class="form-control" id="id_categoria" name="id_categoria" required>
                                <option value="">Seleccionar categoria</option>
                                <?php foreach ($categorias as $categoria) { ?>
                                    <option value="<?php echo $categoria['id']; ?>"><?php echo $categoria['nombre']; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                    </div>
                </div>

                <div class="form-group">
                    <div class="row">

                        <div class="col-12 col-sm-6">
                            <label>Precio venta</label>
                            <input class="form-control" id="precio_venta" name="precio_venta" type="text" autofocus
                                required />
                        </div>

                        <div class="col-12 col-sm-6">
                            <label>Precio compra </label>
                            <input class="form-control" id="precio_compra" name="precio_compra" type="text" required />
                        </div>

                    </div>
                </div>


                <div class="form-group">
                    <div class="row">

                        <div class="col-12 col-sm-6">
                            <label>Stock minimo</label>
                            <input class="form-control" id="stock_minimo" name="stock_minimo" type="text" autofocus
                                required />
                        </div>


                        <div class="col-12 col-sm-6">
                            <label>Es inventariable </label>
                            <select id="inventariable" name="inventariable" class="form-control">
                                <option value="1">Si</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                    </div>
                </div>


                <div class="form-group">

                    <div class="row">
                        <div class="col-12 col-sm-6">
                            <label>Imagen</label><br />

                            <input type="file" id="img_producto" name="img_producto" accept="image/jpg" />
                            <p class="text-danger"> Cargar imagen en formato jpg de 150 x 150 pixiles</p>
                        </div>
                    </div>
                </div>

                <a href="<?php echo base_url(); ?>/productos" class="btn btn-primary ">Regresar</a>
                <button type="submit" class="btn btn-success">Guardar</button>
            </form>

        </div>
    </main>