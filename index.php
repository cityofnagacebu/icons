<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta http-equiv="x-ua-compatible" content="ie=edge" />
  <title>Home | ICoNS</title>
  <?php require_once 'constants/links.php';?>
  <style>
    .scrollrequirement::-webkit-scrollbar {
      display: none;
    }

    .scrollrequirement {
      -ms-overflow-style: none;  /* IE and Edge */
      scrollbar-width: none;  /* Firefox */
    }
  </style>
</head>
<body>
  <!-- START NEWS SECTION -->
  <section> 
    <?php require_once 'index_contents/index_news.php';?>
  </section>
  <!-- END NEWS SECTION -->
  <!-- START HEADER -->
  <div class="d-flex align-items-center justify-content-around bg-light" style="z-index: 0 !important;">
    <?php require_once 'index_contents/index_header.php';?>
  </div>
  <!-- END HEADER -->
  <!--START NAVIGATION-->
  <nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top px-5">
    <?php require_once 'index_contents/index_nav.php';?>
  </nav>
  <!--END NAVIGATION-->
  <!-- Carousel wrapper -->
  <div id="carouselBasicExample" class="carousel slide carousel-fade" data-mdb-ride="carousel">
    <?php require_once 'index_contents/index_carousel.php';?>
  </div>
  <!--End Carousel wrapper -->
  <!-- START ABOUT -->
  <main class="main" role="main">
    <?php require_once 'index_contents/index_about.php';?>
  </main>
  <!-- END ABOUT -->
  <!-- START REQUIREMENTS -->
  <div id="requirements" class="mt-5">
    <?php require_once 'index_contents/index_requirements.php'; ?>
  </div>
  <!-- END REQUIREMENTS -->
  <!-- START FOOTER -->
  <footer role="contentinfo" class="py-6 lh-1 bg-dark text-white">
    <br>
    <?php require_once 'index_contents/index_footer.php'; ?>
    <br>
  </footer>
  <!-- END FOOTER -->
</body>
  <?php require_once 'constants/scripts.php';?>
</html>
