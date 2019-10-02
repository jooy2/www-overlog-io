<?php
    class KKYData {
        function toArray($data) {
            $arr = explode("\n", $data);
            $arrCount = count($arr);
            // Remove new line character
            for ($i = 0; $i < $arrCount; $i++) {
                $arr[$i] = preg_replace("/\r\n|\r|\n/u", "", $arr[$i]);
            }

            // METADATA
            $result = array(16);
            $result[0] = ""; // host ip 0
            $result[1] = $arr[1];
            $result[2] = $arr[2];
            $result[3] = ""; // timestamp 3

            // CPU VALUE
            $temp = explode(",", $arr[5]);
            $result[4] = substr($temp[0], 2, 5); // 100.0
            $result[5] = substr($temp[1], 2, 5);
            
            // CPU TOP
            $temp = null;
            $temp = [$arr[7], $arr[8], $arr[9], $arr[10], $arr[11]];
            $tempCount = count($temp);
            $result[6] = combineTopValues($temp, $tempCount, $arr[1]);

            // MEM USE
            $temp = null;
            $temp = explode(",", $arr[13]);
            $result[7] = substr($temp[2], 4, 20); // 100.0
            $result[8] = substr($temp[0], 5, 20);

            // MEM TOP
            $temp = null;
            $temp = [$arr[17], $arr[18], $arr[19], $arr[20], $arr[21]];
            $tempCount = count($temp);
            $result[9] = combineTopValues($temp, $tempCount, $arr[1]);
            
            // NETWORK
            $temp = null;
            $temp = explode(",", $arr[23]);
            $result[10] = substr($temp[0], 6, 20);
            $result[12] = substr($temp[0], 6, 20);

            $temp = null;
            $temp = explode(",", $arr[24]);
            $result[11] = substr($temp[1], 9, 20);
            if ($result[11] == "not supported")
                $result[11] = -1;
            $result[13] = substr($temp[1], 9, 20);
            if ($result[13] == "not supported")
                $result[13] = -1;

            // DISK USE
            $temp = null;
            $temp = explode(",", $arr[26]);
            $result[14] = substr($temp[1], 2, 20);
            $result[15] = substr($temp[0], 8, 20);

            $result[16] = null;

            return $result;
        }
    }

    function combineTopValues($arr, $count, $os) {
        $str = "";
        for ($i = 0; $i < $count; $i++) {
            $tempArr = explode(",", $arr[$i]);
            
            if ($os == "WINDOWS")
                $tempArr[1] = preg_replace("/^\//", "", $tempArr[1]);

            $str .= $tempArr[1];
            if ($i != ($count - 1)) $str .= ";;";
        }

        return $str;
    }
?>