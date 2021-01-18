<?php

namespace App\Http\Controllers\Super;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\serversModel;
use App\Models\ModuleModel;
use App\Models\EntiteModel;
use App\Models\linkserverentiteModel;

class SuperController extends Controller
{
    //
    public function addServer(){
        $nameserver=$_POST['nameserver'];
        $ipserver=$_POST['ipserver'];
        if(count($this->findserverByIp($ipserver))==0 && count($this->findserverByName($nameserver))==0){
            serversModel::create(['nom'=>$nameserver,'ip'=>$ipserver]);
            return response()->json(['status'=>1]);
        }
        //server existe deja
        return response()->json(['status'=>0]);
    }
    function findserverByName($name){
        return ServersModel::where('nom',$name)->get();
    }
    function findserverByIp($ip){
        return ServersModel::where('ip',$ip)->get();
    }
    public function getServer(){
        $servers=ServersModel::get();
        return response()->json(['status'=>1,'data'=>$servers]);
    }
   public function deleteServer(){
	$idserver=$_POST['idserver'];
	$server=serversModel::find($idserver);
	$rep=$server->delete();
	return $rep;
   }
   public function updateServer(){
       $idserver=$_POST['idserver'];
       $ip=$_POST['ip'];
       $nom=$_POST['nom'];
       $server=serversModel::find($idserver);
       if(strcmp($server->nom,$nom)!=0){
           if(count($this->findserverByName($nom))==0){
                $server->nom=$nom;
           }else{
               return response()->json(["status"=>0,"message"=>"Ce nom existe deja."]);
           }
       }
       if(strcmp($server->ip,$ip)!=0){
           if(count($this->findserverByIp($ip))==0){
                $server->ip=$ip;
           }else{
            return response()->json(["status"=>0,"message"=>"L'adresse ip existe deja."]);
           }
       }
       $server->save();
       return response()->json(["status"=>1,"message"=>"Modifications enregistrées"]);
   }
   public function getModule(){
       $module=ModuleModel::get();
       $mod=[];
       foreach($module as $m){
           $id_entite=$m->id_entite;
           $entite=EntiteModel::where('id_entite',$id_entite)->get()->first();
           $link=linkserverentiteModel::where('id_entite',$id_entite)->get()->first();
           $server=ServersModel::where('id',$link->id_server)->get()->first();
           $mod[]=array("id"=>$server->nom.$m->id_module_g,"etat"=>$m->status_module,"serveur"=>$server->nom,"entite"=>$entite->nom);
       }
       return $mod;
   }
}
