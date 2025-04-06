<?php
session_start();
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logging Out...</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <script>
        Swal.fire({
            title: "Successfully Logged Out!",
            icon: "success",
            showConfirmButton: true
        }).then(() => {
            window.location.href = "login.php";
        });
    </script>
</body>
<<<<<<< HEAD
</html>
=======
</html>
>>>>>>> 348029ba3aeba2edaad36b635a5676567531541d
