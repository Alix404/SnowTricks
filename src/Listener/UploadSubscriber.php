<?php

namespace App\Listener;

use App\Annotation\UploadAnnotationReader;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\PropertyAccess\PropertyAccess;

class UploadSubscriber implements EventSubscriber
{

    /**
     * @var UploadAnnotationReader
     */
    private $reader;

    public function __construct(UploadAnnotationReader $reader)
    {
        $this->reader = $reader;
    }

    public function getSubscribedEvents()
    {
        return [
            'prePersist'
        ];
    }

    public function prePersist(LifecycleEventArgs $eventArgs)
    {

        $entity = $eventArgs->getEntity();
        foreach ($this->reader->getUploadableFields($entity) as $property => $annotation) {
            $accessor = PropertyAccess::createPropertyAccessor();
            $file = $accessor->getValue($entity, $property);
            if ($file instanceof UploadedFile) {
                $filename = $file->getClientOriginalName();
                $file->move($annotation->getPath(), $filename);
                $accessor->setValue($entity, $annotation->getFilename(), $filename);
            }
        }
    }
}