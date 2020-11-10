<?php

use Sirius\Upload\Handler as UploadHandler;

class MY_Input extends CI_Input {

    public function __construct() {
        parent::__construct();
    }

    public function file($index = null, $path = null, $extension = null, $size = 0) {
        if (empty($_FILES))
            return NULL;
        if ($path === null)
            $path = FCPATH . 'upload/other/file';
        else {
            if (!str_contains($path, FCPATH))
                $path = FCPATH . 'upload/' . $path;
            elseif (!str_contains($path, 'upload/')) {
                $path = 'upload/' . $path;
            }
        }
        if ($extension === null) {
            $extension = ['jpg', 'jpeg', 'png', 'bmp', 'gif', 'pdf', 'csv', 'xls', 'xlsx', 'ods', 'xml', 'txt'];
        }
        if (!is_array($extension))
            $extensionxt = explode(',', $extension);
        $uploadHandler = new UploadHandler($path);

        // Optional configuration
        $uploadHandler->setOverwrite(false);
        $uploadHandler->setPrefix(time() . '_'); // string to be appended to the file name
        // validation rules
        $uploadHandler->addRule('extension', ['allowed' => $extension], '{label} باید فایل با پسوندهای  معتبر باشد', 'فایل');
        if ($size > 0)
            $uploadHandler->addRule('filesize', ['max' => $size.'M'], 'سایز {label} باید کمتر از  {max} باشد', 'فایل عکس');
        //   $uploadHandler->addRule('imageratio', ['ratio' => 1], '{label} should be a sqare image', 'Profile picture');

        $result = $uploadHandler->process($_FILES[$index]); // ex: subdirectory/my_headshot.png


        if ($result->isValid()) {
            // do something with the image like attaching it to a model etc
            try {
                $result->confirm(); // this will remove the uploaded file and it's .lock file
                return $result->name;
            } catch (\Exception $e) {
                // something wrong happened, we don't need the uploaded picture anymore
                $result->clear();
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * 
     * @param type $index_name
     * @param type $path
     */
    public function imageFile($index_name, $path = null) {

        if ($path === null)
            $path = FCPATH . 'upload/other/img';
        return $this->file($index_name, $path, ['jpg', 'jpeg', 'png', 'bmp', 'gif']);
    }

    /**
     * 
     * @param type $index_name
     * @param type $path
     */
    public function videoFile($index_name, $path = null) {

        if ($path === null)
            $path = FCPATH . 'upload/other/video';
        return $this->file($index_name, $path, ['mov', 'mp4', 'mpeg', 'mp4a', 'avi', 'mpg', '3gp', 'm4a', 'mca', 'wmv','webm'],10);
    }

    /**
     * 
     * @param type $index_name
     * @param type $path
     * @return boolean
     * @throws \Exception
     */
    public function pdfFile($index_name, $path = null) {

        if ($path === null)
            $path = FCPATH . 'upload/other/pdf';
        return $this->file($index_name, $path, ['pdf']);
    }

    /**
     * 
     * @param type $index_name
     * @param type $path
     * @return boolean
     * @throws \Exception
     */
    public function audioFile($index_name, $path = null) {

        if ($path === null)
            $path = FCPATH . 'upload/other/pdf';
        return $this->file($index_name, $path, ['mp3', 'ogg', 'wav', 'mka', 'wma', 'mpga', 'mp2', 'midi', 'mid']);
    }

    /**
     * for uploading files that contains data such as : 
     * excel, txt, xml, ods, csv
     * @param type $index_name
     * @param type $path
     * @return boolean
     * @throws \Exception
     */
    public function dataFile($index_name, $path = null) {

        if ($path === null)
            $path = FCPATH . 'upload/other/datafile';
        return $this->file($index_name, $path, ['csv', 'xls', 'xlsx', 'ods', 'xml', 'txt']);
    }

}
