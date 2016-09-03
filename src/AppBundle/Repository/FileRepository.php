<?php

namespace AppBundle\Repository;

use AppBundle\Entity\File;
use Doctrine\ORM\EntityRepository;

/**
 * FileRepository.
 */
class FileRepository extends EntityRepository
{
    /**
     * Return new file entity.
     *
     * @return File
     */
    public function createNew()
    {
        $entity = new File();
        $this->_em->persist($entity);

        return $entity;
    }
}
