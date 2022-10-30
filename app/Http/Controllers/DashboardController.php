<?php
//For subdomain deploy!!!
//namespace App\Http\Controllers\Mifik;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;

use App\Models\content;
use App\Models\tag;
use App\Models\archieve;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view ('dashboard.index');
    }


    public function getAllContent()
    {
        $cnt = content::all();
        //Need pagination?
        return response()->json($cnt);
    }
    
    public function getContent($id)
    {
        $cnt = content::where('id', $id)->get();
        
        return response()->json($cnt);
    }
    
    public function getAllTag()
    {
        $tag = tag::all();
        
        return response()->json($tag);
    }
    
    public function getMyArchieve($id_user)
    {
        $ar = archieve::where('id_user', $id_user)->get();
        
        return response()->json($ar);
    }

   
}
