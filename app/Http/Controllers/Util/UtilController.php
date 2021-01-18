<?php

namespace App\Http\Controllers\Util;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UsersModel;
use App\Models\LinkuserdroitModel;
use App\Models\LinkgroupeusersModel;
use App\Models\LinkmodulesusersModel;
use App\Models\EntiteModel;


class UtilController extends Controller
{
  public function uploadImage(Request $request ){
       $file = $_FILES['uploads'];
       $pathTo = public_path('uploads');

       $originalName = $file['name'];
       $originalTab = explode(".",$originalName);

       $generatedName = sha1($originalName).".".$originalTab[count($originalTab) -1];

       $fileExtension = pathinfo($originalName, PATHINFO_EXTENSION);



       $fileName = $generatedName;

       // return response()->json(array("status"=>true,"name"=>$pathTo.'/'.$fileName) ,200);

       if( !move_uploaded_file($file['tmp_name'], $pathTo.'/'.$fileName) ){



           return response()->json(array("status"=>true,"name"=>"") ,200);
       }
       else{
       //   chmod($pathTo.'/'.$fileName, 777);
          list($width, $height, $type, $attr) = getimagesize($pathTo.'/'.$fileName);
          return response()->json(array("status"=>true,"name"=>$fileName,"width"=>$width,"height"=>$height,"type"=>$type) ,200);
       }
  }

  public function login(Request $request ){
        // $groupe = GroupeModel::create($request->all());

        $login = $_POST['login'];
        $pass = $_POST['pass'];

        $user = UsersModel::where(['email' => $login,'password' => $pass])->get();

        if(count($user)!=0){
          $value = $user[0];
          $droit = LinkuserdroitModel::where('id_user',$value->id_user)->get();

          $value->droit = $droit;


           $groupes = LinkgroupeusersModel::where('id_user',$value->id_user)->get();
           $value->groupes = $groupes;

           $modules = LinkmodulesusersModel::where('id_user',$value->id_user)->get();
           $value->modules = $modules;

           $entite = EntiteModel::where('id_entite',$value->id_entite)->get();
           $value->entite = $entite;
           $user[0]=$value;
        }

        return response()->json($user,200);
  }

 public function getUser(){
    $user = UsersModel::where(['id_user' =>$_POST['id_user']])->get()->first();
    return $user;
  }

 public function saveChange(){
    $id_user=$_POST['id_user'];
    $email=$_POST['email'];
    $pass=$_POST['password'];
    $prenom=$_POST['prenom'];
    $nom=$_POST['nom'];
    $us=UsersModel::where(['email' =>$email])->get();
    $user=UsersModel::where(['id_user' =>$_POST['id_user']])->get()->first();
    if(count($us)>0){
      if($us[0]->id_user!=$id_user){
        return 0;
      }
    }
    $user->prenom=$prenom;
    $user->nom=$nom;
    $user->email=$email;
    if($pass!=""){
      $user->password=$pass;
    }

    return $user->save();
  }

}
