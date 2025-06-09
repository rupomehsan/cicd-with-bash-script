<?php

/*
|--------------------------------------------------------------------------
| Auth Management Module
|--------------------------------------------------------------------------
*/

include_once base_path("app/Modules/Management/Auth/Routes/Route.php");

/*
|--------------------------------------------------------------------------
| Setting Management Module
|--------------------------------------------------------------------------
*/

include_once base_path("app/Modules/Management/SettingManagement/WebsiteSettings/Routes/Route.php");

/*
|--------------------------------------------------------------------------
| User Management Module
|--------------------------------------------------------------------------
*/

include_once base_path("app/Modules/Management/UserManagement/User/Routes/Route.php");

/*
|--------------------------------------------------------------------------
| Others Management Module
|--------------------------------------------------------------------------
*/
include_once base_path("app/Modules/Management/BlogManagement/BlogCategory/Routes/Route.php");
include_once base_path("app/Modules/Management/BlogManagement/Blog/Routes/Route.php");
include_once base_path("app/Modules/Management/BlogManagement/BlogWriter/Routes/Route.php");
