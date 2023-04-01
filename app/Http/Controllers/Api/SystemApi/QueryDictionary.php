<?php

namespace App\Http\Controllers\Api\SystemApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\Dictionary;
use App\Models\DictionaryType;

class QueryDictionary extends Controller
{
    public function getAllDictionary() {
        try{
            $dictionary = Dictionary::select('slug_name','dct_name','dct_desc','dct_type','dictionaries.created_at','dictionaries.updated_at','dictionaries.deleted_at','dictionaries.created_by','dictionaries.updated_by','dictionaries.deleted_by')
                ->join('dictionaries_types', 'dictionaries_types.app_code','=','dictionaries.dct_type')
                ->orderBy('updated_at', 'DESC')
                ->orderBy('created_at', 'DESC')
                ->get();

            if ($dictionary->count() > 0) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Dictionary Found',
                    'data' => $dictionary
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Dictionary Not Found',
                    'data' => null
                ], Response::HTTP_NOT_FOUND);
            }
        } catch(\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getAllDictionaryType() {
        try{
            $dictionary = DictionaryType::select('app_code','type_name')
                ->orderBy('created_at', 'DESC')
                ->get();

            if ($dictionary->count() > 0) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Dictionary type Found',
                    'data' => $dictionary
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Dictionary type Not Found',
                    'data' => null
                ], Response::HTTP_NOT_FOUND);
            }
        } catch(\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
