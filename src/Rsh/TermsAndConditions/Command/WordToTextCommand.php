<?php
namespace Rsh\TermsAndConditions\Command;

use PhpOffice\PhpWord\Reader\Word2007;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class WordToTextCommand extends Command
{
    /**
     * Configures the command
     */
    protected function configure()
    {
        $this->setName('convert:word-to-text')
            ->setDescription('parses text to html');
    }
    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $source = sprintf('%s/../../../../terms-and-conditions-parser/Terms_and_conditions_Tulsie_BV DE NL 2015 (1).docx', __DIR__);
        $phpWord = \PhpOffice\PhpWord\IOFactory::load($source);
        $sections = $phpWord->getSections();
        $text = '';
        foreach ( $sections as $section ) {
            $elements = $section->getElements();
            $text = $this->toText($elements);
        }
        file_put_contents(sprintf('%s/../../../../public/terms-and-conditions.txt', __DIR__), $text);
        $output->writeln('Done');
    }

    private function toText(array $elements)
    {
        $text = '';
        foreach ($elements as $element) {
            if (  $element instanceof \PhpOffice\PhpWord\Element\TextRun ) {
                $text .= $this->headerToText($element);
                continue;
            }
            if (method_exists ($element, 'getText') && null !== $element->getText()) {
                $text .= PHP_EOL . $element->getText() . PHP_EOL;
            }
        }
        return $text;
    }

    private function headerToText(\PhpOffice\PhpWord\Element\TextRun $element) {
        $elements = $element->getElements();
        $text = '';
        foreach ($elements as $element) {
            if ($element->getText() === 'A') {
                $text .= PHP_EOL;
            }
            $text .= $element->getText();
        }
        return $text;
    }
} 