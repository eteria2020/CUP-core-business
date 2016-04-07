<?php

namespace BusinessCore\Form\InputData;

use BusinessCore\Service\BusinessService;

class BusinessDataFactory
{
    /**
     * @var BusinessService
     */
    private $businessService;

    /**
     * BusinessDataFactory constructor.
     * @param BusinessService $businessService
     */
    public function __construct(BusinessService $businessService)
    {
        $this->businessService = $businessService;
    }

    /**
     * creates a new BusinessData object from an array
     *
     * @param array $data
     * @return AddBusinessData
     */
    public function fromArray(array $data)
    {
        $code = $this->businessService->getUniqueCode();

        return new BusinessData($code, $data);

    }
}
