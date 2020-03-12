<?php

namespace App\DataFixtures;

use App\Entity\Author;
use App\Entity\File;
use App\Entity\Music;
use App\Entity\Pdf;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class InheritanceEntitiesFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 2; $i++) {
            $author = new Author();
            $author->setName('Author name - '. $i);
            $manager->persist($author);
            for ($j = 1; $j <= 3; $j++) {
                $pdf = new Pdf();
                $pdf->setFilename('pdf name of - '. $j)  ;
                $pdf->setDescription('pdf description of - '. $j);
                $pdf->setSize(5454);
                $pdf->setPagesNumber(123);
                $pdf->setOrientation('portrait');
                $pdf->setAuthor($author);
                $manager->persist($pdf);
            }
            
            for ($j = 1; $j <= 3; $j++) {
                $music = new Music();
                $music->setFilename('music name of - '. $j)  ;
                $music->setDescription('music description of - '. $j);
                $music->setSize(5454);
                $music->setDuration(123);
                $music->setFormat('mpeg-2');
                $music->setAuthor($author);
                $manager->persist($music);
            }
        }

        $manager->flush();
    }
}
