<?php
namespace App\Utils;
use DirectoryIterator;

class DkvideoHelper {
    public static function computeStartFrames($startFrame, $timeAlignment) {
        $startFrame = intval($startFrame);
        $startFrames = [];
        foreach ($timeAlignment as  $value) {
            $startFrame +=  intval($value);
            $startFrames[] = $startFrame;
        }
        return $startFrames;
    }

    public static function getOutputDir($inputDir) {
        return dirname($inputDir, 2).'/output/'.basename($inputDir);
    }

    public static function getAllVideoFile($dir) {
        $dirIterator = new DirectoryIterator($dir);
        $allVideoFile = [];
        foreach($dirIterator as $fileInfo) {
            if($fileInfo->isFile() && in_array($fileInfo->getExtension(), ['avi', 'mp4', 'mov'])) {
                $allVideoFile[] = $fileInfo->getFilename();
            }
        }
        return $allVideoFile;
    }

    public static function getVideoFileCount($dir) {
        return count(self::getAllVideoFile($dir));
    }

    public static function evalSerialNumberDir($inputDir) {
        $allFile = self::getAllVideoFile($inputDir);
        //文件件错误
        if(empty($allFile)) {
            return false;
        }
        $oneFile = $allFile[0];
        $filesCount = count($allFile);
        $extension = substr(strrchr($oneFile, '.'), 1);
        switch ($extension) {
            case 'avi':
                // 获取类似sn_0000001的序列前缀
                $sn = substr($oneFile, 0, strpos($oneFile, '_', 3));
                return $sn;
            case 'mp4':
                return "gopro_${filesCount}_camera";
            case 'mov':
                return "bmpcc_${filesCount}_camera";
            default:
                return false;
        }
    }

    public static function cameraRingRadius($cameraType, $renderType) {
        $radius = null;
        if ($renderType === 'FACEBOOK_2D') {
            $radius = 10000;
        } elseif ($renderType === 'FACEBOOK_3D') {
            switch(strtoupper($cameraType)) {
                case 'BMPCC':
                    $radius = 19;
                    break;
                case 'GOPRO':
                    $radius = 15;
                    break;
                default:
                    $radius = 13;
            }
        } 
        return $radius;
    }

    public static function cameraSaveType($camera_type) {
        $saveType = '';
        switch(strtoupper($camera_type)) {
            case 'BMPCC':
                $saveType = '.tiff';
                break;
            default:
                $saveType = '.jpg';
        }
        return $saveType;
    }

    public static function cameraSettingName($cameraType, $renderType) {
        if (strtoupper($renderType) === 'PREVIEW') {
            return strtolower($cameraType).'_camera_setting_visiondk.xml';
        }
        $camera = strtolower($cameraType);
        $algo = strtolower(explode('_', $renderType)[0]);
        return "{$camera}_camera_setting_{$algo}.xml";
    }
}
?>