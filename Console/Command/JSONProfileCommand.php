<?php
declare(strict_types=1);

namespace Sh\CustomerImport\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Sh\CustomerImport\Model\Profile\ImportJSON;
use Sh\CustomerImport\Helper\Utility;
use Exception;


class JSONProfileCommand extends Command
{




    //command line options
    const NAME_ARGUMENT = "filename";
    const NAME_OPTION = "filename";


    /**
     * @var Utility
     */
    protected Utility $utility;

    /**
     * @var ImportJSON
     */
    protected ImportJSON $importJSON;


    public function __construct(
        \Sh\CustomerImport\Model\Profile\ImportJSON $importJSON,
        \Sh\CustomerImport\Helper\Utility $utility

    )
    {
        $this->importJSON = $importJSON;
        $this->utility = $utility;

        parent::__construct();
    }

    /**
     *  configure commands
     */
    protected function configure()
    {
        $this->setName("customer:import:sample-json");
        $this->setDescription("Import Customer Profile - JSON Format");
        $this->setDefinition([
            new InputArgument(self::NAME_ARGUMENT, InputArgument::REQUIRED, "filename"),
            new InputOption(self::NAME_OPTION, "-f", InputOption::VALUE_NONE, "JSON File")
        ]);
        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void
     */
    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ) {
        $fileName = $input->getArgument(self::NAME_ARGUMENT);
        $option = $input->getOption(self::NAME_OPTION);
        $output->writeln("JSON " . $fileName);

        if (!empty($fileName)) {
            if(!$this->utility->isValidJSONFileType($fileName))
            {
                $output->writeln("Invalid File Type " .$fileName);
                return;
            }
            if(!$this->utility->isFileExists($fileName))
            {
                $output->writeln("File does not exist " .$fileName);
                return;
            }

            $this->importJSON->importCustomersByJSON($fileName);

        }
    }


}

