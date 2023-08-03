<?php
namespace App\Helpers;
use Illuminate\Support\Facades\DB;

use App\Helpers\Generator;

use App\Models\FailedJob;

use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

class FirebaseTask
{
    public static function deleteContentAttachment($id){ 
        $attachment = DB::table("contents_details")->select("content_attach","created_by")
            ->join("contents_headers","contents_headers.id","=","contents_details.content_id")
            ->where('content_id', $id)
            ->first();

        if($attachment){
            $factory = (new Factory)->withServiceAccount(base_path('/secret/firebase_admin/mifik-83723-firebase-adminsdk-ejmwj-29f65d3ea6.json'));
            $storage = $factory->createStorage();

            if($attachment->content_attach != null){
                $att = json_decode($attachment->content_attach);
                foreach($att as $val){
                    if($val->attach_type != "attachment_url"){
                        $failed = false;
                        $fileName = Generator::generateUUIDStorageURL($val->attach_type,$val->attach_url);

                        if($fileName){
                            $bucket = 'mifik-83723.appspot.com';
                            $fileUrl = $val->attach_type.'/'.$fileName;
                            $bucket = $storage->getBucket($bucket);
                            $object = $bucket->object($fileUrl);

                            if ($object->exists()){
                                $object->delete();
                            } else {
                                $failed = true;
                                $obj = [
                                    'message' => 'File is not exist anymore', 
                                    'stack_trace' => $val->attach_type, 
                                    'file' => $val->attach_url, 
                                    'line' => null,
                                ];
                            }
                        } else {
                            $failed = true;
                            $obj = [
                                'message' => 'URL is not valid', 
                                'stack_trace' => $val->attach_type, 
                                'file' => $val->attach_url, 
                                'line' => null,
                            ];
                        }

                        if($failed){
                            FailedJob::create([
                                'id' => Generator::getUUID(), 
                                'type' => "storage", 
                                'status' => "failed",  
                                'payload' => json_encode($obj),
                                'created_at' => date("Y-m-d H:i:s"), 
                                'faced_by' => $attachment->created_by
                            ]);
                        }
                    } 
                }
            }
        }
    }

    public static function deleteUserImage($id){ 
        $type = "profile_image";
        $user = DB::table("users")->select("image_url")
            ->where('id', $id)
            ->first();

        if($user){
            $factory = (new Factory)->withServiceAccount(base_path('/secret/firebase_admin/mifik-83723-firebase-adminsdk-ejmwj-29f65d3ea6.json'));
            $storage = $factory->createStorage();

            $failed = false;
            if($user->image_url != null){
                $fileName = Generator::generateUUIDStorageURL($type,$user->image_url);

                if($fileName){
                    $bucket = 'mifik-83723.appspot.com';
                    $fileUrl = $type.'/'.$fileName;
                    $bucket = $storage->getBucket($bucket);
                    $object = $bucket->object($fileUrl);

                    if ($object->exists()){
                        $object->delete();
                    } else {
                        $failed = true;
                        $obj = [
                            'message' => 'File is not exist', 
                            'stack_trace' => $type, 
                            'file' => $user->image_url, 
                            'line' => null,
                        ];
                    }
                } else {
                    $failed = true;
                    $obj = [
                        'message' => 'URL is not valid', 
                        'stack_trace' => $type, 
                        'file' => $user->image_url, 
                        'line' => null,
                    ];
                }

                if($failed){
                    FailedJob::create([
                        'id' => Generator::getUUID(), 
                        'type' => "storage", 
                        'status' => "failed",  
                        'payload' => json_encode($obj),
                        'created_at' => date("Y-m-d H:i:s"), 
                        'faced_by' => $id
                    ]);
                }
            }
        }
    }
}