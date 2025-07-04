<?php


use App\Http\Controllers\AdminController;
use App\Http\Controllers\AppController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ViewController;
use App\Models\Tasks;
use Illuminate\Support\Benchmark;
use Illuminate\Support\Facades\Route;

////////////////////////////////////////////////////////////////---APP---
Route::middleware('auth')->group(function () {
    Route::get('/Home', [ViewController::class, 'HomeView'])->name('App-Home-View')->middleware('check.sidebar.access:Home');
    Route::get('/Scoreboard', [ViewController::class, 'ScoreboardView'])->name('App-Scoreboard-View')->middleware('check.sidebar.access:Scoreboard');
    Route::get('/Statistics', [ViewController::class, 'StatisticView'])->name('App-Statistics-View')->middleware('check.sidebar.access:Statistics');
    Route::get('/Statistics/ID/{id}', [ViewController::class, 'StatisticIDview'])->name('App-Statistics-ID-View')->middleware('check.sidebar.access:Statistics');
    Route::get('/Logout', [AuthController::class, 'logoutApp'])->name('App-Logout')->middleware('check.sidebar.access:Logout');

    Route::get('/Download/File/{md5file}/{id}', [AppController::class, 'DwnlFile'])->name('App-Download-File')->middleware('check.sidebar.access:Home');
    Route::post('/Home/Tasks/Check', [AppController::class, 'checkFlag'])->name('App-Check-Flag')->middleware('check.sidebar.access:Home');
});

////////////////////////////////////////////////////////////////---ADMIN---
Route::middleware('auth.admin')->group(function () {
    Route::get('/Admin', [ViewController::class, 'AdminHomeView'])->name('Admin-Home-View');
    Route::get('/Admin/Scoreboard', [ViewController::class, 'AdminScoreboardView'])->name('Admin-Scoreboard-View');
    Route::get('/Admin/Tasks', [ViewController::class, 'AdminTasksView'])->name('Admin-Tasks-View');
    Route::get('/Admin/Teams', [ViewController::class, 'AdminTeamsView'])->name('Admin-Teams-View');
    Route::get('/Admin/Settings', [ViewController::class, 'AdminSettingsView'])->name('Admin-Settings-View');
    Route::get('/Admin/Logout', [AuthController::class, 'logoutAdmin'])->name('Admin-Logout');

    Route::post('/Admin/Settings/Reset', [AdminController::class, 'SettingsReset'])->name('Admin-Settings-Reset');
    Route::post('/Admin/Settings/DeleteAll', [AdminController::class, 'SettingsDeleteAll'])->name('Admin-Settings-DeleteAll');
    Route::post('/Admin/Settings/Sidebars', [AdminController::class, 'SettingsSlidebars'])->name('Admin-Settings-Sidebars');
    Route::post('/Admin/Settings/СhangeRull', [AdminController::class, 'SettingsChngRules'])->name('Admin-Settings-Сhange-Rules');
    Route::post('/Admin/Settings/СhangeCategory', [AdminController::class, 'SettingsChngCategory'])->name('Admin-Settings-Сhange-Categories');


    Route::put('/Admin/Tasks/Add', [AdminController::class, 'AddTasks'])->name('Admin-Tasks-Add');
    Route::patch('/Admin/Tasks/Change', [AdminController::class, 'ChangeTasks'])->name('Admin-Tasks-Change');
    Route::delete('/Admin/Tasks/Delete', [AdminController::class, 'DeleteTasks'])->name('Admin-Tasks-Delete');

    Route::put('/Admin/Teams/Add', [AdminController::class, 'AddTeams'])->name('Admin-Teams-Add');
    Route::patch('/Admin/Teams/Change', [AdminController::class, 'ChangeTeams'])->name('Admin-Teams-Change');
    Route::delete('/Admin/Teams/Delete', [AdminController::class, 'DeleteTeams'])->name('Admin-Teams-Delete');
});

////////////////////////////////////////////////////////////////---GUEST---
Route::get('/', [ViewController::class, 'SlashView'])->name('login');
Route::get('/Auth', [ViewController::class, 'AuthView'])->name('App-Auth-View');
Route::get('/Rules', [ViewController::class, 'RulesView'])->name('App-Rules-View')->middleware('check.sidebar.access:Rules');
Route::get('/Projector', [ViewController::class, 'ProjectorView'])->name('App-Projector-View')->middleware('check.sidebar.access:Projector');
Route::get('/Admin/Auth', [ViewController::class, 'AdminAuthView'])->name('Admin-Auth-View');

Route::middleware('throttle:AuthApp')->group(function () {
    Route::post('/Auth', [AuthController::class, 'AuthApp']);
    Route::post('/Admin/Auth', [AuthController::class, 'AuthAdmin']);
});

////////////////////////////////////////////////////////////////---OTHER---
