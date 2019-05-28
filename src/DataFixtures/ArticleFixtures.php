<?php


namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;

class ArticleFixtures extends Fixture implements DependentFixtureInterface

{

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create();
        for ($i = 0; $i <= 50; $i++) {
            switch ($i){
                case $i<=10:
                    $article = new Article();
                    $article->setTitle(mb_strtolower($faker->words($nb = 3, $asText = true)));
                    $article->setContent($faker->sentence($nbWords = 50, $variableNbWords = true));
                    $manager->persist($article);
                    $article->setCategory($this->getReference('categorie_0'));
                    break;
                case $i<=20:
                    $article = new Article();
                    $article->setTitle(mb_strtolower($faker->words($nb = 3, $asText = true)));
                    $article->setContent($faker->sentence($nbWords = 50, $variableNbWords = true));
                    $manager->persist($article);
                    $article->setCategory($this->getReference('categorie_1'));
                    break;
                case $i<=30:
                    $article = new Article();
                    $article->setTitle(mb_strtolower($faker->words($nb = 3, $asText = true)));
                    $article->setContent($faker->sentence($nbWords = 50, $variableNbWords = true));
                    $manager->persist($article);
                    $article->setCategory($this->getReference('categorie_2'));
                    break;
                case $i<=40:
                    $article = new Article();
                    $article->setTitle(mb_strtolower($faker->words($nb = 3, $asText = true)));
                    $article->setContent($faker->sentence($nbWords = 50, $variableNbWords = true));
                    $manager->persist($article);
                    $article->setCategory($this->getReference('categorie_3'));
                    break;
                case $i<=50:
                    $article = new Article();
                    $article->setTitle(mb_strtolower($faker->words($nb = 3, $asText = true)));
                    $article->setContent($faker->sentence($nbWords = 50, $variableNbWords = true));
                    $manager->persist($article);
                    $article->setCategory($this->getReference('categorie_4'));
                    break;
            }

        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [CategoryFixtures::class];
    }
}