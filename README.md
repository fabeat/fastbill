# Fastbill

A PHP library for the Fastbill API (http://www.fastbill.com)

## Usage

### Initialization

Simply include the autoloader & set up a connection

    require_once 'lib/autoload.php';
    \Fastbill\Connection\Wrapper::init('YOUR_EMAIL', 'YOUR_API_KEY');

### Working with Customers

#### Create a new customer

    $customer = new \Fastbill\Customer\Customer();
    $customer['CUSTOMER_NUMBER']  = 1;
    $customer['CUSTOMER_TYPE']    = 'business';
    $customer['ORGANIZATION']     = 'Test Gbr';
    $customer['SALUATION']        = 'mr';
    $customer['FIRST_NAME']       = 'Max';
    $customer['LAST_NAME']        = 'Mustemann';
    $customer['ADDRESS']          = 'Musterstraße 1';
    $customer['ZIPCODE']          = '80808';
    $customer['CITY']             = 'München';
    $customer['PAYMENT_TYPE']     = 1;
    # the library changes the country code internally to the needed country id
    $customer['COUNTRY_CODE']     = 'DE';
    $customer->save();

#### Get an existing customer

    $customer = \Fastbill\Customer\Finder::findOneById(123456);

#### Delete an existing customer

    $customer = \Fastbill\Customer\Finder::findOneById(123456);
    $customer->delete();
