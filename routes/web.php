<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use App\Models\Chart;
use App\Models\ChartRecord;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/charts/{id}', function($id) {
    $chart = Chart::findOrFail($id);
    $chart->chart_records = ChartRecord::where('chart_id', $id)->get(['data']);
    return $chart->toJson(JSON_PRETTY_PRINT);
});
