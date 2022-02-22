# Custom Import Profiles  


# Method 1

 - run the command on CLI  `composer require sh/profile-customerimport`
 - Enable module `php bin/magento module:enable Sh_CustomerImport` 
 - Apply Module changes to Magento `php bin/magento setup:upgrade`
 - Compile Code to see any errors `php bin/magento s:d:c`
 - Deploy static `php bin/magento s:s:dep -f` 


# Method 2

 - Extract code here `app/code/Sh/CustomerImport`
 - Enable module `php bin/magento module:enable Sh_CustomerImport` 
 - Apply Module changes to Magento `php bin/magento setup:upgrade`
 - Compile Code to see any errors `php bin/magento setup:di:compile`
 - Deploy static `php bin/magento setup:static-content:deploy -f`  

# Cli Commands to execute 

 - Console Command
    - CsvProfile  **customer:import:sample-csv**
    - JSONProfile **customer:import:sample-json**
    - File Name is Required here -f --filename 
    - `php bin/magento customer:import:sample-csv  sample.csv`
    - `php bin/magento customer:import:sample-json sample.json`
    - `php bin/magento customer:import:sample-csv --filename sample.csv`
    - `php bin/magento customer:import:sample-csv -f sample.csv`
    
# Tested on Magento 2.4.3 with php 7.4
 
