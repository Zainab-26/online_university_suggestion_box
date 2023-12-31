<!-- Side navigation -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar1" onclick="toggleSidebar()">

    <!-- Logo -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="manage_dept_ideas.php">
        <div class="sidebar-brand-icon">
            <i class="fas fa-university fa-lg"></i>
        </div>
        <div class="sidebar-brand-text mx-md-2">RM University</div>
    </a>

    <hr class="sidebar-divider my-0">

    <li class="nav-item">
        <a class="nav-link" href="index.html">
            <i class="fas fa-bookmark"></i>
            <span>QA Coordinator Dashboard</span></a>
    </li>

    <hr class="sidebar-divider">

    <div class="sidebar-heading">
        Manage
    </div>

    <li class="nav-item">
        <a class="nav-link" href="manage_dept_users.php">
            <i class="fas fa-users"></i>
            <span>Department Users</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="manage_dept_ideas.php">
            <i class="fas fa-fw fa-comment"></i>
            <span>Department Ideas</span></a>
    </li>

    <hr class="sidebar-divider">

    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

    <script>
        function toggleSidebar() {
            var sidebar = document.getElementById("accordionSidebar1");
            sidebar.classList.toggle("toggled");
        }

        function toggleSidebar2() {
            var sidebar = document.getElementById("accordionSidebar1");
            var sidebarToggleBtn = document.getElementById("menu");

            sidebar.classList.toggle("toggled");
            sidebarToggleBtn.classList.toggle("toggled");
        }
    </script>
</ul>

<div id="content-wrapper" class="d-flex flex-column">

    <div id="content">

        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

            <button id="menu" class="btn btn-link d-md-none rounded-circle mr-3" onclick="toggleSidebar2()">
                <i class="fa fa-bars"></i>
            </button>

            <ul class="navbar-nav ml-auto">

                <div class="topbar-divider d-none d-sm-block"></div>

                <li class="nav-item dropdown no-arrow">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                            <?php
                            echo $_SESSION['First_name'];
                            echo ' ';
                            echo $_SESSION['Last_name'];
                            ?>
                        </span>

                    </a>

                    <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                        aria-labelledby="userDropdown">
                        <a class="dropdown-item" href="../Common_pages/logout.php" data-toggle="modal"
                            data-target="#logoutModal">
                            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                            Logout
                        </a>
                    </div>
                </li>

            </ul>

        </nav>