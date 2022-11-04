<?php
namespace App\Http\Traits;

use Illuminate\Support\Str;
use App\Repositories\Common\CommonRepository;

trait ImageManageTrait {

    protected $common;

    public function __construct(CommonRepository $commonRepository)
    {
        $this->common = $commonRepository;
    }

    public function imageUpload($table_id, $type, $fileName, $tableNameAndFolderName, $prefix = "demo", $image)
    {
        $prefix = Str::slug($prefix);
        $imageName = $prefix.'-'.rand(1111,9999).'-'.$image->getClientOriginalName();

        $image_path = "uploads/".$tableNameAndFolderName;
        $image->move($image_path,$imageName);

        $imageData = [
            "table_id" => $table_id,
            "url" => $image_path.'/'.$imageName,
            "type" => $type,
            'file_name' => $fileName,
            "table_name" => $tableNameAndFolderName,
        ];

        $this->common->storeInModel('App\Models\Image', $imageData);
    }

    public function imageDelete($data){
        if (file_exists($data)) {
            unlink($data);
        }
        return $data;
    }


    public function imageUploadForUpdate($table_id, $type, $fileName, $tableNameAndFolderName, $prefix = "demo", $image){

        $prefix = Str::slug($prefix);
        $imageName = $prefix.'-'.rand(1111,9999).'-'.$image->getClientOriginalName();

        $image_path = "uploads/".$tableNameAndFolderName;
        $image->move($image_path,$imageName);

        $oldData = $this->common->imageOnModelFirst($tableNameAndFolderName, $table_id, $type, $fileName);

        $newData = [
            "table_id" => $table_id,
            "url" => $image_path.'/'.$imageName,
            "type" => $type,
            'file_name' => $fileName,
            "table_name" => $tableNameAndFolderName,
        ];
                    
        if($oldData){
            // old file delete and update
            $this->imageDelete($oldData->url);
            $this->common->update($newData, $oldData);
        }else{
            //If the old image is not there, it will update the new image
            $this->common->storeInModel('App\Models\Image', $newData);
        }
    }
}