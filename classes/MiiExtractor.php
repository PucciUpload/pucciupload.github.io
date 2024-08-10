<?php
declare(strict_types = 1);
require_once "MiiType.php";

final class MiiExtractor
{
    public static function detectTypes(array $file): array
    {
        $types = array();

        foreach (MiiType::values() as $type)
        {
            if ($file["size"] === $type -> getDbSize())
            {
                array_push($types, $type);
            }
        }

        if (empty($types))
        {
            exit("No valid Mii types apply.");
        }

        return $types;
    }

    public static function extractMiis(array $file, MiiType $type): array
    {
        $miis = array();
        $handle = fopen($file["tmp_name"], "rb");
        $isActive = true;
        $miiCount = 0;

        if (!$handle)
        {
            exit("The uploaded database is unreadable.");
        }

        fseek($handle, $type -> getOffset());

        while ($isActive)
        {
            $data = fread($handle, $type -> getSize());

            if (!$data)
            {
                exit("An error occurred during processing.");
            }
            elseif ($miiCount >= $type -> getLimit() || strspn($data, "\0") === strlen($data))
            {
                $isActive = false;
            }
            else
            {
                $data .= str_repeat("\0", $type -> getPadding());
                $miis[sprintf("%s%05d.mii", $type->getPrefix(), $miiCount)] = $data;
                $miiCount++;
            }
        }

        fclose($handle);

        return $miis;
    }

    public static function zipMiis(array $file): string
    {
        $miis = array();
        $types = self::detectTypes($file);
        $archive = new ZipArchive();

        foreach ($types as $type)
        {
            $miis = array_merge($miis, self::extractMiis($file, $type));
        }

        if (empty($miis))
        {
            exit("No Miis were detected.");
        }

        $tempFile = tempnam(sys_get_temp_dir(), "mii");
        
        if (!$archive -> open($tempFile, ZipArchive::OVERWRITE))
        {
            exit("The output could not be created.");
        }
    
        foreach ($miis as $name => $data)
        {
            $archive -> addFromString($name, $data);
        }
    
        $archive -> close();
        $archive = file_get_contents($tempFile);
        unlink($tempFile);
    
        return $archive;
    }
}