<?php

namespace App\Ticsol\Components\Contact\Repository;

use App\Ticsol\Base\Repository\Repository;
use App\Ticsol\Components\Models\Contact;

class ContactRepository extends Repository{
    
    public function model()
    {
        return 'App\Ticsol\Components\Models\Contact';
    }
}