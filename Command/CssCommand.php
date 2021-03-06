<?php
/**
 * @copyright 2012 Rithis Studio LLC
 * @author Vyacheslav Slinko <vyacheslav.slinko@rithis.com>
 */

namespace Rithis\Spriter\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Imagine\Gd\Imagine;
use Rithis\Spriter\Spriter;
use Rithis\Spriter\Formatter\CssFormatter;

class CssCommand extends Command
{
    protected function configure()
    {
        $this->setName('css');
        $this->setDescription('Make sprite and css file');
        $this->addArgument('directory', InputArgument::REQUIRED, 'Directory with images');
        $this->addOption('recursive', 'r', InputOption::VALUE_NONE, 'Scan directory recursively');
        $this->addOption('url', 'u', InputOption::VALUE_REQUIRED, 'URL with sprite', 'sprite.png');
        $this->addOption('sprite-path', 's', InputOption::VALUE_REQUIRED, 'Path where sprite must be saved', 'sprite.png');
        $this->addOption('css-path', 'c', InputOption::VALUE_REQUIRED, 'Path where stylesheet must be saved', 'sprite.css');
        $this->addOption('padding', 'p', InputOption::VALUE_REQUIRED, 'Image padding', 10);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $spriter = new Spriter(new Imagine());

        $sprite = $spriter->scan($input->getArgument('directory'), $input->getOption('recursive'));
        $sprite->setPadding($input->getOption('padding'));

        $formatter = new CssFormatter($input->getOption('url'));
        $css = $formatter->format($sprite);

        $sprite->getImage()->save($input->getOption('sprite-path'));

        file_put_contents($input->getOption('css-path'), $css);
    }
}
