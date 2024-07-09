<?php

if (!function_exists("customUploadFile")) {
    /**
     * Custom upload function that you send to it the file name in the request like "photo"
     * Also you will specify the folder like "tempWork" or "tqawel"
     * This function will find or create upload folder for the current month then make upload inside it
     *
     * @param $fileAttr
     * @param $path
     *
     * @return string $filePath
     */
    function customUploadFile($fileAttr, $path = "userDocuments")
    {
        $folder_path = $path.'/'.date('m').'_'.date('y');

        if (request()->file($fileAttr)->isValid()) {
            $file = request()->file($fileAttr);
            $name = $file->getClientOriginalName();
            $name = time().'_'.$name;
            return $file->store($folder_path.'/'.$name);
        }

        return false;
    }
}

if (!function_exists('isImage')) {
    /**
     * @param string $file
     *
     * @return bool
     */
    function isImage($file)
    {
        $allowedMimeTypes = ['image/jpeg', 'image/gif', 'image/png', 'image/bmp', 'image/svg+xml'];
        $contentType = mime_content_type($file);

        if (in_array($contentType, $allowedMimeTypes, true)) {
            return true;
        }

        return false;
    }
}
