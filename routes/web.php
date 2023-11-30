<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\StaticController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
// Home


Route::redirect("/", "/landing");

//Static Pages
Route::get('{page}', [StaticController::class, 'show'])->whereIn('page', StaticController::STATIC_PAGES)->name('static');

// Admin
Route::controller(UserController::class)->group(function () {
    Route::get('users/search', 'index')->name('search_users');
});
Route::prefix('/admin')->controller(AdminController::class)->group(function () {
    Route::redirect('/', '/admin/users')->name('admin');
    Route::get('/users', 'show')->name('admin_users');
    Route::get('/users/create', 'create');
    Route::post('/users/create', 'store')->name('admin_user_create');
});


// Authentication
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'authenticate');
    Route::get('/logout', 'logout')->name('logout');
});

// Sign-up
Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'showRegistrationForm')->name('register');
    Route::post('/register', 'register')->name('create_account');
});
// Profile
Route::prefix('/user-profile')->controller(ProfileController::class)->group(function () {
    Route::get('/{user}', 'showProfile')->name('profile');
    Route::put('/{user}/edit', 'updateProfile')->name('update_profile');
    Route::get('/{user}/edit', 'showEditProfile')->name('edit_profile');
});

// Decide which Home page to use
Route::controller(HomeController::class)->group(function () {
    Route::get('/homepage/{user}', 'showHome')->name('home');
    Route::get('/landing', 'showLanding')->name('landing');
});

// Projects
Route::prefix('/project')->group(function () {

    //Create projects
    Route::controller(ProjectController::class)->group(function () {
        Route::get('/new', 'create')->name('show_new');
        Route::post('/new', 'store')->name('action_new');
    });
    Route::prefix('/{project}')->where(['project' => '[0-9]+'])->group(function () {
        Route::controller(ProjectController::class)->group(function () {
            Route::get('', 'show')->name('project');
            Route::get('/team', 'show_team')->name('team');
            Route::post('team/add', 'add_user')->name('addUser');
            Route::delete('', 'destroy')->where('project', '[0-9]+')->name('delete_project');
            Route::get('/edit', 'edit')->whereNumber('project')->name('show_edit_project');
            Route::put('/edit', 'update')->whereNumber('project')->name('action_edit_project');

        });
        Route::prefix('/task')->controller(TaskController::class)->group(function () {
            Route::get('/{task}', 'show')->where('task', '[0-9]+')->name('task');
            Route::get('/search', 'index')->name('search_tasks');
            Route::get('/new', 'create')->name('createTask');
            Route::post('/new', 'store')->name('newTask');
        });
        Route::prefix('/tasks')->group(function () {
            Route::get('/search', [TaskController::class, 'index'])->name('search_tasks');
            Route::get('', [ProjectController::class, 'showTasks'])->where('project', '[0-9]+')->name('show_tasks');

        });
    });

});
