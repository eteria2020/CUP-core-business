<?php
namespace BusinessCore\View\Helper;

use BusinessCore\Entity\BusinessEmployee;
use Zend\View\Helper\AbstractHelper;

class BusinessEmployeeAvailableActionButtonHelper extends AbstractHelper
{
    public function __invoke(BusinessEmployee $businessEmployee)
    {
        $html = '';
        if ($businessEmployee->isBlocked()) {
            $html .= '<a href="';
            $html .= $this->getView()->url('employees/employee/unblock', ['id' => $businessEmployee->getEmployee()->getId()]);
            $html .= '"onclick="return confirm(\'';
            $html .= $this->getView()->translate("Sei sicuro di voler sbloccare questo utente?");
            $html .= '\')" class="btn-margin validation-btn btn btn-primary btn-xs">';
            $html .= $this->getView()->translate("Sblocca");
            $html .= '</a>';
        } else if ($businessEmployee->isApproved()) {
            $html .= '<a href="';
            $html .= $this->getView()->url('employees/employee/block', ['id' => $businessEmployee->getEmployee()->getId()]);
            $html .= '"onclick="return confirm(\'';
            $html .= $this->getView()->translate("Sei sicuro di voler bloccare questo utente?");
            $html .= '\')" class="btn-margin validation-btn btn btn-warning btn-xs">';
            $html .= $this->getView()->translate("Blocca");
            $html .= '</a>';
        } else {
            $html .= '<a href="';
            $html .= $this->getView()->url('employees/employee/approve', ['id' => $businessEmployee->getEmployee()->getId()]);
            $html .= '"onclick="return confirm(\'';
            $html .= $this->getView()->translate("Sei sicuro di voler approvare questo utente?");
            $html .= '\')" class="btn-margin validation-btn btn btn-success btn-xs">';
            $html .= $this->getView()->translate("Approva");
            $html .= '</a>';
        }

        $html .= '<a href="';
        $html .= $this->getView()->url('employees/employee/remove', ['id' => $businessEmployee->getEmployee()->getId()]);
        $html .= '"onclick="return confirm(\'';
        $html .= $this->getView()->translate("Sei sicuro di voler eliminare questo utente?");
        $html .= '\')" class="validation-btn btn btn-danger btn-xs">';
        $html .= $this->getView()->translate("Elimina");
        $html .= '</a>';
        return $html;
    }
}
