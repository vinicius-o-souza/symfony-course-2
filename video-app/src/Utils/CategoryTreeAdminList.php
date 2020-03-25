<?php

namespace App\Utils;

use App\Utils\AbstractClass\CategoryTreeAbstract;

class CategoryTreeAdminList extends CategoryTreeAbstract
{

    public $html_1 = '<ul class="fa-ul text-left">';
    public $html_2 = '<li><i class="fa-li fa fa-arrow-right"></i> ';
    public $html_3 = '<a href="';
    public $html_4 = '">';
    public $html_5 = '</a><a onclick="return confirm(\'Are you sure?\');" href="';
    public $html_6 = '">';
    public $html_7 = '</a>';
    public $html_8 = '</li>';
    public $html_9 = '</ul>';
    
    public function getCategoryList(array $categoriesArray)
    {
        $this->categoryList .=  $this->html_1;
        foreach ($categoriesArray as $value) {
            $urlEdit = $this->urlGenerator->generate('edit_category', ['id' => $value['id']]);
            $urlDelete = $this->urlGenerator->generate('delete_category', ['id' => $value['id']]);
            
            $this->categoryList .= $this->html_2 . $value['name'] . $this->html_3 . 
                $urlEdit . $this->html_4 . ' Edit' . $this->html_5 . $urlDelete . 
                $this->html_6 . ' Delete'  . $this->html_7;
                
            if (!empty($value['children'])) {
                $this->getCategoryList($value['children']);
            }
            
            $this->categoryList .= $this->html_8;
        }
        $this->categoryList .=  $this->html_9;
        return $this->categoryList;
    }
    
    public function getMainParent(int $id) : array
    {
        $key = array_search($id, array_column($this->categoriesArrayFromDb, 'id'));
        if ($this->categoriesArrayFromDb[$key]['parent_id'] != null) {
            return $this->getMainParent($this->categoriesArrayFromDb[$key]['parent_id']);
        }
        return [
            'id' => $this->categoriesArrayFromDb[$key]['id'],
            'name' => $this->categoriesArrayFromDb[$key]['name'],
        ];
    }
    
    public function getCategoryListAndParent(int $id): string
    {
        $this->slugger = new AppExtension(); // twig extension for slugify url's for categories
        $parentData = $this->getMainParent($id); // main parent of subcategory
        $this->mainParentName = $parentData['name']; // for acessing in view
        $this->mainParentId = $parentData['id']; // for acessing in view
        $key = array_search($id, array_column($this->categoriesArrayFromDb, 'id'));
        $this->currentCategoryName = $this->categoriesArrayFromDb[$key]['name'];
        
        $categories_array = $this->buildTree($parentData['id']);
        return $this->getCategoryList($categories_array);
    }
}