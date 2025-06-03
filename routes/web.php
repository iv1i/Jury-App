<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AppController;
use App\Http\Controllers\TasksController;
use Illuminate\Support\Facades\Route;

////////////////////////////////////////////////////////////////---APP---
Route::middleware('auth')->group(function () {
    Route::get('/Home', [AppController::class, 'HomeView'])->name('Home');
    //Route::get('/Home/{id}', [AppController::class, 'TasksIDView'])->name('TasksID');
    Route::get('/Scoreboard', [AppController::class, 'ScoreboardView']);
    Route::get('/Statistics', [AppController::class, 'StatisticView']);
    Route::get('/Statistics/ID/{id}', [AppController::class, 'StatisticIDview']);
    Route::get('/Logout', [AppController::class, 'logout']);

    Route::get('/Download/File/{md5file}/{id}', [AppController::class, 'DwnlFile']);
    Route::post('/Home/Tasks/Check', [TasksController::class, 'CheckFlag']);
});

////////////////////////////////////////////////////////////////---ADMIN---
Route::middleware('auth.admin')->group(function () {
    Route::get('/Admin', [AdminController::class, 'AdminHomeView'])->name('AdminHome');
    Route::get('/Admin/Scoreboard', [AdminController::class, 'AdminScoreboardView'])->name('AdminScoreboard');
    Route::get('/Admin/Tasks', [AdminController::class, 'AdminTasksView'])->name('AdminTasks');
    Route::get('/Admin/Tasks/{id}', [AdminController::class, 'TaskID']);
    Route::get('/Admin/Teams', [AdminController::class, 'AdminTeamsView'])->name('AdminTeams');
    Route::get('/Admin/Teams/{id}', [AdminController::class, 'TeamsID']);
    Route::get('/Admin/Settings', [AdminController::class, 'AdminSettingsView']);

    Route::get('/Admin/Logout', [AdminController::class, 'logout']);
    Route::post('/Admin/Settings/Reset', [AdminController::class, 'SettingsReset'])->name('AdminSettingsReset');
    Route::post('/Admin/Settings/DeleteAll', [AdminController::class, 'SettingsDeleteAll'])->name('AdminSettingsDeleteAll');
    Route::post('/Admin/Settings/Slidebars', [AdminController::class, 'SettingsSlidebars'])->name('AdminSettingsSlidebars');
    Route::post('/Admin/Settings/ChngRull', [AdminController::class, 'SettingsChngRules'])->name('AdminSettingsChngRules');

    Route::put('/Admin/Tasks/Add', [AdminController::class, 'AddTasks']);
    Route::patch('/Admin/Tasks/Change', [AdminController::class, 'ChangeTasks']);
    Route::delete('/Admin/Tasks/Delete', [AdminController::class, 'DeleteTasks']);

    Route::put('/Admin/Teams/Add', [AdminController::class, 'AddTeams']);
    Route::patch('/Admin/Teams/Change', [AdminController::class, 'ChangeTeams']);
    Route::delete('/Admin/Teams/Delete', [AdminController::class, 'DeleteTeams']);
});
////////////////////////////////////////////////////////////////---GUEST---

Route::get('/', [AppController::class, 'SlashView'])->name('login');

Route::get('/Auth', [AppController::class, 'AuthView'])->name('AuthApp');
Route::get('/Rules', [AppController::class, 'RulesView']);

Route::get('/Projector', [AppController::class, 'ProjectorView']);
Route::get('/Admin/Auth', [AdminController::class, 'AdminAuthView'])->name('Adminlogin');
Route::middleware('throttle:AuthApp')->group(function () {
    Route::post('/Auth', [AppController::class, 'AuthTeam']);
    Route::post('/Admin/Auth', [AdminController::class, 'AdminAuth']);
});

////////////////////////////////////////////////////////////////---OTHER---
