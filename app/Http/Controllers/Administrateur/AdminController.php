<?php

namespace App\Http\Controllers\Administrateur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\LinkuserdroitModel;
use App\Models\UsersModel;
use App\Models\GroupeModel;
use App\Models\ModuleModel;

use App\Models\GroupeusersModel;
use App\Models\GroupeusersdroitModel;
use App\Models\LinkgroupeusersModel;
use App\Models\LinkmodulesusersModel;
use App\Models\EntiteModel;

use App\Models\MediaModel;
use App\Models\AgendaModel;
use App\Models\SceneModel;
use App\Models\linkserverentiteModel;
use App\Models\serversModel;
use App\Models\linkscenemedia;


class AdminController extends Controller
{
    public function getUtilisateurs(){

        $access_level = intval($_POST['access_level']);
        $id_entite = intval($_POST['id_entite']);
        $id_user = intval($_POST['id_user']);

        $users = array();


        if($access_level==2){
          $access_level_admin = intval($_POST['access_level_admin']);
          if($access_level_admin==2){
              $users = UsersModel::where('access_level','<',3)
              ->where('access_level_admin','!=',$access_level_admin)
              ->where('id_user','!=',$id_user)
              ->where('id_entite',$id_entite)
              ->get();
          }else{
            $users = UsersModel::where('access_level','<',$access_level)
            ->where('id_user','!=',$id_user)
            ->where('id_entite',$id_entite)
            ->get();
          }
        }


        if($access_level==3){
          $users = UsersModel::where('id_user','!=',$id_user)
          ->where('access_level','<',$access_level)
          ->get();
        }

        $datas = array();

        foreach ($users as $value){
              $droit = LinkuserdroitModel::where('id_user',$value->id_user)->get();

              $value->droit = $droit;
               array_push($datas,  $value);


               $groupes = LinkgroupeusersModel::where('id_user',$value->id_user)->get();
               $value->groupes = $groupes;

               $modules = LinkmodulesusersModel::where('id_user',$value->id_user)->get();
               $value->modules = $modules;

               $entite = EntiteModel::where('id_entite',$value->id_entite)->get();
               $value->entite = $entite[0]->nom;
        }
        return response()->json( $datas,200);
    }

    public function getGroupes(){
        $id_entite = intval($_POST['id_entite']);
        $groupes = GroupeusersModel::where("id_entite", $id_entite)->get();
        $datas = array();


        foreach ($groupes as $value){
              $droit = GroupeusersdroitModel::where('id_groupeusers',$value->id)->get();
              $value->droit = $droit;

              $usersId = LinkgroupeusersModel::where('id_goupeusers',$value->id)->get();

              $users = array();
              foreach ($usersId as $userid){
                $user = UsersModel::where('id_user',$userid->id_user)->get();
                array_push($users,  $user);
              }

              $value->users = $users;

               array_push($datas,  $value);
        }

        return response()->json( $datas,200);
    }


    public function getEntites(){
        $entites = EntiteModel::get();

        $datas = array();

        foreach ($entites as $value){

              $d = UsersModel::where('id_entite',$value->id_entite)->get();
              $value->users = count($d);

              $d = ModuleModel::where('id_entite',$value->id_entite)->get();
              $value->modules = count($d);


              $d = GroupeModel::where('id_entite',$value->id_entite)->get();
              $value->groupes = count($d);

              $d = MediaModel::where('id_entite',$value->id_entite)->get();
              $value->fichiers = count($d);

              $d = AgendaModel::where('id_entite',$value->id_entite)->get();
              $value->agendas = count($d);

              $d = SceneModel::where('id_entite',$value->id_entite)->get();
              $value->scenes = count($d);

              //$admin = UsersModel::where(['id_entite'=>$value->id_entite,'access_level_admin'=>2])->get();
             // $value->admin = $admin[0];
              $value->admin=UsersModel::where(['id_entite'=>$value->id_entite,'access_level_admin'=>2])->get()->first();


              array_push($datas,  $value);

        }

        return response()->json( $datas,200);
    }


    // record (save) -------------------------------------------------

    public function saveUtilisateurs(Request $request){
        // $user = UsersModel::create($request->all());

        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $telephone  =  $_POST['telephone'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $id_entite = intval($_POST['id_entite']);
        $my_level = intval($_POST['access_level']);
        $access_level = 0;
        $d = 'non';
        $nom_entite=null;
        $access_level_admin=null;

        $rep="non";
        $user = null;

        $isExist = UsersModel::where(['email' => $email,'id_entite' => $id_entite])->get();

        if(count($isExist)==0){
                if($my_level==2){
                    $access_level_admin= intval($_POST['access_level_admin']);
                    if($access_level_admin==1)
                    {
                      $access_level = 2;
                      $d = 'oui';
                    }
                    else
                    {
                       $access_level = 1;
                       $d = 'non';
                     }
                }
                else if($my_level==3){
                    $access_level = 2;
                    $access_level_admin=2;
                    $nom_entite = $_POST['nom_entite'];
                    $d = 'oui';

                    $redDatasEntite = [
                     'id_entite'=>\rand(1,1000),
                      'nom'=>$nom_entite,
                    ];

                    $entite = EntiteModel::create($redDatasEntite);
                    $id_entite = $entite->id_entite;
                }

                $etat = 1;

                $redDatas = [
                  'id_user'=>\rand(1,1000),
                  'id_entite'=>$id_entite,
                  'access_level'=>$access_level,
                  'access_level_admin'=>$access_level_admin,
                  'nom'=>$nom,
                  'prenom'=>$prenom,
                  'tel'=>$telephone,
                  'email'=>$email,
                  'password'=>$password,
                  'etat'=>$etat,
                ];

                $user = UsersModel::create($redDatas);

                $reqDroits = [
                  'id_user'=>$user->id_user,
                  'type_cible'=>'scene',
                  'id_cible'=>0,
                  'droit'=>$d,
                ];

                $droit = LinkuserdroitModel::create($reqDroits);

                $user->droitScene = $droit;

                $reqDroits = [
                  'id_user'=>$user->id_user,
                  'type_cible'=>'fichier',
                  'id_cible'=>0,
                  'droit'=>$d,
                ];

                $droit = LinkuserdroitModel::create($reqDroits);

                $user->droitFichier = $droit;

                $reqDroits = [
                  'id_user'=>$user->id_user,
                  'type_cible'=>'agenda',
                  'id_cible'=>0,
                  'droit'=>$d,
                ];

                $droit = LinkuserdroitModel::create($reqDroits);

                $user->droitAgenda = $droit;

                // -------------------------------------------------------------------

                $reqDroits = [
                  'id_user'=>$user->id_user,
                  'type_cible'=>'sceneEdit',
                  'id_cible'=>0,
                  'droit'=>$d,
                ];

                $droit = LinkuserdroitModel::create($reqDroits);

                $user->droitScene = $droit;

                $reqDroits = [
                  'id_user'=>$user->id_user,
                  'type_cible'=>'fichierEdit',
                  'id_cible'=>0,
                  'droit'=>$d,
                ];

                $droit = LinkuserdroitModel::create($reqDroits);

                $user->droitFichier = $droit;

                $reqDroits = [
                  'id_user'=>$user->id_user,
                  'type_cible'=>'agendaEdit',
                  'id_cible'=>0,
                  'droit'=>$d,
                ];

                $droit = LinkuserdroitModel::create($reqDroits);

                $user->droitAgenda = $droit;

                // -------------------------------------------------------------------

                $reqDroits = [
                  'id_user'=>$user->id_user,
                  'type_cible'=>'sceneDelete',
                  'id_cible'=>0,
                  'droit'=>$d,
                ];

                $droit = LinkuserdroitModel::create($reqDroits);

                $user->droitScene = $droit;

                $reqDroits = [
                  'id_user'=>$user->id_user,
                  'type_cible'=>'fichierDelete',
                  'id_cible'=>0,
                  'droit'=>$d,
                ];

                $droit = LinkuserdroitModel::create($reqDroits);

                $user->droitFichier = $droit;

                $reqDroits = [
                  'id_user'=>$user->id_user,
                  'type_cible'=>'agendaDelete',
                  'id_cible'=>0,
                  'droit'=>$d,
                ];

                $droit = LinkuserdroitModel::create($reqDroits);

                $user->droitAgenda = $droit;

                // ----------------------------------------------

                $reqDroits = [
                  'id_user'=>$user->id_user,
                  'type_cible'=>'module',
                  'id_cible'=>0,
                  'droit'=>$d,
                ];

                $droit = LinkuserdroitModel::create($reqDroits);

                $user->droitModule = $droit;

                $reqDroits = [
                  'id_user'=>$user->id_user,
                  'type_cible'=>'moduleEdit',
                  'id_cible'=>0,
                  'droit'=>$d,
                ];

                $droit = LinkuserdroitModel::create($reqDroits);

                $user->droitModule = $droit;

                $reqDroits = [
                  'id_user'=>$user->id_user,
                  'type_cible'=>'moduleDelete',
                  'id_cible'=>0,
                  'droit'=>$d,
                ];

                $droit = LinkuserdroitModel::create($reqDroits);

                $user->droitModule = $droit;



                $rep="oui";

        }

        return response()->json(array("user"=>$user,"statut"=>$rep),200);
    }

    public function saveGroupes(Request $request){
        // $groupe = GroupeModel::create($request->all());

        $nom = $_POST['nom'];
        $id_user = intval($_POST['id_user']);
        $access_level  =  intval($_POST['access_level']);
        $id_entite = intval($_POST['id_entite']);
        $d = 'non';

        $groupe = null;
        $statut = "non";

        $isExist =  GroupeusersModel::where(['nom' => $nom,'id_entite' => $id_entite])->get();

        if(count($isExist)==0){
                $redDatas = [
                  'nom'=>$nom,
                  'id_entite'=>$id_entite,
                ];

                $groupe = GroupeusersModel::create($redDatas);

                $reqDroits = [
                  'id_groupeusers'=>$groupe->id,
                  'type_cible'=>'scene',
                  'id_cible'=>0,
                  'droit'=>$d,
                ];

                $droit = GroupeusersdroitModel::create($reqDroits);

                $groupe->droitScene = $droit;

                $reqDroits = [
                  'id_groupeusers'=>$groupe->id,
                  'type_cible'=>'fichier',
                  'id_cible'=>0,
                  'droit'=>$d,
                ];

                $droit = GroupeusersdroitModel::create($reqDroits);

                $groupe->droitFichier = $droit;

                $reqDroits = [
                  'id_groupeusers'=>$groupe->id,
                  'type_cible'=>'agenda',
                  'id_cible'=>0,
                  'droit'=>$d,
                ];

                $droit = GroupeusersdroitModel::create($reqDroits);

                $groupe->droitAgenda = $droit;

                $reqDroits = [
                  'id_groupeusers'=>$groupe->id,
                  'type_cible'=>'sceneEdit',
                  'id_cible'=>0,
                  'droit'=>$d,
                ];

                $droit = GroupeusersdroitModel::create($reqDroits);

                $groupe->droitSceneEdit = $droit;

                $reqDroits = [
                  'id_groupeusers'=>$groupe->id,
                  'type_cible'=>'fichierEdit',
                  'id_cible'=>0,
                  'droit'=>$d,
                ];

                $droit = GroupeusersdroitModel::create($reqDroits);

                $groupe->droitFichierEdit = $droit;

                $reqDroits = [
                  'id_groupeusers'=>$groupe->id,
                  'type_cible'=>'agendaEdit',
                  'id_cible'=>0,
                  'droit'=>$d,
                ];

                $droit = GroupeusersdroitModel::create($reqDroits);

                $groupe->droitAgendaEdit = $droit;

                $reqDroits = [
                  'id_groupeusers'=>$groupe->id,
                  'type_cible'=>'sceneDelete',
                  'id_cible'=>0,
                  'droit'=>$d,
                ];

                $droit = GroupeusersdroitModel::create($reqDroits);

                $groupe->droitSceneDelete = $droit;

                $reqDroits = [
                  'id_groupeusers'=>$groupe->id,
                  'type_cible'=>'fichierDelete',
                  'id_cible'=>0,
                  'droit'=>$d,
                ];

                $droit = GroupeusersdroitModel::create($reqDroits);

                $groupe->droitFichierDelete = $droit;

                $reqDroits = [
                  'id_groupeusers'=>$groupe->id,
                  'type_cible'=>'agendaDelete',
                  'id_cible'=>0,
                  'droit'=>$d,
                ];

                $droit = GroupeusersdroitModel::create($reqDroits);

                $groupe->droitAgendaDelete = $droit;


              $statut = "oui";
          }

          return response()->json(array("data"=>$groupe,"statut"=>$statut),200);
    }

    // Update () -------------------------------------------------

    public function updateUtilisateurs(Request $request){


      $id_user = intval($_POST['id_user']);
      $id_entite = intval($_POST['id_entite']);
      $nom = $_POST['nom'];
      $prenom = $_POST['prenom'];
      $telephone  =  $_POST['telephone'];
      $email = $_POST['email'];
      $password = $_POST['password'];
      $access_level_user= intval($_POST['access_level_user']);

      $rep="non";
      $user = null;

      $isExist = UsersModel::where(['email' => $email,'id_entite' => $id_entite])->get();

      if(count($isExist)==0){
          $redDatas = [
            'nom'=>$nom,
            'prenom'=>$prenom,
            'tel'=>$telephone,
            'email'=>$email,
            'password'=>$password,
            'access_level'=>$access_level_user,
          ];

          $user = UsersModel::where('id_user', $id_user)
            ->update($redDatas);

          $rep="oui";
      }

      return response()->json(array("user"=>$user,"statut"=>$rep),200);
    }

    public function updateGroupes(Request $request){
        // $groupe = GroupeModel::create($request->all());
        $rep = array();
        $id_user = intval($_POST['id_user']);


        if(strcmp( $_POST['groupes'] ,"") !=0){
          $groupes = explode(",",$_POST['groupes']);
          $del = LinkgroupeusersModel::where('id_user', $id_user)->delete();

          foreach ($groupes as $value){
              $id_goupeusers = intval($value);
              $colouns =  [
                'id_goupeusers'=>$id_goupeusers,
                'id_user'=>$id_user,
              ];

              $linkgroupeusers = LinkgroupeusersModel::create($colouns);
              array_push($rep,  $linkgroupeusers);
          }
        }

        if(strcmp( $_POST['modules'] ,"") !=0){

            $modules = explode(",",$_POST['modules']);

            $del = LinkmodulesusersModel::where('id_user', $id_user)->delete();
            foreach ($modules as $value){
                $id_module = intval($value);
                $colouns =  [
                  'id_module'=>$id_module,
                  'id_user'=>$id_user,
                ];

                $linkmodulesusers = LinkmodulesusersModel::create($colouns);
                array_push($rep,  $linkmodulesusers);
            }
        }




        return response()->json($rep,200);
    }

    public function updateUsersGroupe(Request $request){
        // $groupe = GroupeModel::create($request->all());
        $rep = array();
        $id_groupe = intval($_POST['id_groupe']);


        if(strcmp( $_POST['users'] ,"") !=0){
          $users = explode(",",$_POST['users']);
          $del = LinkgroupeusersModel::where('id_goupeusers', $id_groupe)->delete();

          foreach ($users as $value){
              $id_user = intval($value);
              $colouns =  [
                'id_goupeusers'=>$id_groupe,
                'id_user'=>$id_user,
              ];

              $linkgroupeusers = LinkgroupeusersModel::create($colouns);
              array_push($rep,  $linkgroupeusers);
          }
        }





        return response()->json($rep,200);
    }



    public function setEntite(Request $request){
        // $groupe = GroupeModel::create($request->all());
        $entiteId = intval($_POST['entiteId']);
        $userId = intval($_POST['userId']);
        $id_entite = intval($_POST['id_entite']);
        $access_level = intval($_POST['access_level']);
        $id_user = intval($_POST['id_user']);
        $nom_entite = $_POST['nom_entite'];

        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $telephone  =  $_POST['telephone'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        $get = EntiteModel::where('nom',$nom_entite)
        ->where('id_entite','!=',$entiteId)->get();

        $rep = "non";
        $message = "";
        $update = null;

        if(count($get)==0){
            $update = EntiteModel::where('id_entite', $entiteId)->update(['nom'=>$nom_entite]);

            $isExist = UsersModel::where('email', $email)
            ->where('id_user','!=', $userId)->get();
            $rep = "oui";

            if(count($isExist)==0){
                $redDatas = [
                  'nom'=>$nom,
                  'prenom'=>$prenom,
                  'tel'=>$telephone,
                  'email'=>$email,
                  'password'=>$password,
                ];

                $user = UsersModel::where('id_user', $userId)
                  ->update($redDatas);

                $rep = "oui";
            }else{
              $rep = "non";
              $message = "Ce adresse existe déja !";
            }

              if($update!=1){
                $rep = "oui";
              }

        }else{
          $rep = "non";
          $message = "Ce nom existe déja !";
        }

        return response()->json(array('update'=>$update,'statut'=>$rep,'message'=>$message),200);
    }

    public function updateDroits(Request $request){
        // $groupe = GroupeModel::create($request->all());

        $fichier = $_POST['fichier'];
        $scene = $_POST['scene'];
        $agenda  =  $_POST['agenda'];
        $fichierEdit = $_POST['fichierEdit'];
        $sceneEdit = $_POST['sceneEdit'];
        $agendaEdit  =  $_POST['agendaEdit'];
        $fichierDelete = $_POST['fichierDelete'];
        $sceneDelete = $_POST['sceneDelete'];
        $agendaDelete  =  $_POST['agendaDelete'];

        $module  =  $_POST['module'];
        $moduleEdit  =  $_POST['moduleEdit'];
        $moduleDelete  =  $_POST['moduleDelete'];

        $id_user = intval($_POST['id_user']);

        $getDroit =  LinkuserdroitModel::where('id_user', $id_user)
          ->where('type_cible', 'fichier')
          ->get();

        if(count($getDroit)!=0){
            $updateDroit = LinkuserdroitModel::where('id_user', $id_user)
              ->where('type_cible', 'fichier')
              ->update(['droit' => $fichier]);
        }else{
          $reqDroits = [
            'id_user'=>$id_user,
            'type_cible'=>'fichier',
            'id_cible'=>0,
            'droit'=>$fichier,
          ];

          $droit = LinkuserdroitModel::create($reqDroits);
        }

        $updateDroit = LinkuserdroitModel::where('id_user', $id_user)
          ->where('type_cible', 'scene')
          ->update(['droit' => $scene]);

        $updateDroit = LinkuserdroitModel::where('id_user', $id_user)
          ->where('type_cible', 'agenda')
          ->update(['droit' => $agenda]);

        $updateDroit = LinkuserdroitModel::where('id_user', $id_user)
          ->where('type_cible', 'fichierEdit')
          ->update(['droit' => $fichierEdit]);

        $updateDroit = LinkuserdroitModel::where('id_user', $id_user)
          ->where('type_cible', 'sceneEdit')
          ->update(['droit' => $sceneEdit]);

        $updateDroit = LinkuserdroitModel::where('id_user', $id_user)
          ->where('type_cible', 'agendaEdit')
          ->update(['droit' => $agendaEdit]);

        $updateDroit = LinkuserdroitModel::where('id_user', $id_user)
          ->where('type_cible', 'fichierDelete')
          ->update(['droit' => $fichierDelete]);

        $updateDroit = LinkuserdroitModel::where('id_user', $id_user)
          ->where('type_cible', 'sceneDelete')
          ->update(['droit' => $sceneDelete]);

        $updateDroit = LinkuserdroitModel::where('id_user', $id_user)
          ->where('type_cible', 'agendaDelete')
          ->update(['droit' => $agendaDelete]);

        // -------------------------------------------
        $getDroit =  LinkuserdroitModel::where('id_user', $id_user)
          ->where('type_cible', 'module')
          ->get();

        if(count($getDroit)!=0){
            $updateDroit = LinkuserdroitModel::where('id_user', $id_user)
              ->where('type_cible', 'module')
              ->update(['droit' => $module]);
        }else{
          $reqDroits = [
            'id_user'=>$id_user,
            'type_cible'=>'module',
            'id_cible'=>0,
            'droit'=>$module,
          ];

          $droit = LinkuserdroitModel::create($reqDroits);
        }


        $getDroit =  LinkuserdroitModel::where('id_user', $id_user)
          ->where('type_cible', 'moduleEdit')
          ->get();

        if(count($getDroit)!=0){
            $updateDroit = LinkuserdroitModel::where('id_user', $id_user)
              ->where('type_cible', 'moduleEdit')
              ->update(['droit' => $moduleEdit]);
        }else{
          $reqDroits = [
            'id_user'=>$id_user,
            'type_cible'=>'moduleEdit',
            'id_cible'=>0,
            'droit'=>$moduleEdit,
          ];

          $droit = LinkuserdroitModel::create($reqDroits);
        }

        $getDroit =  LinkuserdroitModel::where('id_user', $id_user)
          ->where('type_cible', 'moduleDelete')
          ->get();

        if(count($getDroit)!=0){
            $updateDroit = LinkuserdroitModel::where('id_user', $id_user)
              ->where('type_cible', 'moduleDelete')
              ->update(['droit' => $moduleDelete]);
        }else{
          $reqDroits = [
            'id_user'=>$id_user,
            'type_cible'=>'moduleDelete',
            'id_cible'=>0,
            'droit'=>$moduleDelete,
          ];

          $droit = LinkuserdroitModel::create($reqDroits);
        }



        return response()->json($updateDroit,200);
    }

    public function updateDroitsGroupe(Request $request){
        // $groupe = GroupeModel::create($request->all());

        $fichier = $_POST['fichier'];
        $scene = $_POST['scene'];
        $agenda  =  $_POST['agenda'];
        $fichierEdit = $_POST['fichierEdit'];
        $sceneEdit = $_POST['sceneEdit'];
        $agendaEdit  =  $_POST['agendaEdit'];
        $fichierDelete = $_POST['fichierDelete'];
        $sceneDelete = $_POST['sceneDelete'];
        $agendaDelete  =  $_POST['agendaDelete'];

        $module  =  $_POST['module'];
        $moduleEdit  =  $_POST['moduleEdit'];
        $moduleDelete  =  $_POST['moduleDelete'];


        $id_groupe = intval($_POST['id_groupe']);
        $AppliqAllMembers = intval($_POST['AppliqAllMembers']);

        if($AppliqAllMembers==1){
          $ids  = LinkgroupeusersModel::where("id_goupeusers", $id_groupe)->get();
          if(count($ids)!=0){
              foreach ($ids as $value){
                  $updateDroit = LinkuserdroitModel::where('id_user', $value->id_user)
                    ->where('type_cible', 'fichier')
                    ->update(['droit' => $fichier]);

                  $updateDroit = LinkuserdroitModel::where('id_user', $value->id_user)
                    ->where('type_cible', 'scene')
                    ->update(['droit' => $scene]);

                  $updateDroit = LinkuserdroitModel::where('id_user', $value->id_user)
                    ->where('type_cible', 'agenda')
                    ->update(['droit' => $agenda]);

                  $updateDroit = LinkuserdroitModel::where('id_user', $value->id_user)
                    ->where('type_cible', 'fichierEdit')
                    ->update(['droit' => $fichierEdit]);

                  $updateDroit = LinkuserdroitModel::where('id_user', $value->id_user)
                    ->where('type_cible', 'sceneEdit')
                    ->update(['droit' => $sceneEdit]);

                  $updateDroit = LinkuserdroitModel::where('id_user', $value->id_user)
                    ->where('type_cible', 'agendaEdit')
                    ->update(['droit' => $agendaEdit]);

                  $updateDroit = LinkuserdroitModel::where('id_user', $value->id_user)
                    ->where('type_cible', 'fichierDelete')
                    ->update(['droit' => $fichierDelete]);

                  $updateDroit = LinkuserdroitModel::where('id_user', $value->id_user)
                    ->where('type_cible', 'sceneDelete')
                    ->update(['droit' => $sceneDelete]);

                  $updateDroit = LinkuserdroitModel::where('id_user', $value->id_user)
                    ->where('type_cible', 'agendaDelete')
                    ->update(['droit' => $agendaDelete]);
                  // -----------------------------------------------------
                  $getDroit =  LinkuserdroitModel::where('id_user', $value->id_user)
                    ->where('type_cible', 'module')
                    ->get();

                  if(count($getDroit)!=0){
                      $updateDroit = LinkuserdroitModel::where('id_user', $value->id_user)
                        ->where('type_cible', 'module')
                        ->update(['droit' => $module]);
                  }else{
                    $reqDroits = [
                      'id_user'=>$value->id_user,
                      'type_cible'=>'module',
                      'id_cible'=>0,
                      'droit'=>$module,
                    ];

                    $droit = LinkuserdroitModel::create($reqDroits);
                  }

                  $getDroit =  LinkuserdroitModel::where('id_user', $value->id_user)
                    ->where('type_cible', 'moduleEdit')
                    ->get();

                  if(count($getDroit)!=0){
                      $updateDroit = LinkuserdroitModel::where('id_user', $value->id_user)
                        ->where('type_cible', 'moduleEdit')
                        ->update(['droit' => $moduleEdit]);
                  }else{
                    $reqDroits = [
                      'id_user'=>$value->id_user,
                      'type_cible'=>'moduleEdit',
                      'id_cible'=>0,
                      'droit'=>$moduleEdit,
                    ];

                    $droit = LinkuserdroitModel::create($reqDroits);
                  }

                  $getDroit =  LinkuserdroitModel::where('id_user', $value->id_user)
                    ->where('type_cible', 'moduleDelete')
                    ->get();

                  if(count($getDroit)!=0){
                      $updateDroit = LinkuserdroitModel::where('id_user', $value->id_user)
                        ->where('type_cible', 'moduleDelete')
                        ->update(['droit' => $moduleDelete]);
                  }else{
                    $reqDroits = [
                      'id_user'=>$value->id_user,
                      'type_cible'=>'moduleDelete',
                      'id_cible'=>0,
                      'droit'=>$moduleDelete,
                    ];

                    $droit = LinkuserdroitModel::create($reqDroits);
                  }

              }
          }
        }

        $updateDroit = GroupeusersdroitModel::where('id_groupeusers', $id_groupe)
          ->where('type_cible', 'fichier')
          ->update(['droit' => $fichier]);

        $updateDroit = GroupeusersdroitModel::where('id_groupeusers', $id_groupe)
          ->where('type_cible', 'scene')
          ->update(['droit' => $scene]);

        $updateDroit =GroupeusersdroitModel::where('id_groupeusers', $id_groupe)
          ->where('type_cible', 'agenda')
          ->update(['droit' => $agenda]);

        $updateDroit = GroupeusersdroitModel::where('id_groupeusers', $id_groupe)
          ->where('type_cible', 'fichierEdit')
          ->update(['droit' => $fichierEdit]);

        $updateDroit = GroupeusersdroitModel::where('id_groupeusers', $id_groupe)
          ->where('type_cible', 'sceneEdit')
          ->update(['droit' => $sceneEdit]);

        $updateDroit =GroupeusersdroitModel::where('id_groupeusers', $id_groupe)
          ->where('type_cible', 'agendaEdit')
          ->update(['droit' => $agendaEdit]);

        $updateDroit = GroupeusersdroitModel::where('id_groupeusers', $id_groupe)
          ->where('type_cible', 'fichierDelete')
          ->update(['droit' => $fichierDelete]);

        $updateDroit = GroupeusersdroitModel::where('id_groupeusers', $id_groupe)
          ->where('type_cible', 'sceneDelete')
          ->update(['droit' => $sceneDelete]);

        $updateDroit =GroupeusersdroitModel::where('id_groupeusers', $id_groupe)
          ->where('type_cible', 'agendaDelete')
          ->update(['droit' => $agendaDelete]);
// -----------------------------------------------------------------------
          $getDroit =  GroupeusersdroitModel::where('id_groupeusers', $id_groupe)
            ->where('type_cible', 'moduleDelete')
            ->get();

          if(count($getDroit)!=0){
              $updateDroit = GroupeusersdroitModel::where('id_groupeusers', $id_groupe)
                ->where('type_cible', 'moduleDelete')
                ->update(['droit' => $moduleDelete]);
          }else{
            $reqDroits = [
              'id_groupeusers'=>$id_groupe,
              'type_cible'=>'moduleDelete',
              'id_cible'=>0,
              'droit'=>$moduleDelete,
            ];

            $droit = GroupeusersdroitModel::create($reqDroits);
          }

          $getDroit =  GroupeusersdroitModel::where('id_groupeusers', $id_groupe)
            ->where('type_cible', 'moduleEdit')
            ->get();

          if(count($getDroit)!=0){
              $updateDroit = GroupeusersdroitModel::where('id_groupeusers', $id_groupe)
                ->where('type_cible', 'moduleEdit')
                ->update(['droit' => $moduleEdit]);
          }else{
            $reqDroits = [
              'id_groupeusers'=>$id_groupe,
              'type_cible'=>'moduleEdit',
              'id_cible'=>0,
              'droit'=>$moduleEdit,
            ];

            $droit = GroupeusersdroitModel::create($reqDroits);
          }


          $getDroit =  GroupeusersdroitModel::where('id_groupeusers', $id_groupe)
            ->where('type_cible', 'module')
            ->get();

          if(count($getDroit)!=0){
              $updateDroit = GroupeusersdroitModel::where('id_groupeusers', $id_groupe)
                ->where('type_cible', 'module')
                ->update(['droit' => $module]);
          }else{
            $reqDroits = [
              'id_groupeusers'=>$id_groupe,
              'type_cible'=>'module',
              'id_cible'=>0,
              'droit'=>$module,
            ];

            $droit = GroupeusersdroitModel::create($reqDroits);
          }


        return response()->json($_POST,200);
    }

    public function updateBloquer(Request $request){
        // $groupe = GroupeModel::create($request->all());

        $id_user = intval($_POST['id_user']);

        $updateBloq = UsersModel::where('id_user', $id_user)
          ->update(['etat' => 0]);

        return response()->json($updateBloq,200);
    }

    public function updateDebloquer(Request $request){
        // $groupe = GroupeModel::create($request->all());

        $id_user = intval($_POST['id_user']);

        $updatedeBloq = UsersModel::where('id_user', $id_user)
          ->update(['etat' => 1]);

        return response()->json($updatedeBloq,200);
    }

    public function editGroupes(Request $request){
        // $groupe = GroupeModel::create($request->all());

        $id_groupe = intval($_POST['id_groupe']);
        $nom = $_POST['nom'];

        $update = GroupeusersModel::where('id', $id_groupe)
          ->update(['nom' => $nom]);

        return response()->json($update,200);
    }

    // Delete -------------------------------------

    public function supprimerUser(Request $request){
      // $groupe = GroupeModel::create($request->all());

      $id_user = intval($_POST['id_user']);

      $delete = UsersModel::where('id_user', $id_user)
        ->delete();

      $delete = LinkuserdroitModel::where('id_user', $id_user)
          ->delete();

      return response()->json($delete,200);
  }

  public function supprimerAllUser(Request $request){
    // $groupe = GroupeModel::create($request->all());

      $ids = explode(",",$_POST['ids']);
      $rep = array();

      foreach ($ids as $value){
          $id_user = intval($value);

          $delete = UsersModel::where('id_user', $id_user)
            ->delete();

          $delete = LinkuserdroitModel::where('id_user', $id_user)
              ->delete();
              array_push($rep,  $delete);
      }

      return response()->json($_POST,200);
}



  public function supprimerGroupeUsers(Request $request){
      // $groupe = GroupeModel::create($request->all());

      $id_groupe = intval($_POST['id_groupe']);

      $delete = GroupeusersModel::where('id', $id_groupe)->delete();

      $delete = GroupeusersdroitModel::where('id_groupeusers', $id_groupe)->delete();

      return response()->json($delete,200);
  }

  public function supprimerAllGroupeUsers(Request $request){
      // $groupe = GroupeModel::create($request->all());

      $ids = explode(",",$_POST['ids']);
      $rep = array();

      foreach ($ids as $value){
          $id_groupe = intval($value);

          $delete = GroupeusersModel::where('id', $id_groupe)->delete();

          $delete = GroupeusersdroitModel::where('id_groupeusers', $id_groupe)->delete();

          array_push($rep,  $delete);
      }

      return response()->json($rep,200);
  }

  public function deleteEntite(Request $request){
      // $groupe = GroupeModel::create($request->all());

      $id_entite = intval($_POST['idEntite']);


      $delete = EntiteModel::where('id_entite', $id_entite)->delete();
      $delete = UsersModel::where('id_entite', $id_entite)->delete();

      return response()->json($delete,200);
  }

  public function deleteEntites(Request $request){
      // $groupe = GroupeModel::create($request->all());

      $ids = explode(",",$_POST['ids']);
      $rep = array();

      foreach ($ids as $value){
          $id_entite = intval($value);

          $delete = EntiteModel::where('id_entite', $id_entite)->delete();
          $delete = UsersModel::where('id_entite', $id_entite)->delete();

          array_push($rep,  $delete);
      }

      return response()->json($rep,200);
  }

    public function saveNewModule(){
      $idModule=$_POST['idModule'];
      $nomModule=$_POST['nomModule'];
      $idEntite=$_POST['idEntite'];
      $mod = ModuleModel::where('id_module',$idModule)->get();
      if(\count($mod)>=1){
        return response()->json(['status'=>0]);//module deja enregistre
      }

      $module=ModuleModel::create(['id_module'=>$idModule,'id_entite'=>intval($idEntite),'nom'=>$nomModule]);


      return response()->json(['status'=>1]);//success
     // return response()->json(['status'=>-1]);//erreur inconnue
    }


    public function getModules(){
      $id_entite=$_POST['entite'];
      $id_user=$_POST['id_user'];
      $access_level=$_POST['access_level'];
      $idserver=linkserverentiteModel::where('id_entite',$id_entite)->get()->first()->id_server;
      $server=serversModel::where('id',$idserver)->first();
     // $user=UsersModel::where('id_user',$id_user)->get()->first();
      if($access_level==1){
        $link=LinkmodulesusersModel::where('id_user',$id_user)->get();
        $modul=[];
        foreach($link as $l){
          $modul[]=ModuleModel::where('id_module',$l->id_module)->get()->first();
        }
        return response()->json(array("module"=>$modul,"server"=>$server));
      }

      $modules=ModuleModel::where('id_entite',$id_entite)->get();
      return response()->json(array("module"=>$modules,"server"=>$server));
    }
    public function affecterModule(){
      $type=$_POST['type'];
      if($type=="user"){
        return response()->json(['status'=>$this->affecterModuleToUser($_POST['id'],$_POST['id_module'])]);
      }
      return "it's a modul";
    }
    function affecterModuleToUser($id_user,$id_module){
      $link=LinkmodulesusersModel::where('id_user',$id_user)->where('id_module',$id_module)->get()->first();
      if($link){
        return -1;
      }
      LinkmodulesusersModel::create(['id_module'=>$id_module,'id_user'=>$id_user]);
      return 1;
    }
    public function retirerModule(){
      $type=$_POST['type'];
      if($type=="user"){
        return response()->json(['status'=>$this->retirerModuleFromUser($_POST['id'],$_POST['id_module'])]);
      }
      return "bi groupe la";
    }
    function retirerModuleFromUser($id_user,$id_module){
      $link=LinkmodulesusersModel::where('id_user',$id_user)->where('id_module',$id_module)->get()->first();
      if($link){
        $link->delete();
        return 1;
      }
      return 0;

    }
    public function getUsers(){
      $id_entite=$_POST['entite'];
      $id_module=$_POST['id_module'];
      $us=UsersModel::where('id_entite',$id_entite)->where('access_level',1)->where('etat',1)->get();
      $users=[];
      foreach($us as $utili){
        $link=LinkmodulesusersModel::where('id_user',$utili->id_user)->where('id_module',$id_module)->get()->first();
        if($link){
          $users[]=['id_user'=>$utili->id_user,'prenom'=>$utili->prenom,'nom'=>$utili->nom,'already'=>1];
        }else{
          $users[]=['id_user'=>$utili->id_user,'prenom'=>$utili->prenom,'nom'=>$utili->nom,'already'=>0];
        }
      }
      return response()->json(["users"=>$users]);
    }
    public function sauvegarderSceneChange(){
      $idscene=$_POST['idscene'];
      $media=json_decode($_POST['medias']);
      foreach($media as $m){
        $ton=linkscenemedia::where('id',$m->id_media)->get();
        if(count($ton)==0){
          linkscenemedia::create(['id_scene'=>$idscene,'id_media'=>$m->id_media,'priorite'=>$m->debut,'duree'=>$m->duree]);
         // return response()->json(['medias'=>"nothing to see","idscene"=>$idscene]);
        }else{
          $medi=$ton[0];
          $medi->priorite=$m->debut;
          $medi->duree=$m->duree;
          $medi->save();
         // return response()->json(["media"=>"update reussie"]);
        }
      }
      return response()->json(['status'=>1,"idscene"=>$idscene]);
    }
}
