<?php

namespace App\DataFixtures;

use App\Entity\Article;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;


class ArticlesFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        for($i=1 ; $i <= 10 ;$i++){
            $article = new Article();
            $article -> setTitle("Titre de l'article numero".$i)
                     -> setContent("<p> Contenu de l'article numero".$i."</p>")
                     ->setImage("http://placehold.it/350x150")
                     ->setCreatedAt(new DateTime());

            $manager->persist($article);
        }

        $manager->flush();
    }
}
