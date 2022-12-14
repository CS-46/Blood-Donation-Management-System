<!DOCTYPE html>
<html lang="en">
<head>
    <title>My Site</title>
    <meta charset="UTF-8">
    <meta content='width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;' name='viewport' />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <link rel="stylesheet" href="/public/css/index.css">
    <script src="/public/scripts/index.js"></script>
</head>
<body>
<?php

use App\view\components\ResponsiveComponent\NavbarComponent\AuthNavbar;

echo    AuthNavbar::getNavbarCSS();
?>
    {{content}}
<?php
echo    AuthNavbar::getNavbarJS();
?>
</body>
</html>
