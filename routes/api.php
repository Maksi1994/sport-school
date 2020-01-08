<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
  'prefix' => 'players'
], function() {
  Route::post('save', 'PlayersController@save');
  Route::get('get-players-by-season/{season_id}', 'PlayersController@getPlayersBySeason');
  Route::get('get-one/{id}', 'PlayersController@getOne');
  Route::get('delete/{id}', 'PlayersController@delete');
});


Route::group([
  'prefix' => 'seasons'
], function() {
  Route::post('save', 'SeasonsController@save');
  Route::post('get-seasons-rating', 'SeasonsController@getSeasonsRating');
  Route::get('get-one/{id}', 'SeasonsController@getOne');
  Route::get('get-all', 'SeasonsController@getAll');
  Route::get('delete/{id}', 'SeasonsController@delete');
});


Route::group([
  'prefix' => 'teams'
], function() {
  Route::post('save-players-in-season', 'TeamsController@savePlayersInSeason');
  Route::post('save', 'TeamsController@save');
  Route::get('get-one/{id}', 'TeamsController@getOne');
  Route::get('delete/{id}', 'TeamsController@delete');
  Route::get('get-teams-by-season/{id}', 'TeamsController@getTeamsBySeason');
});

Route::group([
  'prefix' => 'stats'
], function() {
  Route::get('get-players-stats/{field}', 'StatsController@getPlayersStats');
});
