<?php
namespace App\Utils;

class DkvideoHelper {
    public static function computeStartFrames($startFrame, $timeAlignment) {
        $startFrames = [];
        foreach ($timeAlignment as  $value) {
            $startFrame +=  $value;
            $startFrames[] = $startFrame;
        }
        return $startFrames;
    }

    public static function evalSerialNumberDir($inputDir) {
        $fileNames = scandir($inputDir);
        if($fileNames === false) {
            return false;
        }
        $oneFileName = $files[0];
        $filesCount = count($fileNames);
        $extension = substr(strrchr($oneFileName, '.'), 1);
        switch ($extension) {
            case 'avi':
                // 获取类似sn_0000001的序列前缀
                $sn = substr($oneFileName, 0, strpos($oneFileName, '_', 3) );
                return $sn;
                break;
            case 'mp4':
                return "gopro_${filesCount}_camera";
            case 'mov':
                return "bmpcc_${filesCount}_camera";
            default:
                return false;
        }
    }
}
?>