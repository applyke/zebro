<?php
namespace Application\Service;

use Application\ApplicationTraits\DoctrineEntityManagerAwareTrait;
use Application\ApplicationTraits\LoggerAwareTrait;

class PrepareObjectService
{
    use LoggerAwareTrait;
    use DoctrineEntityManagerAwareTrait;

    public function prepareAlbumObject(\Application\Entity\Album $album)
    {
        return array(
            'id' => $album->getId(),
            'title' => $album->getTitle(),
            'code' => $album->getCode(),
            'status' => $album->getStatus(),
            'created' => $album->getCreated(),
        );
    }

    public function prepareImageObject(\Application\Entity\Image $image)
    {
        return array(
            'id' => $image->getId(),
            'path' => $image->getPath(),
            'url' => $image->getUrl(),
            'width' => $image->getWidth(),
            'height' => $image->getHeight(),
            'created' => $image->getCreated(),
        );
    }
}