<!-- Side Navigation -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar1">

    <!-- Logo -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="view_ideas.php?filter=all">
        <div class="sidebar-brand-icon">
        <i class="fas fa-university fa-lg"></i>
        </div>
        <div class="sidebar-brand-text mx-md-2">RM University</div>
    </a>

    <hr class="sidebar-divider my-0">

        <li class="nav-item">
            <a class="nav-link" href="view_ideas.php?filter=all">
            <i class="fas fa-bookmark"></i>
            <span>Staff Dashboard</span></a>
        </li>

    <hr class="sidebar-divider">

    <div class="sidebar-heading">Ideas</div>

        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
            aria-expanded="true" aria-controls="collapseUtilities">
            <i class="fas fa-fw fa-comment"></i>
                <span>Ideas</span>
                </a>
                <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
                data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item" href="view_ideas.php?filter=all">View Ideas</a>
                    <a class="collapse-item" href="add_idea.php">Add new idea</a>
                    <a class="collapse-item" href="my_ideas.php?user_id=<?php echo $_SESSION['User_ID'] ?>">My ideas</a>
                    </div>
                </div>
        </li>

    <hr class="sidebar-divider">

    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle" onclick="toggleSidebar()"></button>
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
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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

                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Logout
                    </a>
                    </div>
                    </li>

            </ul>

</nav>
