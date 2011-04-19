<?php

namespace Fastbill\Invoice;

class Finder extends \Fastbill\Base\Finder
{
  public function findOneById($id, $con = null)
  {
    return self::findOneHelper(
      array(
        'SERVICE' => 'invoice.get',
        'DATA'    => array( 'INVOICE_ID' => $id )
      ),
      'INVOICES',
      '\Fastbill\Customer\Invoice',
      $con
    );
  }
}