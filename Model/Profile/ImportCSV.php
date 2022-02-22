<?php

declare(strict_types=1);

namespace Sh\CustomerImport\Model\Profile;
use Exception;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use \Sh\CustomerImport\Model\ImportCustomer;
use \Sh\CustomerImport\Helper\Utility;


class ImportCSV
{




    /**
     * @var Utility
     */
    protected Utility $utility;


    /**
     * @var ImportCustomer
     */
    protected ImportCustomer $importCustomer;


    /**
     * @param Utility $utility
     * @param ImportCustomer $importCustomer
     */
    public function __construct(
        \Sh\CustomerImport\Helper\Utility $utility,
        \Sh\CustomerImport\Model\ImportCustomer $importCustomer
    ) {
        $this->utility = $utility;
        $this->importCustomer =$importCustomer;
    }


    /**
     * Import CSV and into Customers
     * @param $fileName
     * @throws Exception
     */
    public function importCustomersByCSV($fileName)
    {
        ///$this->utility->log("Reading CSV");
        $csvContent= $this->utility->getCsvFileData($fileName);
        $csvAssociatedArray=$this->createAssociativeArray($csvContent); // or use existing CSV library
        $this->utility->log("Records Found: ". count($csvAssociatedArray));
        $this->importCustomers($csvAssociatedArray);

    }

    /**
     * Loop Through  array and insert in the customer object
     * @param $csvData
     */
    private function importCustomers($csvData){

        //lopping data or split as per need
        foreach ( $csvData as $csvRow)
        {
            try {
                // call the model to insert dat
                $this->importCustomer->createCustomer($csvRow);
            } catch (NoSuchEntityException $e) {
            } catch (LocalizedException $e) {
                $this->utility->log($e->getMessage());
            }
        }
    }

    /**
     * @param $csvRows
     * @return array
     */
    public function createAssociativeArray($csvRows): array
    {
        try {
            $csvAssociatedArray= array();
            $csvHeader = array_shift($csvRows);
            foreach ($csvRows as $csvRow) {
                $csvAssociatedArray[] = array_combine($csvHeader, $csvRow);
            }
            return $csvAssociatedArray;

        } catch (Exception $e) {
        $this->utility->log("ERROR: ".$e->getMessage());
        }

    }




}
