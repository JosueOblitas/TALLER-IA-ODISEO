<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SimpleCRUD - PHP</title>
    <script src="./public/jquery/jquery.min.js"></script>
    <link href="./public/bootstrap/css/bootstrap.min.css" rel="stylesheet" >
    <script src="./public/bootstrap/js/bootstrap.min.js"></script>
    <link href="./public/fontawesome/css/all.min.css">
    <script src="./public/fontawesome/js/all.min.js"></script>
    <link href="./public/toastr/css/toastr.min.css" rel="stylesheet"/>

    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.css" />
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1 class="mt-2 mb-5">SimpleCRUD - PHP</h1>
            </div>
        </div>

        <div class="row justify-content-md-center">
            <div class="col-md-12">
                <h2 class="text-center">
                    Lista de empleados
                </h2>
                <hr>
            </div>
            <div class="col-md-12 my-2">
                <button class="btn btn-primary" onclick="abrirModalAgregarUsuario()">
                    <i class="fa-solid fa-plus"></i>
                    Agregar
                </button>
            </div>
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-hover" id="tbl-usuarios">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nombres</th>
                                <th scope="col">Apellidos</th>
                                <th scope="col">Correo</th>
                                <th scope="col">Acciones</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?php 
        include('./modals/GuardarUsuario.php');
        include('./modals/EliminarUsuario.php');
    ?>

    <script src="./public/axios/axios.min.js"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
    <script src="./public/toastr/js/toastr.min.js"></script>
    <script src="./public/js/index.js"></script>
</body>
</html>