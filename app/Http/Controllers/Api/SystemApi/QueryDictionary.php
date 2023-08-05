<?php

namespace App\Http\Controllers\Api\SystemApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\Generator;

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
                    'message' => Generator::getMessageTemplate("business_read_success", 'dictionary', null),
                    'data' => $dictionary
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'status' => 'failed',
                    'message' => Generator::getMessageTemplate("business_read_failed", 'dictionary', null),
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
                    'message' => Generator::getMessageTemplate("business_read_success", 'dictionary type', null),
                    'data' => $dictionary
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'status' => 'failed',
                    'message' => Generator::getMessageTemplate("business_read_failed", 'dictionary type', null),
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

    public function getAllDictionaryByType($dct_type) {
        try{
            if($dct_type == "TAG-001"){
                $dictionary = Dictionary::selectRaw('dictionaries.slug_name,dct_name, count(1) as total')
                    ->join("tags","tags.tag_category","=","dictionaries.slug_name")
                    ->where('dct_type', $dct_type)
                    ->groupBy('tags.tag_category')
                    ->orderByRaw("
                        CASE
                            WHEN tag_category = 'general-role' THEN 1
                            ELSE 2
                        END ASC
                    ")
                    ->whereNull('tags.deleted_at');
            } else {
                $dictionary = Dictionary::selectRaw('dictionaries.slug_name,dct_name')
                    ->where('dct_type', $dct_type)
                    ->orderBy('dictionaries.created_at', 'DESC');
            }

            $dictionary = $dictionary->get();

            if ($dictionary->count() > 0) {
                return response()->json([
                    'status' => 'success',
                    'message' => Generator::getMessageTemplate("business_read_success", 'dictionary', null),
                    'data' => $dictionary
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'status' => 'failed',
                    'message' => Generator::getMessageTemplate("business_read_failed", 'dictionary', null),
                    'data' => null
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        } catch(\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
