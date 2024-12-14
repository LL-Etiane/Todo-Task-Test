<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskManagementController;

Route::apiResource('tasks', TaskManagementController::class);
