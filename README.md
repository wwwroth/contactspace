 # wwwroth/contactspace
 
 A quick, simple PHP SDK for interfacing with contactSPACE's API. Only JSON ?outputtype is supported.
 
 ### Installing
 
```bash
composer require wwwroth/contactspace-sdk:1.0
```
 
 ### Example Usage
 
 ```php
 $client = new \wwwroth\contactspace\Client([
     'api_key' => '<APIKEY>'
 ]);
 
 // 2.1 Add a Dataset
 $client->createDataSet([
     'initiativeid' => 5914,
     'datasetname' => 'testdata',
     'active' => 1
 ]);
 
 // 2.7 Get Users
 $client->getUsers();
 ```
