<?php declare(strict_types=1);

namespace App\Command;

use App\Service\CodeGenerator;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class GenerateCodesCommand extends Command
{
    /** @var CodeGenerator */
    private $codeGenerator;

    public function __construct(CodeGenerator $codeGenerator)
    {
        $this->codeGenerator = $codeGenerator;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('app:generate-codes')
            ->setDescription('Generates unique codes.')
            ->addOption('number-of-codes', null, InputOption::VALUE_REQUIRED, 'Number of codes to generate', 10)
            ->addOption('length-of-code', null, InputOption::VALUE_REQUIRED, 'Length of a single code', 5)
            ->addOption('file-path', null, InputOption::VALUE_REQUIRED, 'Path of the output file', 'kody.txt');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $codesQuantity = (int) $input->getOption('number-of-codes');
        $codeLength = (int) $input->getOption('length-of-code');
        $filePath = $input->getOption('file-path');

        $io->title('Generating codes. Single code length: ' . $codeLength . '. Quantity ' . $codesQuantity . '. File: ' . $filePath);

        $this->codeGenerator->setCodeLength($codeLength);
        $this->codeGenerator->setCodesQuantity($codesQuantity);
        $generateCodes = $this->codeGenerator->getGenerator();

        try {
            $outputFile = fopen($filePath, 'x');
        } catch (\Throwable $e) {
            $io->warning('File already exists! Please move/delete it.');
            return 1;
        }

        $io->comment('Progress in chunks of 1000');
        $io->progressStart($codesQuantity / 1000);

        $progress = 0;
        foreach ($generateCodes() as $code) {
            fwrite($outputFile, $code . PHP_EOL);
            $progress++;
            if ($progress === 1000) {
                $io->progressAdvance(1);
                $progress = 0;
            }
        }

        $io->newLine(2);
        $io->success('Codes generated!');
        $io->newLine();

        fclose($outputFile);

        return 0;
    }
}