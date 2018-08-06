# API EUMM (Express Union Mobile Money)
## Installation
###  API 2.1 
composer require avssarl/apieumm:"2.1.*"
### API 2.0
composer require avssarl/apieumm:"2.0.*"

## Services

---
    - [x] GetAccountBalance
    - [x] GetAccontDetails
    - [x] getCommissionBalance
    - [x] cashIn
    - [x] sendMoney
    - [x] getTransactionDetails
    - [x] getReferenceIdDetails
    - [x] sendPaymentRequest
    - [x] sendPaymentRequest
    - [x] getPaymentStatus
    - [] generateKey
    
## Getting Started

include './vendor/autoload.php';

$eum = new \Eumm\Eumm($id, $pwd, $key, $ip);
print $eum->getAccountBalance()->getBalance();

