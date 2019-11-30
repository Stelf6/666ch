<?php 

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

class MediaFile
{
    private $filename;

    public function getFilename()
    {
        return $this->filename;
    }

    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }
}