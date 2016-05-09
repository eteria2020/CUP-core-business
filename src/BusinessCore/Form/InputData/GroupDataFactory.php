<?php

namespace BusinessCore\Form\InputData;

use BusinessCore\Entity\Business;
use BusinessCore\Entity\Group;
use BusinessCore\Exception\InvalidGroupFormException;

class GroupDataFactory
{
    public static function groupfromArray(Business $business, $data)
    {
        $name = $data['name'];
        $description = $data['description'];

        if (empty($name)) {
            throw new InvalidGroupFormException("Il nome gruppo non può essere vuoto");
        }

        return new Group($business, $name, $description);
    }
}
