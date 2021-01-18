<?php

namespace App\Http\Controllers\Utilisateur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MediaModel;
use App\Models\AgendaModel;
use App\Models\SceneModel;
use App\Models\ModuleModel;

class UtilisateurController extends Controller
{

  // Get -------------------------------------------------------

    public function getFichiers(){
        $id_entite = intval($_POST['id_entite']);
        $access_level = intval($_POST['access_level']);
        $id_user = intval($_POST['id_user']);

        $fichiers = MediaModel::where("id_entite", $id_entite)->get();

        // $id_entite =1;
        return response()->json($fichiers,200);
    }

    public function getScenes(){
        $id_entite = intval($_POST['id_entite']);
        $scenes = SceneModel::where("id_entite", $id_entite)->get();
        return response()->json($scenes,200);
    }

    public function getModules(){
        $id_entite = intval($_POST['id_entite']);
        $modules = ModuleModel::where("id_entite", $id_entite)->get();
        return response()->json($modules,200);
    }

    public function getAgendas(){
      $id_entite = intval($_POST['id_entite']);
      $agendas = AgendaModel::where("id_entite", $id_entite)->get();
      return response()->json($agendas,200);
    }



    // record (save) -------------------------------------------------

    public function saveFichiers(Request $request ){
        $nom = $_POST['nom'];
        $id_entite = intval($_POST['id_entite']);
        $id_media  =  intval($_POST['id_media']);
        $type = $_POST['type'];
        $taille = intval($_POST['taille']);

        $rep="non";
        $fichier = null;

        $isExist = MediaModel::where(['nom' => $nom,'id_entite' => $id_entite])->get();

        if(count($isExist)==0){
        $redDatas = [
          'nom'=>$nom,
          'id_media'=>$id_media,
          'id_entite'=>$id_entite,
          'type'=>$type,
          'taille'=>$taille
        ];

        $fichier = MediaModel::create($redDatas);
        $rep="oui";
      }

      return response()->json(array("data"=>$fichier,"statut"=>$rep),200);
    }

    public function saveScenes(Request $request ){
        $nom = $_POST['nom'];
        $id_entite = intval($_POST['id_entite']);
        $id_scene  =  1;
        $duree  =  0;

        $scene = SceneModel::where(['nom' => $nom])->get();
        $statut = "non";

        if(count($scene)==0){
          $redDatas = [
            'nom'=>$nom,
            'id_entite'=>$id_entite,
            'id_scene'=>$id_scene,
            'duree'=>$duree,
          ];

          $scene = SceneModel::create($redDatas);
          $statut = "oui";
        }

      return response()->json(array("scene"=>$scene,"statut"=>$statut),200);
    }

    public function saveModules(Request $request ){
        $module = ModuleModel::create($request->all());
        return response()->json($module,200);
    }

    public function saveAgendas(Request $request ){
        $nom = $_POST['nom'];
        $id_entite = intval($_POST['id_entite']);
        $id_agenda  =  1;

        $rep="non";
        $agenda = null;

        $isExist = AgendaModel::where(['nom' => $nom,'id_entite' => $id_entite])->get();

        if(count($isExist)==0){

          $redDatas = [
            'nom'=>$nom,
            'id_entite'=>$id_entite,
            'id_agenda'=>$id_agenda
          ];

          $rep="oui";
          $agenda = AgendaModel::create($redDatas);
        }

        return response()->json(array("agenda"=>$agenda,"statut"=>$rep),200);
    }

    // Update () -------------------------------------------------

    public function updateFichiers(Request $request ,MediaModel $media){
        $fichier =  $media->update($request->all());
        return response()->json($fichier,200);
    }

    public function updateScenes(Request $request ){
        $scene = SceneModel::update($request->all());
        return response()->json($scene,200);
    }

    public function updateModules(Request $request ){
        $module = ModuleModel::update($request->all());
        return response()->json($module,200);
    }

    public function updateAgendas(Request $request ){
        $agenda = AgendaModel::update($request->all());
        return response()->json($agenda,200);
    }

    //  Delete ------------------------

    public function SupprimerFichier(Request $request){
        // $groupe = GroupeModel::create($request->all());

        $id = intval($_POST['id']);

        $delete = MediaModel::where('id', $id)
          ->delete();

        return response()->json($delete,200);
    }


    public function SupprimerScene(Request $request){
        // $groupe = GroupeModel::create($request->all());

        $id = intval($_POST['id']);

        $delete = SceneModel::where('id', $id)
          ->delete();

        return response()->json($delete,200);
    }

    public function SupprimerAllScenes(Request $request){
        // $groupe = GroupeModel::create($request->all());

          $ids = explode(",",$_POST['ids']);
          $rep = array();

          foreach ($ids as $value){
              $id = intval($value);

              $delete = SceneModel::where('id', $id)
                ->delete();

              array_push($rep,  $delete);
          }
        //
        // $delete = SceneModel::where('id', $id)
        //   ->delete();

        return response()->json($rep,200);
    }

    public function SupprimerAgenda(Request $request){
        // $groupe = GroupeModel::create($request->all());

        $id = intval($_POST['id']);

        $delete = AgendaModel::where('id', $id)
          ->delete();

        return response()->json($delete,200);
    }

    public function SupprimerAllAgendas(Request $request){
        // $groupe = GroupeModel::create($request->all());

          $ids = explode(",",$_POST['ids']);
          $rep = array();

          foreach ($ids as $value){
              $id = intval($value);

              $delete = AgendaModel::where('id', $id)
                ->delete();

              array_push($rep,  $delete);
          }
        //
        // $delete = SceneModel::where('id', $id)
        //   ->delete();

        return response()->json($rep,200);
    }
}
