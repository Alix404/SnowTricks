<?php

namespace App\Listener;

use App\Annotation\UploadAnnotationReader;
use App\Handler\UploadHandler;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;

class UploadSubscriber implements EventSubscriber
{

    /**
     * @var UploadAnnotationReader
     */
    private $reader;
    /**
     * @var UploadHandler
     */
    private $handler;

    public function __construct(UploadAnnotationReader $reader, UploadHandler $handler)
    {
        $this->reader = $reader;
        $this->handler = $handler;
    }

    public function getSubscribedEvents()
    {
        return [
            'prePersist',
            'preUpdate',
            'postLoad',
            'postRemove'
        ];
    }

    public function preEvent(LifecycleEventArgs $eventArgs) {
        $entity = $eventArgs->getEntity();
        foreach ($this->reader->getUploadableFields($entity) as $property => $annotation) {
            $this->handler->uploadFile($entity, $property, $annotation);
        }
    }

    public function prePersist(LifecycleEventArgs $eventArgs)
    {
        $this->preEvent($eventArgs);
    }

    public function preUpdate(LifecycleEventArgs $eventArgs)
    {
        $this->preEvent($eventArgs);
    }

    public function postLoad(LifecycleEventArgs $eventArgs)
    {
        $entity = $eventArgs->getEntity();
        foreach ($this->reader->getUploadableFields($entity) as $property => $annotation) {
            $this->handler->setFileFromFilename($entity, $property, $annotation);
        }
    }

    public function postRemove(LifecycleEventArgs $eventArgs) {
        $entity = $eventArgs->getEntity();
        foreach($this->reader->getUploadableFields($entity) as $property => $annotation) {
            $this->handler->removeFile($entity, $property);
        }
    }
}