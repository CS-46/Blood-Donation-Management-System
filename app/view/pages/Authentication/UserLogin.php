<?php
use App\view\components\ResponsiveComponent\ImageComponent\BackGroundImage;
use App\view\components\ResponsiveComponent\NavbarComponent\AuthNavbar;
use App\view\components\ResponsiveComponent\NavbarComponent\Navbar;

$navbar= new Navbar([
    'Home'=>'/home',
    'About'=>'/about',
    'Contact Us'=>'/contact',
    'Log In'=>'/user/login'
],'#','/public/images/icons/user.png','');
echo AuthNavbar::getNavbarCSS();
echo $navbar;
echo AuthNavbar::getNavbarJS();
$background=new BackGroundImage();
echo $background;
?>
<h1>User Logging!</h1>
