<?php

namespace Rsh\TermsAndConditions\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class TextToHtmlCommand extends Command
{
    /**
     * Configures the command
     */
    protected function configure()
    {
        $this->setName('convert:text-to-html')
            ->setDescription('parses word doc to string');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filePath = sprintf('%s/../../../../public/terms-and-conditions.txt', __DIR__);

        if (!file_exists($filePath)) {
            $output->writeln('File not found');
            return;
        }

        $lines = file(sprintf('%s/../../../../public/terms-and-conditions.txt', __DIR__));
        $html = '';

        $dlStarted = false;

        $ddNode = '';
        $dlNode = '';
        $headerCount = 1;
        $ddCount = 1;
        foreach ($lines as $lineNumber => $line) {


            // close previous dl element
            if ($this->lineMatchesHeader($line) && $dlStarted ) {

                $html .= '<dl>' . PHP_EOL . $dlNode . PHP_EOL . '</dl>';
                $html .='</div></div></div></section>';
                $dlStarted = false;

            }
            // start new header
            if ($this->lineMatchesHeader($line)) {

                $html .= '<section class="pt-meidum pb-medium">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">';
                $html .= PHP_EOL . $this->wrapHeader($line);


                $headerCount += 1;
                $ddCount = 1;
                $dlNode = '';

                $dlStarted = true;

                continue;
            }
            $ddNode .= $line;
            // end previous dd and start a new
            if (trim((string)$line) === ''  && $dlStarted) {
                $dlNode .= sprintf('<dt>%d.%d</dt>', $headerCount, $ddCount);
                $dlNode .= PHP_EOL . '<dd>' . $ddNode . '</dd>' . PHP_EOL . PHP_EOL;
                $ddCount += 1;

                $ddNode = '';

            }

            // end dl
            if ($lineNumber === count($lines) -1  && $dlStarted ) {
                $html .= $ddNode;
                $ddNode = '';

            }
        }

        file_put_contents(sprintf('%s/../../../../public/terms-and-conditions.html', __DIR__), $html);

    }

    private function lineMatchesHeader($line)
    {
        if (substr($line, 0, strlen('Artikel')) === 'Artikel') {
            return true;
        }
        return false;
    }

    private function wrapHeader($line)
    {
        return sprintf('<h1>%s</h1>%s', str_replace(PHP_EOL, '',$line), PHP_EOL);
    }
} 