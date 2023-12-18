<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\StaticController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ForumController;
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


Route::redirect("/", 'home')->name('init_page');

// Decide which Home page to use
Route::controller(HomeController::class)->group(function () {
    Route::get('/home', 'show')->name('home');
});

// Recover password route
Route::middleware('guest')->group(function () {
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgetPasswordForm'])->name('pass.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'submitForgetPasswordForm'])->name('pass.email');
    Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('pass.reset');
    Route::post('/reset-password', [ForgotPasswordController::class, 'submitResetPasswordForm'])->name('pass.update');
});


//Static Pages
Route::get('/myProjects', [ProjectController::class, 'index'])->name('projects');

// Static Pages
Route::get('{page}', [StaticController::class, 'show'])->whereIn('page', StaticController::STATIC_PAGES)->name('static');

// API
Route::controller(TaskController::class)->group(function () {
    Route::get('/api/tasks', 'searchTasks')->name('search_tasks');
});

Route::controller(UserController::class)->group(function () {
    Route::get('/api/users', 'searchUsers')->name('search_users');
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
    Route::get('/', 'show')->name('user-profile');
    Route::get('/{user}', 'showProfile')->name('profile');
    Route::put('/{user}/edit', 'updateProfile')->name('update_profile');
    Route::get('/{user}/edit', 'showEditProfile')->name('edit_profile');
    Route::delete('/{user}/delete', 'destroy')->name('delete_profile');
});

// Files 
Route::controller(FileController::class)->group(function () {
    Route::post('/file/upload','upload')->name('upload_profile_file');
    Route::delete('/file/delete','delete')->name('delete_file');
});
// Users
Route::prefix('/user/{user}')->whereNumber('user')->controller(UserController::class)->group(function () {
    Route::delete('/delete', 'destroy')->name('delete_user');
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
            Route::post('/team/add', 'add_user')->name('addUser');
            Route::delete('team/leave', 'remove_user')->name('leave_project');
            Route::delete('', 'destroy')->name('delete_project');
            Route::get('/edit', 'edit')->name('show_edit_project');
            Route::put('/edit', 'update')->name('action_edit_project');
        });
        Route::prefix('/task')->controller(TaskController::class)->group(function () {
            Route::get('/search', 'index')->name('search_tasks');
            Route::get('/new', 'create')->name('createTask');
            Route::post('/new', 'store')->name('newTask');

            Route::prefix('/{task}')->whereNumber('task')->group(function () {
                Route::get('', 'show')->name('task');
                Route::put('/edit/status', 'editStatus')->name('edit_status');
            });
        });

        Route::get('/tasks', [ProjectController::class, 'showTasks'])->name('show_tasks');

        Route::prefix('/forum')->group(function () {
            Route::controller(ProjectController::class)->group(function () {
                Route::get('', 'showForum')->name('forum'); 
                Route::controller(PostController::class)->group(function () {

                    Route::post('/new', 'store')->name('create_post');

                    Route::prefix('/{post}')->whereNumber('post')->group(function() {
                        Route::put('/edit', 'update')->name('edit_post');
                        Route::delete('/delete', 'destroy')->name('delete_post');
                    });
                });
            });
        });
    });
});
