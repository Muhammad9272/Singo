<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model{

   protected $table = "tbl_subscription";

   const SUBSCRIPTION_STATUS_NONE = 0;
   const SUBSCRIPTION_STATUS_ACTIVE = 1;
   const SUBSCRIPTION_STATUS_CANCELED = 2;

}
