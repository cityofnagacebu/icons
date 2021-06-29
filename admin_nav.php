      <!-- Container wrapper -->
      <div class="container-fluid">
        <!-- Toggle button -->
        <button class="navbar-toggler" type="button" data-mdb-toggle="collapse" data-mdb-target="#sidebarMenu"
          aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
          <i class="fas fa-bars"></i>
        </button>

        <!-- Brand -->
        <a class="navbar-brand" href="#">
          <img src="images/icon_brand_logo.png" height="35" alt="" loading="lazy" />
        </a>
        <!-- Search form -->
        <!-- <form class="d-none d-md-flex input-group w-auto my-auto">
          <input autocomplete="off" type="search" class="form-control rounded"
            placeholder='Search...' style="min-width: 225px" />
          <span class="input-group-text border-0"><i class="fas fa-search"></i></span>
        </form> -->

        <!-- Right links -->
        <ul class="navbar-nav ms-auto d-flex flex-row">
          <!-- Notification dropdown -->
          
          <li class="nav-item dropdown">
            <a class="nav-link me-3 me-lg-0 dropdown-toggle hidden-arrow" href="#" id="navbarDropdownMenuLink"
              role="button" data-mdb-toggle="dropdown" aria-expanded="false">
              <i class="fas fa-bell"></i>
              <span class="badge rounded-pill badge-notification bg-danger count"></span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end notif-list" aria-labelledby="navbarDropdownMenuLink">
            </ul>
          </li>

          <!-- Icon -->
          <!-- <li class="nav-item">
            <a class="nav-link me-3 me-lg-0" href="#">
              <i class="fas fa-fill-drip"></i>
            </a>
          </li> -->

          <!-- Avatar -->
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle hidden-arrow d-flex align-items-center" href="#"
              id="navbarDropdownMenuLink2" role="button" data-mdb-toggle="dropdown" aria-expanded="false">
              <img src="images/avatardefault.png" class="rounded-circle" height="22"
                alt="" loading="lazy" />
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuLink2">
              <li class="dropdown-item"><?php $role = ""; if ($_SESSION['role'] == "admin") {
                $role = "Admin";
              } echo $role." - ".$_SESSION['firstname']; ?></li>
              <li><a class="dropdown-item" href="admin_dashboard.php?logout-admin=<?php echo $_SESSION['admin_id']; ?>"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
          </li>
        </ul>
      </div>
      <!-- Container wrapper -->