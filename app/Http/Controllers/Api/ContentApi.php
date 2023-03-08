<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\ContentHeader;
use App\Models\ContentDetail;

class ContentApi extends Controller
{
    public function getContentHeader()
    {
        $content = ContentHeader::select('slug_name', 'content_title','content_desc','content_loc','content_image','content_date_start','content_date_end','content_tag','contents_headers.created_at')
            ->leftjoin('contents_details', 'contents_headers.id', '=', 'contents_details.content_id')
            ->orderBy('contents_headers.created_at', 'DESC')
            ->paginate(12);

        if ($content->isEmpty()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Content Not Found',
                'data' => $content
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'status' => 'success',
                'message' => 'Content Header Found',
                'data' => $content
            ], Response::HTTP_OK);
        }
    }

    public function getContentBySlug($slug)
    {
        $content = ContentHeader::select('slug_name', 'content_title','content_desc','content_loc','content_image','content_date_start','content_date_end','content_tag','contents_headers.created_at')
            ->leftjoin('contents_details', 'contents_headers.id', '=', 'contents_details.content_id')
            ->where('slug_name', $slug)
            ->get();

        if ($content->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Content Not Found'
            ], Response::HTTP_NOT_FOUND);
        } else {
            return response()->json([
                'status' => 'success',
                'message' => 'Content Header Found',
                'data' => $content
            ], Response::HTTP_OK);
        }
    }

    public function getContentBySlugLike($slug, $order)
    {
        if($slug != "all"){
            $i = 1;
            $query = "";
            $filter_tag = explode(",", $slug);
            
            foreach($filter_tag as $ft){
                $stmt = 'content_tag like '."'".'%"slug_name":"'.$ft.'"%'."'";

                if($i != 1){
                    $query = substr_replace($query, " ".$stmt." OR", 0, 0);
                } else {
                    $query = substr_replace($query, " ".$stmt, 0, 0);
                }
                $i++;
            }

            $content = ContentHeader::select('slug_name', 'content_title','content_desc','content_loc','content_image','content_date_start','content_date_end','content_tag','contents_headers.created_at')
                ->leftjoin('contents_details', 'contents_headers.id', '=', 'contents_details.content_id')
                ->orderBy('contents_headers.created_at', $order)
                ->whereRaw($query)
                ->paginate(12);

        } else {
            $content = ContentHeader::select('slug_name', 'content_title','content_desc','content_loc','content_image','content_date_start','content_date_end','content_tag','contents_headers.created_at')
                ->leftjoin('contents_details', 'contents_headers.id', '=', 'contents_details.content_id')
                ->orderBy('contents_headers.created_at', $order)
                ->paginate(12);
        }

        if ($content->isEmpty()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Content Not Found',
                'data' => $content
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'status' => 'success',
                'message' => 'Content Header Found',
                'data' => $content
            ], Response::HTTP_OK);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
