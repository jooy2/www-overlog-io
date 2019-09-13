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
            $result[0] = "";
            $result[1] = $arr[0];
            $result[2] = $arr[1];

            // CPU VALUE
            $temp = explode(",", $arr[3]);
            $result[3] = substr($temp[0], 2, 5); // 100.0
            $result[4] = substr($temp[1], 2, 5);
            
            // CPU TOP
            $temp = null;
            $temp = [$arr[5], $arr[6], $arr[7], $arr[8], $arr[9]];
            $tempCount = count($temp);
            $result[5] = combineTopValues($temp, $tempCount);

            // MEM USE
            $temp = null;
            $temp = explode(",", $arr[11]);
            $result[6] = substr($temp[2], 4, 20); // 100.0
            $result[7] = substr($temp[0], 5, 20);

            // MEM TOP
            $temp = null;
            $temp = [$arr[15], $arr[16], $arr[17], $arr[18], $arr[19]];
            $tempCount = count($temp);
            $result[8] = combineTopValues($temp, $tempCount);
            
            // NETWORK
            $temp = null;
            $temp = explode(",", $arr[21]);
            $result[9] = substr($temp[0], 6, 20);
            $result[11] = substr($temp[0], 6, 20);

            $temp = null;
            $temp = explode(",", $arr[22]);
            $result[10] = substr($temp[1], 9, 20);
            if ($result[10] == "not supported")
                $result[10] = -1;
            $result[12] = substr($temp[1], 9, 20);
            if ($result[12] == "not supported")
                $result[12] = -1;

            // DISK USE
            $temp = null;
            $temp = explode(",", $arr[24]);
            $result[13] = substr($temp[1], 2, 20);
            $result[14] = substr($temp[0], 8, 20);

            $result[15] = null;

            return $result;
        }
    }

    function combineTopValues($arr, $count) {
        $str = "";
        for ($i = 0; $i < $count; $i++) {
            $tempArr = explode(",/", $arr[$i]);
            $str .= $tempArr[1];
            if ($i != ($count - 1)) $str .= ";;";
        }

        return $str;
    }
?>