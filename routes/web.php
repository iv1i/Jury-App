<?php


use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminViewController;
use App\Http\Controllers\AppController;
use App\Http\Controllers\AppViewController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GuestViewController;
use App\Http\Controllers\ViewController;

use Illuminate\Support\Facades\Route;

////////////////////////////////////////////////////////////////---APP---
Route::middleware('auth')->group(function () {
    Route::get('/Home', [AppViewController::class, 'home'])->name('App-Home-View')->middleware('check.sidebar.access:Home');
    Route::get('/Scoreboard', [AppViewController::class, 'scoreboard'])->name('App-Scoreboard-View')->middleware('check.sidebar.access:Scoreboard');
    Route::get('/Statistics', [AppViewController::class, 'statistic'])->name('App-Statistics-View')->middleware('check.sidebar.access:Statistics');
    Route::get('/Statistics/ID/{id}', [AppViewController::class, 'statisticById'])->name('App-Statistics-ID-View')->middleware('check.sidebar.access:Statistics');
    Route::get('/Logout', [AuthController::class, 'logoutApp'])->name('App-Logout')->middleware('check.sidebar.access:Logout');

    Route::get('/Download/File/{md5file}/{id}', [AppController::class, 'downloadFile'])->name('App-Download-File')->middleware('check.sidebar.access:Home');
    Route::post('/Home/Tasks/Check', [AppController::class, 'checkFlag'])->name('App-Check-Flag')->middleware('check.sidebar.access:Home');

    //Route::get('/Home/{id}', [ViewController::class, 'tasksIDView'])->name('TasksID');

});

////////////////////////////////////////////////////////////////---ADMIN---
Route::middleware('auth.admin')->group(function () {
    Route::get('/Admin', [AdminViewController::class, 'adminHomeView'])->name('Admin-Home-View');
    Route::get('/Admin/Scoreboard', [AdminViewController::class, 'adminScoreboardView'])->name('Admin-Scoreboard-View');
    Route::get('/Admin/Tasks', [AdminViewController::class, 'adminTasksView'])->name('Admin-Tasks-View');
    Route::get('/Admin/Teams', [AdminViewController::class, 'adminTeamsView'])->name('Admin-Teams-View');
    Route::get('/Admin/Settings', [AdminViewController::class, 'adminSettingsView'])->name('Admin-Settings-View');
    Route::get('/Admin/Logout', [AuthController::class, 'logoutAdmin'])->name('Admin-Logout');

    Route::post('/Admin/Settings/Reset', [AdminController::class, 'settingsReset'])->name('Admin-Settings-Reset');
    Route::post('/Admin/Settings/DeleteAll', [AdminController::class, 'settingsDeleteAll'])->name('Admin-Settings-DeleteAll');
    Route::post('/Admin/Settings/Sidebars', [AdminController::class, 'settingsSidebars'])->name('Admin-Settings-Sidebars');
    Route::post('/Admin/Settings/СhangeRull', [AdminController::class, 'settingsChangeRules'])->name('Admin-Settings-Сhange-Rules');
    Route::post('/Admin/Settings/СhangeCategory', [AdminController::class, 'settingsChangeCategory'])->name('Admin-Settings-Сhange-Categories');

    Route::post('/Admin/Tasks/Add', [AdminController::class, 'addTasks'])->name('Admin-Tasks-Add');
    Route::post('/Admin/Tasks/Change', [AdminController::class, 'changeTasks'])->name('Admin-Tasks-Change');
    Route::post('/Admin/Tasks/Delete', [AdminController::class, 'deleteTasks'])->name('Admin-Tasks-Delete');

    Route::post('/Admin/Teams/Add', [AdminController::class, 'addTeams'])->name('Admin-Teams-Add');
    Route::post('/Admin/Teams/Change', [AdminController::class, 'changeTeams'])->name('Admin-Teams-Change');
    Route::post('/Admin/Teams/Delete', [AdminController::class, 'deleteTeams'])->name('Admin-Teams-Delete');

    //Route::get('/Admin/Tasks/{id}', [AdminController::class, 'TaskID']);
    //Route::get('/Admin/Teams/{id}', [AdminController::class, 'TeamsID']);

});

////////////////////////////////////////////////////////////////---GUEST---
Route::get('/', [ViewController::class, 'slashView'])->name('login');
Route::get('/Auth', [GuestViewController::class, 'authView'])->name('App-Auth-View');
Route::get('/Rules', [GuestViewController::class, 'rulesView'])->name('App-Rules-View')->middleware('check.sidebar.access:Rules');
Route::get('/Projector', [GuestViewController::class, 'projectorView'])->name('App-Projector-View')->middleware('check.sidebar.access:Projector');
Route::get('/Admin/Auth', [GuestViewController::class, 'adminAuthView'])->name('Admin-Auth-View');

Route::middleware('throttle:AuthApp')->group(function () {
    Route::post('/Auth', [AuthController::class, 'authApp']);
    Route::post('/Admin/Auth', [AuthController::class, 'authAdmin']);
});

////////////////////////////////////////////////////////////////---OTHER---
