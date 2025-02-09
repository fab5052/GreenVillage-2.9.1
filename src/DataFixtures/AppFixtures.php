<?php

namespace App\DataFixtures;

use App\Entity\Rubric;
use App\Entity\Product;
use App\Entity\InfoSuppliers;
// use App\Entity\OrdersDetails;
// use App\Entity\Order;
use App\Entity\User;
use App\Entity\Image;
use App\Entity\Tva;
use DateTime;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
// use Symfony\Component\Slugger;
// use App\Entity\Trait\SlugTrait;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    private SluggerInterface $slugger;

    private UserPasswordHasherInterface $passwordEncoder;


    public function __construct(UserPasswordHasherInterface $passwordEncoder, SluggerInterface $slugger)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->slugger = $slugger;
    }

public function load(ObjectManager $manager): void
{
    
    // $admin = new User();
    // $admin->setEmail('admin@greenvillage.net')
    //       ->setFirstname('Fabrice')
    //       ->setLastname('Beaujois')
    //       ->setIsVerified(true)
    //       ->setAddress('12 rue du port')
    //       ->setZipcode('80850')
    //       ->setCity('Berto')
    //       ->setPassword($this->passwordEncoder->hashPassword($admin, 'fb975052'))
    //       ->setRoles(['ROLE_ADMIN']);

    // $manager->persist($admin);
    
$faker = \Faker\Factory::create('fr_FR');

    // Utilisateurs

    $users = [];
    for ($i = 0; $i < 5; $i++) {
        $user = new User();
        $user->setCreatedAt(new DateTimeImmutable())
             ->setEmail($faker->email)
             ->setLastname($faker->lastName)
             ->setFirstname($faker->firstName)
             ->setRoles(['ROLE_USER'])
             ->setIsVerified(1)
             ->setAddress($faker->address)
             ->setZipcode($faker->postcode)
             ->setCity($faker->city)
             ->setPassword($this->passwordEncoder->hashPassword($user, 'secret'));
        $manager->persist($user);
        $users[] = $user;
    }
    $manager->flush(); 

    
// Rubriques principales
$rubrics = []; 
$rubricLabels = ['vent', 'percus', 'cordes', 'electro'];

foreach ($rubricLabels as $label) {
    $rubric = new Rubric();
    $rubric->setCreatedAt(new DateTimeImmutable())
           ->setLabel($label)
           ->setSlug($this->slugger->slug($label));

    $manager->persist($rubric);
    $rubrics[] = $rubric; 
}

$manager->flush(); 

// Sous-rubriques
$subrubrics = []; 
$subrubricsLabels = ['saxo', 'trompette', 'batterie', 'tamtam', 'guitare', 'piano', 'synthétiseur', 'amplificateur'];

foreach ($subrubricsLabels as $label) {
    $subrubric = new Rubric();
    $subrubric->setCreatedAt(new DateTimeImmutable())
              ->setLabel($label)
              ->setSlug($this->slugger->slug($label))
              ->setParent($faker->randomElement($rubrics)) 
              ->setDescription($faker->paragraph());

    $manager->persist($subrubric);
    $subrubrics[] = $subrubric; 
}

$manager->flush(); 

// Fournisseurs
$infoSuppliersType = ['constructeur', 'importateur'];
$infoSuppliers = [];

foreach ($infoSuppliersType as $type) {
    $infoSupplier = new InfoSuppliers();
    $infoSupplier->setType($type)
                 ->setStatus('Active')
                 ->setReference("infoSuppliers:" . mt_rand(1000, 9999))
                 ->setUser($faker->randomElement($users));

    $manager->persist($infoSupplier);
    $infoSuppliers[] = $infoSupplier;
}

$manager->flush(); 

//Produits
$product = [] ;

for ($i = 0; $i < 50; $i++) {
    $tva = new Tva();
    $tva->setRate('18.60');
    $manager->persist($tva);

    $product = new Product();
    $product->setLabel($faker->sentence)
            ->setSlug($this->slugger->slug($faker->sentence)->lower() . '-' . uniqid())
            ->setStock(mt_rand(1, 100))
            ->setPrice(mt_rand(1, 100))
            ->setReference("RefProd:" . mt_rand(10000, 99999))
            ->setDescription($faker->paragraph)
            ->setWeight($faker->randomFloat(2, 0, 100))
            ->setInfoSuppliers($faker->randomElement($infoSuppliers))
            ->setRubric($faker->randomElement($subrubrics)) 
            ->setTva($tva)
            ->setCreatedAt(new DateTimeImmutable())
            ->setUpdatedAt(new DateTimeImmutable());

    $manager->persist($product);
   // $product[] = $product; 
}

$manager->flush();

    // Images
    for ($i = 0; $i < 50; $i++) {
        $image = new Image();
        $image->setImage($faker->imageUrl);
        $image->setProduct($faker->randomElement($manager->getRepository(Product::class)->findAll()));
        $manager->persist($image);
    }
    $manager->flush();
}
}




  // Services

    ## Creation de services suivant les utilisateurs

    // $servicetype = ['apres-vente', 'commercial', 'equipe'];

    // for ($i = 0; $i < 10; $i++) {
    //     try {
    //         $service = new Service();
    //         $service->setType($servicetype[mt_rand(0, 2)]);
    //         $manager->persist($service);
    //     } catch (\Exception $e) {
    //         throw new \RuntimeException('Erreur lors de la création d\'un service', 0, $e);
    //     }
    // }

    // try {
    //     $manager->flush();
    // } catch (\Exception $e) {
    //     throw new \RuntimeException('Erreur lors de la sauvegarde des services', 0, $e);
    // }
