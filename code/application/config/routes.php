<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/
//rutas por defecto para configuracion
$route['default_controller'] = "front/home";
$route['404_override'] = '';

/*-------------------RUTAS DEL FRONTEND--------------*/
//rutas sobre la revista
$route['acerca_de'] = 'front/home/about';
$route['privacidad'] = 'front/home/privacy';
//rutas de contenido
$route['buscar'] = 'front/home/search';
$route['articulo/(:any)'] = 'front/articles/index/$1';
$route['categoria/(:any)'] = 'front/categories/index/$1';
//rutas de usuarios
$route['usuario/(:num)'] = 'front/users/index/$1';
$route['mi_cuenta'] = 'front/users/account';
$route['mi_cuenta/editar'] = 'front/users/edit_account';
$route['mi_cuenta/editar_curriculum'] = 'front/users/edit_curriculum';
$route['marcadores'] = 'front/users/bookmarks';
$route['marcadores/(:num)'] = 'front/users/bookmarks/$1';
//rutas de registro y de sesion
$route['login'] = 'front/users/login';
$route['logout'] = 'front/users/logout';
$route['registrar'] = 'front/users/register';
$route['activar/(:any)'] = 'front/users/activate_user/$1';

/*-------------------RUTAS DE LA ADMINISTRACION--------------*/

//rutas generales / administracion
$route['admin'] = 'admin/inicio';
$route['admin/login'] = 'admin/inicio/login';
$route['admin/logout'] = 'admin/inicio/logout';
$route['admin/login_now'] = 'admin/inicio/login_now';

//rutas de estadisticas /administracion
$route['admin/estadisticas/articulos'] = 'admin/estadisticas/stats_articles';
$route['admin/estadisticas/usuarios'] = 'admin/estadisticas/stats_users';

//rutas de usuarios / administracion
$route['admin/usuarios/(:num)'] = 'admin/usuarios/index/$1';
$route['admin/usuarios/nuevo'] = 'admin/usuarios/new_user';
$route['admin/usuarios/editar/(:num)'] = 'admin/usuarios/edit_user/$1';
$route['admin/mi_cuenta'] = 'admin/usuarios/account';

//rutas de articulos / administracion
$route['admin/articulos'] = 'admin/articulos/index';
$route['admin/articulos/nuevo'] = 'admin/articulos/new_article';
$route['admin/articulos/editar/(:num)'] = 'admin/articulos/edit_article/$1';

//rutas de categorias / administracion
$route['admin/categorias/(:num)'] = 'admin/categorias/index/$1';
$route['admin/categorias/nueva'] = 'admin/categorias/new_category';
$route['admin/categorias/editar/(:num)'] = 'admin/categorias/edit_category/$1';

//rutas de portadas / administracion
$route['admin/portadas/(:num)'] = 'admin/portadas/index/$1';
$route['admin/portadas/editar/(:num)'] = 'admin/portadas/edit_cover/$1';

/* End of file routes.php */
/* Location: ./application/config/routes.php */