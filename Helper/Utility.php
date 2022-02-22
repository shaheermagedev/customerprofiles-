<?php
declare(strict_types=1);

namespace Sh\CustomerImport\Helper;

use Exception;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\Exception\FileSystemException;
use Symfony\Component\Console\Output\OutputInterface;
use \Magento\Framework\Filesystem\Driver\File;
use \Magento\Framework\File\Csv;
use \Psr\Log\LoggerInterface;
use \Magento\Framework\Serialize\Serializer\Json;
use \Symfony\Component\Console\Output\ConsoleOutput;
use \Magento\Framework\App\Helper\Context;

class Utility extends AbstractHelper
{


    protected string $csvDelimiter = ",";
    protected File $fileDriver;
    protected Csv $csvReader;
    protected LoggerInterface $logger;
    protected Json $jsonSerializer;
    protected ConsoleOutput $output;

    /**
     * @var string[]
     */
    protected array $allowedCSVFileTypes = array('csv', 'txt', 'tcsv');
    /**
     * @var string[]
     */
    protected array $allowedJSONFileTypes = array('json', 'jsonp', 'txt');


    /**
     * @param Context $context
     * @param File $fileDriver
     * @param Csv $csvReader
     * @param Json $jsonSerializer
     * @param LoggerInterface $logger
     * @param ConsoleOutput $output
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context           $context,
        \Magento\Framework\Filesystem\Driver\File       $fileDriver,
        \Magento\Framework\File\Csv                     $csvReader,
        \Magento\Framework\Serialize\Serializer\Json    $jsonSerializer,
        \Psr\Log\LoggerInterface                        $logger,
        \Symfony\Component\Console\Output\ConsoleOutput $output
    )
    {
        $this->fileDriver = $fileDriver;
        $this->jsonSerializer = $jsonSerializer;
        $this->csvReader = $csvReader;
        $this->logger = $logger;
        $this->output = $output;
        parent::__construct($context);
    }


    public function getCsvFileData($fileName)
    {
        $csvData = [];

        try {

            $this->log('Reading Data from ' . $fileName);
            $this->csvReader->setDelimiter($this->getCsvDelimiter());
            //$data = $this->csvReader->getDataPairs($fileName);
            return $this->csvReader->getData($fileName);


        } catch (FileSystemException $e) {
            $this->log($e->getMessage());
            return false;
        }

    }

    /**
     * ReadJSOn File and Convert into Array
     * @param $fileName
     * @return bool|string
     */
    public function getJSONFileData($fileName)
    {
        $jsonData = [];
        try {
           // $this->log('Reading JSON ' . $fileName);
            $jsonData = $this->fileDriver->fileGetContents($fileName);
            return $this->jsonSerializer->unserialize($jsonData);
        } catch (FileSystemException $e) {
            $this->log($e->getMessage());
            return false;
        }

    }


    /**
     * Check if File existing on the system
     * @param $fileName
     * @return bool
     */
    public function isFileExists($fileName): bool
    {
        try {
            if ($this->fileDriver->isExists($fileName)) {
                return true;
            } else
                return false;
        } catch (FileSystemException $e) {
            $this->log($e->getMessage());
            return false;
        }


    }

    /**
     * Logger as System output or system log
     * @param $logContent
     */
    public function log($logContent)
    {
        //$this->logger->info($logContent);
        $this->output->writeln($logContent);
    }




    /**
     * @return string
     */
    public function getCsvDelimiter(): string
    {
        return $this->csvDelimiter;
    }

    /**
     * @param string $csvDelimiter
     */
    public function setCsvDelimiter(string $csvDelimiter): void
    {
        $this->csvDelimiter = $csvDelimiter;
    }



    /**
     * Validate JSON File File Extension Type
     * @param $fileName
     * @return bool
     */
    public function isValidJsonFileType($fileName): bool
    {
        $ext = pathinfo($fileName, PATHINFO_EXTENSION);
        if (!in_array($ext, $this->allowedJSONFileTypes)) {
            return false;
        } else
            return true;
    }

    /**
     * Validate CSV File File Extension Type
     * @param $fileName
     * @return bool
     */
    public function isValidCSVFileType($fileName): bool
    {
        $ext = pathinfo($fileName, PATHINFO_EXTENSION);
        if (!in_array($ext, $this->allowedCSVFileTypes)) {
            return false;
        } else
            return true;
    }


}

