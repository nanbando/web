<?php

namespace AppBundle\Command;

use AppBundle\Storage\Storage;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AddZipCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('app:add')
            ->addArgument('fileName');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var Storage $storage */
        $storage = $this->getContainer()->get('app.storage');
        $storage->write($input->getArgument('fileName'));

        $this->getContainer()->get('doctrine.orm.entity_manager')->flush();
    }
}
