<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <!-- Optional JavaScript and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.min.js"></script>
    <title>PDF</title>
</head>

<body>
    <form method="post" enctype="multipart/form-data">
        <div class="container mt-5">
            <div class="form-input py-2">
                <div class="form-group">
                    <input type="text" class="form-control" name="name" placeholder="Enter your name" required>
                </div>
                <div class="form-group">
                    <input type="file" name="pdf_file" class="form-control" accept=".pdf" title="Upload PDF" />
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" name="submit" value="Submit">
                </div>
            </div>
        </div>
    </form>
</body>

</html>
<?php
if (isset($_POST['submit'])) {
    $name = $_POST['name'];

    if (isset($_FILES['pdf_file']['name'])) {
        $file_name = $_FILES['pdf_file']['name'];
        $file_tmp  = $_FILES['pdf_file']['tmp_name'];

        move_uploaded_file($file_tmp, './pdf/' . $file_name);

        $insertquery =
            "INSERT INTO pdf_data(username,filename) VALUES('$name','$file_name')";
        $iquery      = mysqli_query($con, $insertquery);
    } else {
?>
<div class="alert alert-danger alert-dismissible 
			fade show text-center">
    <a class="close" data-dismiss="alert" aria-label="close">×</a>
    <strong>Failed!</strong>
    File must be uploaded in PDF format!
</div>
<?php
    }
}
?>