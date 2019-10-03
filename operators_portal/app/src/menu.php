<?php

/**
 * Here the menu of the whole platform is created.
 * 
 * @param class $site_directions The instantiation of the site_directions class
 */
function oper_menu($site_directions) {
?>
    <div class="row" style="height: 50px;">
        <div class="col-md-2 menu-item">
            <a href ="<?php echo $site_directions->link_generator("home"); ?>">Home</a>
        </div>
        <div class="col-md-2 menu-item">
            <a href ="<?php echo $site_directions->link_generator("about"); ?>">About</a>
        </div>
        <div class="col-md-2 menu-item">
            <a href ="<?php echo $site_directions->link_generator("login"); ?>">Login</a>
        </div>
        <div class="col-md-2 menu-item">
            <a href ="<?php echo $site_directions->link_generator("signup"); ?>">Sign up</a>
        </div>
    </div>
<?php
    // Apart from the basic resourses, we load the resources regarding the menu creation
    load_menu_resources();
}