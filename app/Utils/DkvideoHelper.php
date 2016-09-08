<?php
namespace App\Utils;
use DirectoryIterator;

class DkvideoHelper {
    public static function computeStartFrames($startFrame, $timeAlignment) {
        $startFrames = [];
        foreach ($timeAlignment as  $value) {
            $startFrame +=  $value;
            $startFrames[] = $startFrame;
        }
        return $startFrames;
    }

    public static function getOutputDir($inputDir) {
        return dirname($inputDir, 2).'/output/'.basename($inputDir);
    }

    public static function getAllVideoFileInfo($dir) {
        $dirIterator = new DirectoryIterator($dir);
        $allVideoFileInfo = [];
        foreach($dirIterator as $fileInfo) {
            if($fileInfo->isFile() and in_array($fileInfo->getExtension(), ['avi', 'mp4', 'mov'])) {
                $allVideoFileInfo[] = $fileInfo;
            }
        }
        return $allVideoFileInfo;
    }

    public static function getVideoFileCount($dir) {
        return count(self::getAllVideoFileInfo($dir));
    }

    public static function evalSerialNumberDir($inputDir) {
        $allFileInfo = self::getAllVideoFileInfo($inputDir);
        //文件件错误
        if(empty($allFileInfo)) {
            return false;
        }
        $oneFileInfo = $allFileInfo[0];
        $filesCount = count($allFileInfo);
        $extension = $oneFileInfo->getExtension();
        switch ($extension) {
            case 'avi':
                // 获取类似sn_0000001的序列前缀
                $filename = $oneFileInfo->getFilename();
                $sn = substr($filename, 0, strpos($filename, '_', 3));
                return $sn;
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