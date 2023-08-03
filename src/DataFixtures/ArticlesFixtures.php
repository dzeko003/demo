<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;




class ArticlesFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        
        $faker = \Faker\Factory::create('fr_FR');

        // Creer 3 categories fakees
        for($i=1 ; $i<=3; $i++){
            $category = new Category();
            $category->setTitle($faker->sentence())
                     ->setDescription($faker->paragraph());

            $manager->persist($category);

            // creer entre 4 et 6 articles
            $content = '<p>' . implode('</p><p>', $faker->paragraphs(5)) . '</p>';

            for($j=1 ; $j <= mt_rand(4,6) ; $j++){
                $article = new Article();
                $article -> setTitle($faker->sentence())
                         -> setContent($content)
                         ->setImage($faker->imageUrl())
                         ->setCreatedAt($faker->dateTimeBetween('-6 months'))
                         ->setCategory($category);
    
                $manager->persist($article);

            // On donne des commentaires a l'article
                for($k=1 ; $k <=mt_rand(4,10); $k++){
                    $comment = new Comment();
                    $content = '<p>' . implode('</p><p>', $faker->paragraphs(2)) . '</p>';

                    $now = new DateTime();
                    $interval = $now->diff($article->getCreatedAt())->days;
                    $days = $interval;
                    $minimum = '-'.$days.'days'; // -100 days

                    $comment->setAuthor($faker->name())
                            ->setContent($content)
                            ->setCreatedAt($faker->dateTimeBetween($minimum))
                            ->setArticle($article);

                    $manager->persist($comment);
                }
            }
        }


        $manager->flush();
    }
}
