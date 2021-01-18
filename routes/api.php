<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('utilisateur')->group(function () {
    // Get-----------------------------------------------------------
    Route::post('getFichiers','Utilisateur\UtilisateurController@getFichiers');
    Route::post('getScenes','Utilisateur\UtilisateurController@getScenes');
    Route::post('getModules','Utilisateur\UtilisateurController@getModules');
    Route::post('getAgendas','Utilisateur\UtilisateurController@getAgendas');

    // record (save) -------------------------------------------------

    Route::post('saveFichiers','Utilisateur\UtilisateurController@saveFichiers');
    Route::post('saveScenes','Utilisateur\UtilisateurController@saveScenes');
    Route::post('saveModules','Utilisateur\UtilisateurController@saveModules');
    Route::post('saveAgendas','Utilisateur\UtilisateurController@saveAgendas');


    // Update (save) -------------------------------------------------

    Route::put('updateFichiers/{id}','Utilisateur\UtilisateurController@updateFichiers');
    Route::put('updateScenes/{id}','Utilisateur\UtilisateurController@updateScenes');
    Route::put('updateModules/{id}','Utilisateur\UtilisateurController@updateModules');
    Route::put('updateAgendas/{id}','Utilisateur\UtilisateurController@updateAgendas');
    Route::post('editAgendas','Utilisateur\UtilisateurController@editAgendas');
    Route::post('editAgendasEven','Utilisateur\UtilisateurController@editAgendasEven');
    Route::post('editAgendasNom','Utilisateur\UtilisateurController@editAgendasNom');
    Route::post('deleteAgendasEven','Utilisateur\UtilisateurController@deleteAgendasEven');

    // record (save) -------------------------------------------------

    Route::post('SupprimerFichier','Utilisateur\UtilisateurController@SupprimerFichier');
    Route::post('SupprimerScene','Utilisateur\UtilisateurController@SupprimerScene');
    Route::post('SupprimerAllScenes','Utilisateur\UtilisateurController@SupprimerAllScenes');
    Route::post('SupprimerAgenda','Utilisateur\UtilisateurController@SupprimerAgenda');
    Route::post('SupprimerAllAgendas','Utilisateur\UtilisateurController@SupprimerAllAgendas');
});

Route::prefix('administrateur')->group(function () {
    // Get-----------------------------------------------------------
    Route::post('getUtilisateurs','Administrateur\AdminController@getUtilisateurs');
    Route::post('getGroupes','Administrateur\AdminController@getGroupes');
    Route::post('getModules','Administrateur\AdminController@getModules');
    Route::post('getUsers','Administrateur\AdminController@getUsers');
    Route::post('getEntites','Administrateur\AdminController@getEntites');


    // record (save) -------------------------------------------------

    Route::post('saveUtilisateurs','Administrateur\AdminController@saveUtilisateurs');
    Route::post('saveGroupes','Administrateur\AdminController@saveGroupes');
    Route::post('saveNewModule','Administrateur\AdminController@saveNewModule');
    Route::post('retirerModule','Administrateur\AdminController@retirerModule');
    Route::post('affecterModule','Administrateur\AdminController@affecterModule');

    // Update (save) -------------------------------------------------

    Route::post('updateUtilisateurs/','Administrateur\AdminController@updateUtilisateurs');
    Route::put('updateGroupes/{id}','Administrateur\AdminController@updateGroupes');
    Route::post('updateDroits','Administrateur\AdminController@updateDroits');
    Route::post('updateDroitsGroupe','Administrateur\AdminController@updateDroitsGroupe');
    Route::post('updateBloquer','Administrateur\AdminController@updateBloquer');
    Route::post('updateDebloquer','Administrateur\AdminController@updateDebloquer');
    Route::post('updateGroupes','Administrateur\AdminController@updateGroupes');
    Route::post('updateUsersGroupe','Administrateur\AdminController@updateUsersGroupe');
    Route::post('editGroupes','Administrateur\AdminController@editGroupes');
    Route::post('setEntite','Administrateur\AdminController@setEntite');
    Route::post('sauvegarderSceneChange','Administrateur\AdminController@sauvegarderSceneChange');

    // Delete (save) -------------------------------------------------


    Route::post('supprimerUser','Administrateur\AdminController@supprimerUser');
    Route::post('supprimerAllUser','Administrateur\AdminController@supprimerAllUser');
    Route::post('supprimerGroupeUsers','Administrateur\AdminController@supprimerGroupeUsers');
    Route::post('supprimerAllGroupeUsers','Administrateur\AdminController@supprimerAllGroupeUsers');
    Route::post('deleteEntite','Administrateur\AdminController@deleteEntite');
    Route::post('deleteEntites','Administrateur\AdminController@deleteEntites');



});

Route::prefix('util')->group(function () {
	//header("Access-Control-Allow-Origin: *");
        //header("Access-Control-Allow-Headers: Content-Type");
    Route::post('uploadImage','Util\UtilController@uploadImage');
    Route::post('login','Util\UtilController@login');
    Route::post('getUser','Util\UtilController@getUser');
    Route::post('saveChange','Util\UtilController@saveChange');

});
Route::prefix('module')->group(function(){
    Route::post('getScene','Module\ModuleController@getScene');
    Route::post('delete','Module\ModuleController@deleteModule');
    Route::post('stopDiffusion','Module\ModuleController@stopDiffusion');
    Route::post('autoriserDiffusion','Module\ModuleController@autoriserDiffusion');
    Route::post('getServer','Module\ModuleController@getServer');
});
Route::prefix('super')->group(function(){
    Route::post('addserver','Super\SuperController@addServer');
    Route::post('getServer','Super\SuperController@getServer');
    Route::post('deleteServer','Super\SuperController@deleteServer');
    Route::post('saveUpdateServer','Super\SuperController@updateServer');
    Route::post('getModule','Super\SuperController@getModule');
});
