<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index');
$routes->get('/about','Home::about');
$routes->get('/contact','Home::contact');

$routes->get('/register', 'Auth::register');
$routes->post('/register', 'Auth::register');
$routes->get('/login', 'Auth::login');
$routes->post('/login', 'Auth::login');
$routes->get('/logout', 'Auth::logout');
$routes->get('/dashboard', 'Auth::dashboard');

$routes->get('/admin/manage_users', 'Auth::manageUsers');
$routes->post('/admin/manage_users', 'Auth::manageUsers');

$routes->get('/admin/manage_courses', 'Auth::manageCourses');
$routes->post('/admin/manage_courses', 'Auth::manageCourses');

$routes->get('/teacher/courses', 'Auth::teacherCourses');
$routes->post('/teacher/courses', 'Auth::teacherCourses');

$routes->get('/student/courses', 'Auth::studentCourses');

$routes->post('/course/enroll', 'Course::enroll');
$routes->post('/course/unenroll', 'Course::unenroll');
$routes->get('/course/available', 'Course::getAvailableCourses');
$routes->post('/course/removeStudent', 'Course::removeStudent');
$routes->post('/course/addStudent', 'Course::addStudent');
$routes->get('/course/getAvailableStudents', 'Course::getAvailableStudents');
$routes->get('/course/search', 'Course::search');
$routes->post('/course/search', 'Course::search');

$routes->get('/material/upload/(:num)', 'Material::upload/$1');
$routes->post('/material/upload/(:num)', 'Material::upload/$1');
$routes->get('/material/delete/(:num)', 'Material::delete/$1');
$routes->get('/material/download/(:num)', 'Material::download/$1');
$routes->get('/material/view/(:num)', 'Material::view/$1');

// Notification routes
$routes->get('/notifications', 'Notifications::get');
$routes->post('/notifications/mark_read/(:num)', 'Notifications::mark_as_read/$1');

