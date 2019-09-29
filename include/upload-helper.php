<?php
class UploadHelper {
    protected $dest;
    protected $ext;
    protected $chunkTime;
    
    public function __construct() {
        if (!is_writable(FILES_PATH)) {
            error_log("Unknown error");
            return false;
        }

        //throw new \Exception('Unable to write');
        $dest = FILES_PATH.DIRECTORY_SEPARATOR.'tmp';
        $rdest = FILES_PATH.DIRECTORY_SEPARATOR;

        if (!is_dir($dest)) {
            mkdir($dest, 0755);
            chmod($dest, 0755);
        }

        $this->dest = $dest;
        $this->rdest = $rdest;
        $this->ext  = array( 1 => 'txt', 2 => 'text', 3 => 'json');
        $this->chunkTime = 86400 * 2;
    }

    public function fileName() {
        list($usec) = explode(' ', microtime());
        $usec = preg_replace('#[^0-9]#', '', $usec);

        return md5(date('YmdHis', time()).$usec);
    }

    public function chunkDelete() {
        foreach (scandir($this->dest) as $c) {
            if ($c == '.' || $c == '..')
                continue;

            $f = $this->dest . DIRECTORY_SEPARATOR . $c;

            if (is_dir($f))
                rmdir($f);

            if (time() - filemtime($f) > $this->chunkTime)
                unlink($f);
        }
    }
    
    public function upload() {
        $result = array();

        // chunk delete
        $this->chunkDelete();

        $file = $_FILES['file']['tmp_name'];
        $extenArr = explode('.', $_FILES['file']['name']);
        $exten = $extenArr[1];

        if ($file) {
            $name = $this->fileName();
            $rdest = $this->rdest.$name.'.'.$exten;
             
            move_uploaded_file($file, $rdest);
            $result['file_name'] = $name;
            $result['file_ext'] = $exten;
            $result['file_path'] = $rdest;
        }

        return $result;
    }
}