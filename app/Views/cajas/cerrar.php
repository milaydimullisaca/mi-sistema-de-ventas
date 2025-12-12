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


            <form method="POST" action="<?php echo base_url('cajas/cerrar') ?>" autocomplete="off">
                <input id="id_arqueo" name="id_arqueo" type="hidden" value="<?php echo $arqueo['id']; ?>"/>
                <div class="form-group">
                    <div class="row">

                        <div class="col-12 col-sm-6">
                            <label>Número de caja</label>
                            <input class="form-control" id="numero_caja" name="numero_caja" type="text"
                                value="<?php echo $caja['numero_caja']; ?>" autofocus required />
                        </div>

                        <div class="col-12 col-sm-6">
                            <label>Nombre </label>
                            <input class="form-control" id="nombre" name="nombre" type="text"
                                value="<?php echo $caja['nombre']; ?>" autofocus required />
                        </div>

                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-12 col-sm-6">
                            <label>Monto inicial </label>
                            <input class="form-control" id="monto_inicial" name="monto_inicial" type="text"
                                value="<?php echo $arqueo['monto_inicial']; ?>" required />
                        </div>

                        <div class="col-12 col-sm-6">
                            <label>Monto final</label>
                            <input class="form-control" id="monto_final" name="monto_final" type="text"
                                value="" required/>
                                
                        </div>

                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-12 col-sm-6">
                            <label>Fecha </label>
                            <input class="form-control" id="fecha" name="fecha" type="text"
                                value="<?php echo date('Y-m-d'); ?>" required />
                        </div>
                        <div class="col-12 col-sm-6">
                            <label>Hora</label>
                            <input class="form-control" id="hora" name="hora" type="text"
                                value="<?php echo date('H:i:s'); ?>" required />
                        </div>


                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-12 col-sm-6">
                            <label>Monto de ventas </label>
                            <input class="form-control" id="total_ventas" name="total_ventas" type="text" 
                            value="<?php echo $monto['total']; ?>" required/>
                               
                        </div>
                        <div class="col-12 col-sm-6">
                            <label>Total de ventas</label>
                            <input class="form-control" id="no_ventas" name="no_ventas" type="text" value=" "
                                required />
                        </div>


                    </div>
                </div>

                <a href="<?php echo base_url(); ?>/cajas" class="btn btn-primary ">Regresar</a>
                <button type="submit" class="btn btn-success">Guardar</button>
            </form>

        </div>
    </main>