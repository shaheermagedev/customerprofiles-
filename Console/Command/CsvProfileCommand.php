<?php

declare(strict_types=1);

namespace Sh\CustomerImport\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Sh\CustomerImport\Model\Profile\ImportCSV;
use Exception;
use Sh\CustomerImport\Helper\Utility;



class CsvProfileCommand extends Command
{

    const NAME_ARGUMENT = "filename";
    const NAME_OPTION = "filename";


    /**
     * @var ImportCSV
     */
    protected ImportCSV $importCSV;

    /**
     * @var Utility
     */
    protected Utility $utility;


    /**
     * @param ImportCSV $importCSV
     * @param Utility $utility
     */
    public function __construct(
        \Sh\CustomerImport\Model\Profile\ImportCSV $importCSV,
        \Sh\CustomerImport\Helper\Utility $utility
    )
    {
        $this->utility = $utility;
        $this->importCSV = $importCSV;
        parent::__construct();
    }


    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void
     * @throws Exception
     */
    protected function execute(
        InputInterface  $input,
        OutputInterface $output
    ) {
        $fileName = $input->getArgument(self::NAME_ARGUMENT);
        $option = $input->getOption(self::NAME_OPTION);


        if (!empty($fileName)) {
            if (!$this->utility->isValidCSVFileType($fileName)) {
                $output->writeln("Invalid File Type " . $fileName);
                return;
            }

            if (!$this->utility->isFileExists($fileName)) {
                $output->writeln("File does not exist " . $fileName);
                return;
            }

           // $output->writeln("Importing CSV " . $fileName);
            $this->importCSV->importCustomersByCSV($fileName);
        }
    }


    /**
     * set command for magento
     */
    protected function configure()
    {
        $this->setName("customer:import:sample-csv");
        $this->setDescription("Import Customer Profile - CSV Format");
        $this->setDefinition([
            new InputArgument(self::NAME_ARGUMENT, InputArgument::REQUIRED, "filename"),
            new InputOption(self::NAME_OPTION, "-f", InputOption::VALUE_NONE, "CSV File")
        ]);
        parent::configure();
    }


}
