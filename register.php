<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "db_template"; // Ganti dengan nama database Anda

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error_message = "";
$success_message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validasi input
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error_message = "Semua field harus diisi!";
    } elseif ($password != $confirm_password) {
        $error_message = "Password dan konfirmasi password harus sama!";
    } else {
        // Cek apakah username sudah ada di database
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error_message = "Username sudah digunakan, silakan gunakan username lain.";
        } else {
            // Cek apakah email sudah ada di database
            $sql = "SELECT * FROM users WHERE email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $error_message = "Email sudah digunakan, silakan gunakan email lain.";
            } else {
                // Hash password sebelum menyimpannya ke database
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Simpan ke database
                $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sss", $username, $email, $hashed_password);

                if ($stmt->execute()) {
                    $success_message = "Registrasi berhasil!";
                    header("Location: login.php"); // Redirect ke halaman login setelah sukses
                    exit();
                } else {
                    $error_message = "Terjadi kesalahan saat registrasi!";
                }
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Colorlib Templates">
    <meta name="author" content="Colorlib">
    <meta name="keywords" content="Colorlib Templates">

    <!-- Title Page-->
    <title>Register</title>

    <!-- Icons font CSS-->
    <link href="register/vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">
    <link href="register/vendor/font-awesome-4.7/css/font-awesome.min.css" rel="stylesheet" media="all">
    <!-- Font special for pages-->
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Vendor CSS-->
    <link href="register/vendor/select2/select2.min.css" rel="stylesheet" media="all">
    <link href="register/vendor/datepicker/daterangepicker.css" rel="stylesheet" media="all">

    <!-- Main CSS-->
    <link href="register/css/main.css" rel="stylesheet" media="all">
</head>

<body>
    <div class="page-wrapper bg-gra-02 p-t-130 p-b-100 font-poppins">
        <div class="wrapper wrapper--w680">
            <div class="card card-4">
                <div class="card-body">
                    <h2 class="title">Registration Form</h2>
                    <?php if (!empty($error_message)): ?>
                        <div class="alert alert-danger">
                            <?php echo $error_message; ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($success_message)): ?>
                        <div class="alert alert-success">
                            <?php echo $success_message; ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="row row-space">
                            <div class="col-2">
                                <div class="input-group">
                                    <label class="label">Username</label>
                                    <input class="input--style-4" type="text" name="username" required>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="input-group">
                                    <label class="label">Email</label>
                                    <input class="input--style-4" type="email" name="email" required>
                                </div>
                            </div>
                        </div>
                        <div class="row row-space">
                            <div class="col-2">
                                <div class="input-group">
                                    <label class="label">Password</label>
                                    <input class="input--style-4" type="password" name="password" required>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="input-group">
                                    <label class="label">Masukan Ulang Password</label>
                                    <input class="input--style-4" type="password" name="confirm_password" required>
                                </div>
                            </div>
                        </div>
                        <div class="p-t-15">
                            <button class="btn btn--radius-2 btn--blue" type="submit">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Jquery JS-->
    <script src="register/vendor/jquery/jquery.min.js"></script>
    <!-- Vendor JS-->
    <script src="register/vendor/select2/select2.min.js"></script>
    <script src="register/vendor/datepicker/moment.min.js"></script>
    <script src="register/vendor/datepicker/daterangepicker.js"></script>

    <!-- Main JS-->
    <script src="register/js/global.js"></script>

</body>

</html>
