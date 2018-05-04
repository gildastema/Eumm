**API EUMM**

This library is easy way to use EUMM API.

    Installation
    
    composer require avssarl/apieumm
    
     include './vendor/autoload.php';
       $eum = new \Eumm\Eumm("xxxxx", "xxxxx", "xxxxxx");
       print $eum->getAccountBalance();
       var_dump($eum->getAccountDetails("237xxxxxxxxx")->getPlan());
      
  