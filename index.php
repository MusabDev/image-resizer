<?php

$msg = "";
$output = "";
if (isset($_POST["submit"])) {
  $file = $_FILES["formFile"]["tmp_name"];
  list($width, $height) = getimagesize($file);
  $nwidth = $_POST["width"];
  $nheight = $_POST["height"];
  $dpi = $_POST["dpi"];
  $newimage = imagecreatetruecolor($nwidth, $nheight);
  $source = imagecreatefromjpeg($file);
  imagecopyresized($newimage, $source, 0, 0, 0, 0, $nwidth, $nheight, $width, $height);
  $file_name = time() . '.jpg';

  ob_start();
  imagejpeg($newimage);
  $contents =  ob_get_contents();
  //Converting Image DPI to 300DPI                
  $contents = substr_replace($contents, pack("cnn", 1, $dpi, $dpi), 13, 5);
  ob_end_clean();
  $res = imagejpeg($newimage, 'uploads/' . $file_name);
  if ($res) {
    $output = '<img src="uploads/' . $file_name . '" alt="" class="img-fluid my-3"><a href="uploads/' . $file_name . '" download class="btn btn-info">Download Image</a>';
    $msg = "<div class='alert alert-success'>Image resized successfully.</div>";
  } else {
    $msg = "<div class='alert alert-danger'>Something wrong.</div>";
  }
}

?>

<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <title>Image Resizer</title>
</head>

<body>
  <div class="container py-5">
    <div class="row">
      <div class="col-lg-5 mx-auto">
        <div class="card border p-4 rounded bg-white">
          <div class="card-body">
            <h3 class="card-title mb-3">Image Resizer</h3>
            <?php echo $msg; ?>
            <form action="" method="POST" enctype="multipart/form-data">
              <div class="mb-3">
                <label for="formFile" class="form-label">Browse your file</label>
                <input class="form-control" type="file" id="formFile" name="formFile" required>
              </div>
              <div class="mb-3">
                <label for="width" class="form-label">Width</label>
                <input type="number" class="form-control" id="width" name="width" placeholder="300" value="300" required>
              </div>
              <div class="mb-3">
                <label for="height" class="form-label">Height</label>
                <input type="number" class="form-control" id="height" name="height" placeholder="200" value="200" required>
              </div>
              <div class="mb-3">
                <label for="dpi" class="form-label">DPI</label>
                <input type="number" class="form-control" id="dpi" name="dpi" placeholder="300" value="300" required>
              </div>
              <button class="btn btn-primary" name="submit">Resize Now</button>
            </form>
            <?php echo $output; ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>