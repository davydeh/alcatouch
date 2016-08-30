<?php
namespace Proyecto\AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class File {

    public $id;

    public $name;
    
    public $path;
    
    //protected $projectName;
    
    /*
     * @Assert\File(maxSize="1000000")
     */
    protected $file;
    
    public function setFile(UploadedFile $file = null) {
        $this->file = $file;
    }

    public function getFile() {
        return $this->file;
    }
    
    protected function getAbsolutePath() {
        return null === $this->path
                ? null
                : $this->getUploadRootDir().'/'.$this->path;
    }
    
    public function getWebPath() {
        return null === $this->path
                ? null
                : $this->getUploadDir().'/'.$this->path;
    }
    
    protected function getUploadRootDir() {
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }
    
    protected function getUploadDir() {
        return 'uploads/documents';
    }
    
    public function upload() {
        
        if(null === $this->getFile()) {
            return;
        }
        
        // Deberia sanearse antes de mover
        
        // Mover el archivo
        $this->getFile()->move(
                $this->getUploadRootDir(),
                $this->name.'.inp'
            );
        
        $this->path = $this->name.'.inp';
        
        // Borrarlo pues ya no se necesita
        $this->file = null;
    }
    
}