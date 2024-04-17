
<!DOCTYPE html>
<html lang="en">
<?php
include("connection/connect.php");
error_reporting(0);
session_start();
?>


<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="#">
    <title>Restaurants</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animsition.min.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>

<body>

    <header id="header" class="header-scroll top-header headrom">
        <nav class="navbar navbar-dark">
            <div class="container">
                <button class="navbar-toggler hidden-lg-up" type="button" data-toggle="collapse" data-target="#mainNavbarCollapse">&#9776;</button>
                <a class="navbar-brand" href="index.php"> <img class="img-rounded" src="images/logo.png" alt="" width="18%"> </a>
                <div class="collapse navbar-toggleable-md  float-lg-right" id="mainNavbarCollapse">
                    <ul class="nav navbar-nav">
                        <li class="nav-item"> <a class="nav-link active" href="index.php">Home <span class="sr-only">(current)</span></a> </li>
                        <li class="nav-item"> <a class="nav-link active" href="restaurants.php">Restaurants <span class="sr-only"></span></a> </li>

                        <?php
						if(empty($_SESSION["user_id"]))
							{
								echo '<li class="nav-item"><a href="login.php" class="nav-link active">Login</a> </li>
							  <li class="nav-item"><a href="registration.php" class="nav-link active">Register</a> </li>';
							}
						else
							{
									
									
										echo  '<li class="nav-item"><a href="your_orders.php" class="nav-link active">My Orders</a> </li>';
									echo  '<li class="nav-item"><a href="logout.php" class="nav-link active">Logout</a> </li>';
							}

						?>
                    

                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <div class="page-wrapper">
        <div class="top-links">
            <div class="container">
                <ul class="row links">

                    <li class="col-xs-12 col-sm-4 link-item active"><span>1</span><a href="#">Choose Restaurant</a></li>
                    <li class="col-xs-12 col-sm-4 link-item"><span>2</span><a href="#">Pick Your favorite food</a></li>
                    <li class="col-xs-12 col-sm-4 link-item"><span>3</span><a href="#">Order and Pay</a></li>
                </ul>
            </div>
        </div>
        <div class="inner-page-hero bg-image" data-image-src="images/img/pimg.jpg">
            <div class="container"> </div>
        </div>
        <div class="result-show">
            <div class="container">
                <div class="row">
                </div>
            </div>
        </div>
       
        <section class="restaurants-page">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-5 col-md-5 col-lg-3">
                <!-- Výběrový prvek pro kategorie -->
                <div class="form-group">
                    <label for="categoryFilter">Category:</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"></span>
                        </div>
                        <select id="categoryFilter" class="form-control">
                            <option value="0">All</option>
                            <?php
                                // Načtení kategorií z databáze
                                $categories = mysqli_query($db, "SELECT * FROM res_category");
                                while ($row = mysqli_fetch_array($categories)) {
                                    echo '<option value="'.$row['c_id'].'">'.$row['c_name'].'</option>';
                                }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-7 col-md-7 col-lg-9">
                <div class="bg-gray restaurant-entry">
                    <div class="row" id="restaurantList">
                        <?php 
                            // Výpis restaurací
                            $restaurants = mysqli_query($db, "SELECT * FROM restaurant");
                            while ($row = mysqli_fetch_array($restaurants)) {
                                echo '<!-- Blok pro zobrazení informací o restauraci -->';
                                echo '<div class="col-sm-12 col-md-12 col-lg-8 text-xs-center text-sm-left restaurant-item" data-category="'.$row['c_id'].'">';
                                // Logo restaurace s odkazem na stránku s detaily
                                echo '<div class="entry-logo">';
                                echo '<a class="img-fluid" href="dishes.php?res_id='.$row['rs_id'].'" >';
                                echo '<img src="images/Res_img/'.$row['image'].'" alt="Food logo">';
                                echo '</a>';
                                echo '</div>';
                                // Název a adresa restaurace
                                echo '<div class="entry-dscr">';
                                echo '<h5><a href="dishes.php?res_id='.$row['rs_id'].'" >'.$row['title'].'</a></h5>';
                                echo '<span>'.$row['address'].'</span>';
                                echo '</div>';
                                echo '</div>';
                                // Blok pro tlačítko pro zobrazení menu
                                echo '<div class="col-sm-12 col-md-12 col-lg-4 text-xs-center restaurant-menu" style="display:none;">';
                                echo '<div class="right-content bg-white">';
                                echo '<div class="right-review">';
                                echo '<a href="dishes.php?res_id='.$row['rs_id'].'" class="btn btn-purple">View Menu</a>';
                                echo '</div>';
                                echo '</div>';
                                echo '</div>';
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    // Funkce pro filtrování restaurací podle vybrané kategorie
    document.getElementById("categoryFilter").addEventListener("change", function() {
        var category = this.value;
        var restaurantItems = document.getElementsByClassName("restaurant-item");
        for (var i = 0; i < restaurantItems.length; i++) {
            // Získání kategorie každé restaurace
            var restaurantCategory = restaurantItems[i].getAttribute("data-category");
            // Pokud je kategorie "Všechny kategorie" nebo kategorie restaurace odpovídá vybrané kategorii, zobrazí se
            if (category == 0 || restaurantCategory == category) {
                restaurantItems[i].style.display = "";
                // Zobrazení tlačítka pro zobrazení menu
                restaurantItems[i].nextElementSibling.style.display = "";
            } else {
                restaurantItems[i].style.display = "none";
                // Skrytí tlačítka pro zobrazení menu
                restaurantItems[i].nextElementSibling.style.display = "none";
            }
        }
        localStorage.setItem("selectedCategory", category);
    });

    window.onload = function() {
        // Získání uložené kategorie
        var category = localStorage.getItem("selectedCategory");
        // Nastavení vybrané kategorie v dropdown menu
        document.getElementById("categoryFilter").value = category;
        // Znovu provedení filtrování
        var event = new Event("change");
        document.getElementById("categoryFilter").dispatchEvent(event);
    };
</script>



    <?php include "include/footer.php" ?>

    <script src="js/jquery.min.js"></script>
    <script src="js/tether.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/animsition.min.js"></script>
    <script src="js/bootstrap-slider.min.js"></script>
    <script src="js/jquery.isotope.min.js"></script>
    <script src="js/headroom.js"></script>
    <script src="js/foodpicky.min.js"></script>
</body>

</html>