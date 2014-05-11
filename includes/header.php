			<header class="site-header">
            	<div class="intro">WELCOME, <span class="username"><?php echo $_SESSION["username"]; ?></span></div>  
            
                <nav class="main-menu">
                	<h2>Menu:</h2>
                    <a href="finder.php">Home</a>
                    <a href="#">Friends</a>
                    <a href="#">Photos</a>
                    <a href="index.php?status=logout" class="logout">Logout</a>
                </nav>
            </header>
              
            <div id="menu-button">Menu<i class="fa fa-2x fa-angle-down"></i></div>