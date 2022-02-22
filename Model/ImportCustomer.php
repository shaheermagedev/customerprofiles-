<?php

declare(strict_types=1);

namespace Sh\CustomerImport\Model;
use Exception;
use \Sh\CustomerImport\Helper\Utility;
use \Magento\Store\Model\StoreManagerInterface;
use \Magento\Customer\Model\CustomerFactory;



class ImportCustomer
{


    /**
     * @var Utility
     */
    protected Utility $utility;
    /**
     * @var StoreManagerInterface
     */
    protected StoreManagerInterface $storeManager;

    /**
     * @var CustomerFactory
     */
    protected CustomerFactory $customerFactory;


    /**
     * @param Utility $utility
     * @param StoreManagerInterface $storeManager
     * @param CustomerFactory $customerFactory
     */
    public function __construct(
        \Sh\CustomerImport\Helper\Utility      $utility,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Customer\Model\CustomerFactory    $customerFactory
    )
    {

        $this->utility = $utility;
        $this->customerFactory = $customerFactory;
        $this->storeManager = $storeManager;
    }


    /**
     * @param $dataRow
     */
    public function createCustomer($dataRow)
    {

        try {
            // in future we can use the customer import module 
            $store = $this->storeManager->getStore();
            $websiteId = $this->storeManager->getStore()->getWebsiteId();
            $customer = $this->customerFactory->create();
            $customer->setWebsiteId($websiteId);
            $customer->loadByEmail($dataRow['emailaddress']);
            //check if customer exisst 
            if (!$customer->getId()) {
               
                $customer->setWebsiteId($websiteId)
                    ->setStore($store)
                    ->setFirstname($dataRow['fname'])
                    ->setLastname($dataRow['lname'])
                    ->setEmail($dataRow['emailaddress']);
                //->setPassword($data['customer']['password']);
                $customer->save();
                $this->utility->log($dataRow['emailaddress'] . " account created");
            } else {
                $this->utility->log($dataRow['emailaddress'] . " account already exists");

            }
        } catch (Exception $e) {
            $this->utility->log('We can\'t save the customer .');
        }


    }


}
