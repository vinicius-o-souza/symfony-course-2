<?php

namespace App\Tests\Utils;

use App\Twig\AppExtension;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CategoryTest extends KernelTestCase
{
    private $mockedCategoryTreeFrontPage;
    
    protected function setUp()
    {
        $kernel = self::bootKernel();
        $urlGenerator = $kernel->getContainer()->get('router');
        $this->mockedCategoryTreeFrontPage = $this->getMockBuilder('App\Utils\CategoryTreeFrontPage')
            ->disableOriginalConstructor()
            ->setMethods()->getMock();
            
        $this->mockedCategoryTreeFrontPage->urlGenerator = $urlGenerator;
    }
    
    /**
     * @dataProvider dataForCategoryTreeFrontPage
     */
    public function testCategoryTreeFrontPage($string, $array, $id)
    {
        $this->mockedCategoryTreeFrontPage->categoriesArrayFromDb = $array;
        $this->mockedCategoryTreeFrontPage->slugger = new AppExtension;
        $mainParent = $this->mockedCategoryTreeFrontPage->getMainParent($id)['id'];
        $array = $this->mockedCategoryTreeFrontPage->buildTree($mainParent);
        $this->assertSame($string, $this->mockedCategoryTreeFrontPage->getCategoryList($array));
    }

    public function dataForCategoryTreeFrontPage()
    {
        yield [
            '<ul class="fa-ul text-left"><li><i class="fa-li fa fa-arrow-right"></i> qweqwe<a href="/admin/edit-category/22"> Edit</a><a onclick="return confirm(\'Are you sure?\');" href="/admin/delete-category/22"> Delete</a></li><li><i class="fa-li fa fa-arrow-right"></i> qweqweqwe<a href="/admin/edit-category/23"> Edit</a><a onclick="return confirm(\'Are you sure?\');" href="/admin/delete-category/23"> Delete</a></li></ul>',
            [
                [
                    "id" => "21",
                    "parent_id" => "21",
                    "name" => "Teste"
                ],
                [
                    "id" => "22",
                    "parent_id" => null,
                    "name" => "qweqwe"
                ],
                [
                    "id" => "23",
                    "parent_id" => null,
                    "name" => "qweqweqwe"
                ],
                [
                    "id" => "24",
                    "parent_id" => "21",
                    "name" => "sdafsadf"
                ]
            ],
            1
        ];
    }
}
