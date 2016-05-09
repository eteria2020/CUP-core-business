<?php
namespace BusinessCore\View\Helper;

use BusinessCore\Entity\BusinessEmployee;
use Zend\View\Helper\AbstractHelper;

class GroupLinkHelper extends AbstractHelper
{
    public function __invoke(BusinessEmployee $businessEmployee)
    {
        if (is_null($businessEmployee->getGroup())) {
            return '-';
        } else {
            return '<a href="'.
            $this->getView()->url(
                'groups/details',
                ['id' => $businessEmployee->getGroup()->getId()]
            )
            . '">' . $businessEmployee->getGroup()->getName() . '</a>';
        }
    }
}
