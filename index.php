<?php
session_start();



    if (!empty($_POST)) {
        $alert = '';
        if (empty($_POST['usuario']) || empty($_POST['clave'])) {
            $alert = '<div class="alert alert-danger mt-2" role="alert">
            Ingrese su usuario y su clave
            </div>';
        } else {
            require_once "conexion.php";
            $user = mysqli_real_escape_string($conexion, $_POST['usuario']);
            $clave = md5(mysqli_real_escape_string($conexion, $_POST['clave']));
            $query = "SELECT * FROM usuarios WHERE correo = ? AND pass = ?";
            $stmt = mysqli_prepare($conexion, $query);
            
            // Verificar si la preparación de la consulta fue exitosa
            if ($stmt) {
                // Enlazar parámetros
                mysqli_stmt_bind_param($stmt, "ss", $user, $clave);
            
                // Ejecutar la consulta
                mysqli_stmt_execute($stmt);
            
                // Obtener resultados
                $resultado = mysqli_stmt_get_result($stmt);
            
                // Contar el número de filas
                $num_rows = mysqli_num_rows($resultado);
            
                // Cerrar la consulta preparada
                mysqli_stmt_close($stmt);
            
                mysqli_close($conexion);
            
                if ($num_rows > 0) {
                    $dato = mysqli_fetch_array($resultado);
                    $_SESSION['active'] = true;
                    $_SESSION['nombres'] = $dato['nombres'];
                    $_SESSION['id_usuario'] = $dato['id'];
                    header("Location: Dispositivos/home.php");
                    exit();
                } else {
                    $alert = '<div class="alert  mt-2" role="alert">
                    Usuario o Contraseña Incorrecta
                    </div>';
                    session_destroy();
                }
            } else {
                // Manejo de errores al preparar la consulta
                $alert = '<div class="alert alert-danger mt-2" role="alert">
                Ocurrió un error al intentar iniciar sesión. Por favor, inténtelo de nuevo más tarde.
                </div>';
            }
        }
    }

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Sistema de inventario - Login</title>

    <!-- Custom fonts for this template-->
    <link href="Assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="Assets/css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-6 col-md-5">

                <div class="card o-hidden border-0 shadow-lg my-3">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Iniciar sesión!</h1>
                                    </div>
                                    <form class="user" action="" method="POST">
                                        <div class="form-group">
                                            <input type="email" class="form-control form-control-user" id="exampleInputEmail" aria-describedby="emailHelp" name="usuario" placeholder="Usuario">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user" id="exampleInputPassword" name="clave" placeholder="Contraseña">
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-user btn-block">
                                            Iniciar sesión
                                        </button>
                                        
                                        <?php echo isset($alert) ? $alert : ''; ?>
                                    </form>
                                    <hr>
                                   
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

</body>

</html>
