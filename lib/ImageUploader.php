<?php

class ImageUploader {

    const BASE_DIR      = './uploads/';
    const MAX_FILE_SIZE = 5000000;

    private $files;

    function __construct($files) {
        $this->files     = [];
        $number_of_files = count($files['name']);
        for ($i = 0; $i < $number_of_files; $i++) {
            $file = [
                'name'     => $files['name'][$i],
                'type'     => $files['type'][$i],
                'tmp_name' => $files['tmp_name'][$i],
                'size'     => $files['size'][$i],
                'error'    => $files['error'][$i],
            ];
            array_push($this->files, $file);
        }
    }

    public function upload($upload_dir) {
        if (!$this->validate_files()) {
            return false;
        }
        $uploads = [];
        $dir = self::BASE_DIR . $upload_dir;
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
        foreach ($this->files as $file) {
            $target_file = "$dir/" . time() . '_' . urlencode($file['name']);
            if (move_uploaded_file($file['tmp_name'], $target_file)) {
                array_push($uploads, substr($target_file, 1));
            } else {
                foreach ($uploads as $uploaded_file) {
                    unlink($uploaded_file);
                }
                return false;
            }
        }
        return $uploads;
    }

    private function validate_files() {
        foreach ($this->files as $file) {
            if ($file['error']) {
                return false;
            }
            if (!$this->is_image($file)) {
                return false;
            }
            if ($file['size'] > self::MAX_FILE_SIZE) {
                return false;
            }
        }
        return true;
    }

    private function is_image($file) {
        $image_types = ['image/jpeg', 'image/gif', 'image/png'];
        return in_array($file['type'], $image_types);
    }
}