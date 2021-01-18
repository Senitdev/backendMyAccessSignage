<?php

namespace App\Http\Controllers\Module;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MediaModel;
use App\Models\ModuleModel;
use App\Models\linkserverentiteModel;
use App\Models\serversModel;


class ModuleController extends Controller
{
    //
    public function getScene(){
        if(isset($_POST['id_module'])){
	        date_default_timezone_set("Europe/Zurich");
            $date=date('d/m/yy H:i:s');
            $timestamp=time();
            $modules=ModuleModel::where('id_module',$_POST['id_module'])->get();
	    if(count($modules)>=1){
		    $module=$modules[0];
           	 $module->status_module=1;
           	 $module->last_ping=$date;
           	 $module->timestamp_last_ping=$timestamp;
           	 $id_entite=$module->id_entite;
           	 $module->save();
           
           	 if($module->state==1){
               		 $media=MediaModel::where('id_entite',$id_entite)->get();
               		 return response()->json(["status"=>1,"data"=>$media]);
            	}
                //$media=MediaModel::where('id_media',2002)->get();
                //diffusion arreter
           	 return response()->json(["status"=>-1,"data"=>[]]);
	   }else{
		$media=MediaModel::where('id_media',2001)->get();
		return response()->json(["status"=>0,"data"=>$media]);
	  }
           
        }
        $media=MediaModel::where('id_media',2000)->get();
        return response()->json(["status"=>0,"data"=>$media]);

        
    }
    public function deleteModule(){
        $id_module=$_POST['id_module'];
        $id_user=$_POST['id_user'];
        $id_entite=$_POST['id_entite'];
        $module=ModuleModel::where('id_module',$id_module)->get();
        $delete=ModuleModel::where('id_module',$id_module)->delete();
       // $module->delete();

        return response()->json($delete);
       //return $module->nom;
    }
    public function stopDiffusion(){
        $module=$_POST['id_module'];
        $module=ModuleModel::where('id_module',$_POST['id_module'])->first();
        $module->state=0;
        $module->status_module=0;
        $module->save();
        return $module;
    }
    public function autoriserDiffusion(){
        $module=$_POST['id_module'];
        $module=ModuleModel::where('id_module',$_POST['id_module'])->first();
        $module->state=1;
        $module->status_module=1;
        $module->save();
        return $module;
    }
    public function getServer(){
        $id_module=$_POST['id_module'];
        $module=ModuleModel::where('id_module',$id_module)->first();
        $id_entite=$module->id_entite;
        $link=linkserverentiteModel::where('id_entite',$id_entite)->get()->first();
        $server=ServersModel::where('id',$link->id_server)->get()->first();
        $nameServer=$server->nom.$module->id_module_g;
        return $nameServer;

    }
}
