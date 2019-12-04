<?php 

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

class ThreadForm
{
    private $filename;
    private $threadText;

    public function getFilename()
    {
        return $this->filename;
    }

    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    public function getThreadText()
    {
        return $this->threadText;
    }

    public function setThreadText($text)
    {
        $this->threadText = $text;

        return $this;
    }
}