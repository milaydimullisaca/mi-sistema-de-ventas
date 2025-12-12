<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid">
            <br />


            <div class="row">
                <div class="col-4">
                    <div class="card text-white bg-primary">
                        <div class="card-body">
                            <?php echo $total; ?> Total de productos
                        </div>
                        <a class="card-footer text-white" href="<?php echo base_url() ?>/productos">
                            Ver detalles
                        </a>

                    </div>
                </div>

                <div class="col-4">
                    <div class="card text-white bg-success">
                        <div class="card-body">
                            <?php echo $totalVentas['total']; ?> Ventas del dia
                        </div>
                        <a class="card-footer text-white" href="<?php echo base_url() ?>/ventas">
                            Ver detalles
                        </a>

                    </div>
                </div>

                <div class="col-4">
                    <div class="card text-white bg-info">
                        <div class="card-body">
                            <?php echo $minimos; ?> Productos con stock mínimo
                        </div>
                        <a class="card-footer text-white" href="<?php echo base_url() ?>/productos/mostrarMinimos">
                            Ver detalles
                        </a>

                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-4">
                    
                        <canvas id="myChart" width="400" height="400"></canvas>
                </div>
                
            </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const ctx = document.getElementById('myChart');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'sabado'],
                datasets: [{
                    label: 'Ventas del dia',
                    data: [12, 19, 3, 5, 2, 3],

                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>