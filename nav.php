<!-- navigation.php - Include this in all pages -->
<nav>
    <div class="nav-container">
        <div class="logo">
            <a href="home.php">
                <img src="immage/pbanner.png" alt="Healthcare Center Logo" height="40">
            </a>
        </div>
        
        <ul class="nav-links">
            <li><a href="home.php"><i class="fas fa-home"></i> <span class="hide-on-mobile">Home</span></a></li>
            <li><a href="register.php"><i class="fas fa-user-plus"></i> <span class="hide-on-mobile">Register</span></a></li>
            <li><a href="appointment.php"><i class="fas fa-calendar-check"></i> <span class="hide-on-mobile">Appointments</span></a></li>
            <li class="dropdown">
                <a href="#"><i class="fas fa-info-circle"></i> <span class="hide-on-mobile">About</span> <i class="fas fa-chevron-down"></i></a>
                <ul class="dropdown-menu">
                    <li><a href="doctors.php">Doctors</a></li>
                    <li><a href="mision-vision.php">Mission & Vision</a></li>
                    <li><a href="facilities.php">Facilities</a></li>
                    <li><a href="service.php">Services</a></li>
                </ul>
            </li>
            <li><a href="contact.php"><i class="fas fa-phone-alt"></i> <span class="hide-on-mobile">Contact</span></a></li>
            <?php if(isset($_SESSION['user_id'])): ?>
                <li><a href="call-history.php"><i class="fas fa-history"></i> <span class="hide-on-mobile">History</span></a></li>
                <li><a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> <span class="hide-on-mobile">Logout</span></a></li>
            <?php else: ?>
                <li><a href="login.php"><i class="fas fa-sign-in-alt"></i> <span class="hide-on-mobile">Login</span></a></li>
            <?php endif; ?>
            <li><a href="emergency.php" class="btn-emergency"><i class="fas fa-ambulance"></i> <span class="hide-on-mobile">Emergency</span></a></li>
        </ul>
        
        <div class="search-box">
            <input type="search" placeholder="Search...">
            <button type="submit"><i class="fas fa-search"></i></button>
        </div>
    </div>
</nav>