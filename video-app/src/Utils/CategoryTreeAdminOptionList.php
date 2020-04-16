<?php

namespace App\Utils;

use App\Utils\AbstractClass\CategoryTreeAbstract;

class CategoryTreeAdminOptionList extends CategoryTreeAbstract
{
    public function getCategoryList(array $categories, int $repeat = 0)
    {
        foreach ($categories as $value) {
            $this->categoryList[] = [
                'name' => str_repeat("-", $repeat) . $value['name'], 
                'id' => $value['id']
            ];
            
            if (!empty($value['children'])) {
                $repeat += 2;
                $this->getCategoryList($value['children'], $repeat);
                $repeat -= 2;
            }
        }
        return $this->categoryList;
    }
}