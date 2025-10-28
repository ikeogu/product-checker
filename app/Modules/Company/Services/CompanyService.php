<?php

namespace App\Modules\Company\Services;


class CompanyService {

    public function createSubsidiary($parentCompany, array $data) {
        return $parentCompany->subsidiaries()->create($data);
    }

    
}
