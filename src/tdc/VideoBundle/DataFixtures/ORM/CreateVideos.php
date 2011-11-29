<?php

namespace tdc\MembersBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use tdc\VideoBundle\Entity\Video;
use tdc\VideoBundle\Entity\Category;
use tdc\VideoBundle\Entity\Rating;
use tdc\UserBundle\Entity\User;

class LoadVideoData implements FixtureInterface
{
    public function load($manager)
    {
        // Create video categories
        $cat1 = new Category();
        $cat1->setName("Programming");
        $cat1->setDescription("Programming Courses");
        $manager->persist($cat1);

        $cat2 = new Category();
        $cat2->setName("Python");
        $cat2->setDescription("Programming Python");
        $cat2->setParent($cat1);
        $manager->persist($cat2);
        
        $cat3 = new Category();
        $cat3->setName("RenderMan Shaders");
        $cat3->setDescription("Programming RenderMan Shaders");
        $cat3->setParent($cat1);
        $manager->persist($cat3);

        // Create videos
        for ($i = 0; $i < 35; $i++) {
            $video1 = new Video();
            $video1->setName('Video '.$i);
            $video1->setAuthor('Rudy Cortes');
            $video1->setSummary('Summary '.$i);
            $video1->setDescription('Description '.$i);
            $video1->setToc('TOC '.$i);
            $video1->setStatus(1);
            $video1->setIcon("icon.png");
            $video1->setViews(rand(100,1000));
            $video1->addCategory($cat1);

            if (($i > 0) && ($i % 3 == 0)) {
                $video1->addCategory($cat2);
            }
            if (($i > 0) && ($i % 5 == 0)) {
                $video1->addCategory($cat3);
            }

            $manager->persist($video1);
        }

        // Write to the db
        $manager->flush();
    }
}
