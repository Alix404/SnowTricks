<?php


namespace App\Annotation;

use Doctrine\Common\Annotations\Annotation\Target;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
class UploadableField
{
    /**
     * @var string
     */
    private $filename;
    /**
     * @var string
     */
    private $path;

    public function __construct(array $option)
    {
        $this->filename = $option['filename'];
        $this->path = $option['path'];
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

}