<?php


namespace App\Handler;


use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class UploadHandler
{
    /**
     * @var PropertyAccessor
     */
    private $accessor;

    public function __construct()
    {
        $this->accessor = PropertyAccess::createPropertyAccessor();
    }

    public function uploadFile($entity, $property, $annotation)
    {
        $file = $this->accessor->getValue($entity, $property);
        if ($file instanceof UploadedFile) {
            $filename = $file->getClientOriginalName();
            $file->move($annotation->getPath(), $filename);
            $this->accessor->setValue($entity, $annotation->getFilename(), $filename);
        }
    }

    public function setFileFromFilename($entity, $property, $annotation)
    {
        $file = $this->getFileFromFilename($entity, $annotation);
        if ($file !== null) {
            $this->accessor->setValue($entity, $property, $file);
        }
    }

    /**
     * @param $entity
     * @param $annotation
     * @return File|null
     */
    private function getFileFromFilename($entity, $annotation)
    {
        $filename = $this->accessor->getValue($entity, $annotation->getFilename());
        if (empty($filename)) {
            return null;
        } else {
            return new File($annotation->getPath() . DIRECTORY_SEPARATOR . $filename);
        }
    }

    public function removeOldFile($entity, $annotation)
    {
        $filename = $this->accessor->getValue($entity, $annotation->getFilename());
        if (empty($filename)) {
            return null;
        } else {
        $file = $this->getFileFromFilename($entity, $annotation);
            @unlink($file->getRealPath());
        }
    }

    public function removeFile($entity, $property)
    {
        $file = $this->accessor->getValue($entity, $property);
        if ($file instanceof File) {
            @unlink($file->getRealPath());
        }
    }
}