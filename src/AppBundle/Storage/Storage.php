<?php

namespace AppBundle\Storage;

use AppBundle\Repository\FileRepository;
use Emgag\Flysystem\Hash\HashPlugin;
use League\Flysystem\Filesystem;
use League\Flysystem\Plugin\ListFiles;
use League\Flysystem\ZipArchive\ZipArchiveAdapter;

class Storage
{
    /**
     * @var FileRepository
     */
    private $fileRepository;

    /**
     * @var Filesystem
     */
    private $localFilesystem;

    /**
     * @param FileRepository $fileRepository
     * @param Filesystem $localFilesystem
     */
    public function __construct(FileRepository $fileRepository, Filesystem $localFilesystem)
    {
        $this->fileRepository = $fileRepository;
        $this->localFilesystem = $localFilesystem;
    }

    public function write($fileName)
    {
        $filesystem = new Filesystem(new ZipArchiveAdapter($fileName));
        $filesystem->addPlugin(new ListFiles());
        $filesystem->addPlugin(new HashPlugin());

        foreach ($filesystem->listFiles('/', true) as $file) {
            $hash = $filesystem->hash($file['path']);
            $fileName = $this->getFileName($hash);
            if (!$this->localFilesystem->has($fileName)) {
                $this->localFilesystem->writeStream($fileName, $filesystem->readStream($file['path']));
            }

            $entity = $this->fileRepository->createNew();
            $entity->setName(basename($file['path']));
            $entity->setPath($file['path']);
            $entity->setHashFile($fileName);
        }
    }

    private function getFileName($hash)
    {
        return sprintf('/%s/%s/%s.data', substr($hash, 0, 2), substr($hash, 2, 2), substr($hash, 4));
    }
}
