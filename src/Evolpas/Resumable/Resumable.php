<?php

namespace Evolpas\Resumable;

use Illuminate\Support\Facades\Input;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Illuminate\Support\Facades\File;

class Resumable {
    protected $input;

    public function __construct() {
        $this->input = Input::except('file');
    }

    public function upload($callback) {
        if (!empty($_FILES)) {
            foreach ($_FILES as $file) {
                $identifier = $this->input['resumableIdentifier'];
                $storatePath = storage_path() . DIRECTORY_SEPARATOR . 'resumable';
                if (!is_dir($storatePath)) {
                    mkdir($storatePath, 0777, true);
                }
                $uploadPath = $storatePath . DIRECTORY_SEPARATOR . $identifier;
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }
                $fileName = $this->input['resumableFilename'] . '.part' . $this->input['resumableChunkNumber'];
                //Input::file('file')->move($uploadPath, $fileName);
                if (move_uploaded_file($file['tmp_name'], $uploadPath . DIRECTORY_SEPARATOR . $fileName)) {
                    $uploadedPath = $this->createFileFromChunks($uploadPath, $this->input['resumableFilename'], $this->input['resumableChunkSize'], $this->input['resumableTotalSize']);
                    if ($uploadPath !== null && $uploadPath !== FALSE) {
                        call_user_func($callback, $uploadedPath, $this->input['resumableFilename']);
                    }
                }
            }
        }
    }

    /**
     *
     * Check if all the parts exist, and 
     * gather all the parts of the file together
     * @param string $dir - the temporary directory holding all the parts of the file
     * @param string $fileName - the original file name
     * @param string $chunkSize - each chunk size (in bytes)
     * @param string $totalSize - original file size (in bytes)
     */
    protected function createFileFromChunks($dir, $fileName, $chunkSize, $totalSize) {
        $storagePath = storage_path() . DIRECTORY_SEPARATOR . 'resumable';
        // count all the parts of this file
        $total_files = 0;
        $files = File::files($dir);
        foreach ($files as $file) {
            if (stripos($file, $fileName) !== false) {
                $total_files++;
            }
        }
        $newFilename = date('Y-m-d_H-i-s') . '-' . str_ireplace(' ', '_', $fileName);
        // check that all the parts are present
        // the size of the last part is between chunkSize and 2*$chunkSize
        if ($total_files * $chunkSize >= ($totalSize - $chunkSize + 1)) {
            // create the final destination file 
            if (($fp = fopen($storagePath . DIRECTORY_SEPARATOR . $newFilename, 'w')) !== false) {
                for ($i = 1; $i <= $total_files; $i++) {
                    fwrite($fp, file_get_contents($dir . '/' . $fileName . '.part' . $i));
                    unlink($dir . '/' . $fileName . '.part' . $i);
                }
                fclose($fp);
            } else {
                return false;
            }
            File::deleteDirectory($dir);
            return $storagePath . DIRECTORY_SEPARATOR . $newFilename;
        } else {
            return null;
        }
    }

}
