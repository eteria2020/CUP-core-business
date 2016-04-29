<?php
namespace BusinessCore\View\Helper;

use BusinessCore\Entity\BusinessEmployee;
use Zend\Mvc\I18n\Translator;
use Zend\View\Helper\AbstractHelper;

class BusinessEmployeeStatusHelper extends AbstractHelper
{
    /**
     * @var Translator
     */
    private $translator;

    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    public function __invoke($status)
    {
        switch ($status) {
            case BusinessEmployee::STATUS_PENDING:
                return $this->translator->translate("In attesa di approvazione");
            case BusinessEmployee::STATUS_BLOCKED:
                return $this->translator->translate("Bloccato");
            case BusinessEmployee::STATUS_APPROVED:
                return $this->translator->translate("Approvato");
            case BusinessEmployee::STATUS_DELETED:
                return $this->translator->translate("Disattivato");
            default:
                return $status;
        }
    }
}
