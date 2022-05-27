<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Main Page</title>

  <link rel="stylesheet" href="styles.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous" />
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,300,800,900" rel="stylesheet" type="text/css">
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>
</head>

<body>
  <div class="header">
    <div class="navbar navbar-expand-lg menu ">
      <div class="collapse navbar-collapse justify-content-end" id="navbarToggler">
        <div class="nav-item mx-2">
          <a class="nav-link menu-text" href="#">HOME</a>
        </div>
        <div class="nav-item mx-2">
          <a class="nav-link menu-text" href="#">ABOUT</a>
        </div>
      </div>
      <div class="navbar-brand">
        <div class="logo"></div>
      </div>
      <div class="collapse navbar-collapse" id="navbarToggler">
        <div class="nav-item mx-2">
          <a class="nav-link menu-text" href="#">SERVICE</a>
        </div>
        <div class="nav-item mx-2">
          <a class="nav-link menu-text" href="#">CONTACT</a>
        </div>
      </div>
      <div class="collapse navbar-collapse position-absolute" style="right: 10px" id="navbarToggler">
        <div class="nav-item">
          <?php if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) { ?>
            <a class="nav-link menu-text bg-black" href="auth/logout.php"><?php echo $_SESSION["email"] ?></a>
          <?php } else { ?>
            <a class="nav-link menu-text bg-black" href="auth/login.php">LOGIN</a>
          <?php } ?>
        </div>
      </div>
      <button class="dropdown navbar-toggler position-absolute" type="button" data-bs-toggle="collapse" aria-expanded="false" aria-label="Toggle navigation" style="right: 10px">
        <a class="nav-link" href="#" id="navbarMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          <img src="images/menu.svg" alt="MENU" width="40px" height="40px">
        </a>
        <ul class="dropdown-menu dropdown-menu-light dropdown-menu-end" aria-labelledby="navbarMenuLink">
          <li><a class="dropdown-item" href="#">HOME</a></li>
          <li><a class="dropdown-item" href="#">ABOUT</a></li>
          <li><a class="dropdown-item" href="#">SERVICE</a></li>
          <li><a class="dropdown-item" href="#">CONTACT</a></li>

          <?php if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) { ?>
            <li><a class="dropdown-item" href="#"><?php echo $_SESSION["email"] ?></a></li>
            <li><a class="dropdown-item" href="auth/logout.php" onclick="window.open('logout.php')">LOGOUT</a></li>

          <?php } else { ?>
            <li><a class="dropdown-item" href="auth/login.php" onclick="window.open('login.php')">LOGIN</a></li>
          <?php } ?>
        </ul>
      </button>
    </div>
    <div class="container my-5">
      <h2 style="
            font-size: 6vw;
            color: white;
            text-align: center;
            font-weight: 800;
            margin-bottom: 10%;
          ">
        I am Open Sans 120px
      </h2>
    </div>
  </div>

  <div class="container my-5 py-lg-3 py-md-3 py-sm-2">
    <h3 class="fs-3 text-center line-height-sm mx-5" style="font-weight: 800;">
      I am open sans extra bold 48px
    </h3>
    <h4 class="fs-6 fw-light text-center mx-5" id="h4-for-lg">
      Please follow all directions, make fonts the same size, respect margins
      and spacing.
    </h4>
    <h4 class="fs-6 fw-light text-center mx-5" id="h4-for-sm">
      I am open sans extra bold 48px
    </h4>
  </div>

  <div class="container mb-5">
    <div class="row">
      <div class="col-12 col-md-4 ">
        <img class="p-md-0 py-3 px-5" src="images/photo2.png" alt="p1">
      </div>
      <div class="col-12 col-md-4">
        <img class="p-md-0 py-3 px-5" src="images/photo3.png" alt="p2">
      </div>
      <div class="col-12 col-md-4">
        <img class="p-md-0 py-3 px-5" src="images/photo4.png" alt="p3">
      </div>
    </div>
</body>

</html>
