<?php

namespace App\Repository;

use App\Entity\Wish;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Wish|null find($id, $lockMode = null, $lockVersion = null)
 * @method Wish|null findOneBy(array $criteria, array $orderBy = null)
 * @method Wish[]    findAll()
 * @method Wish[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WishRepository extends ServiceEntityRepository
{
    private $wish;

    public function __construct(ManagerRegistry $registry,EntityManagerInterface $wish)
    {
        parent::__construct($registry, Wish::class);
        $this->wish = $wish;
    }

    public function saveWish($title, $price, $created, $status = 0)
    {
        $newWish = new Wish();

        $newWish
            ->setTitle($title)
            ->setPrice($price)
            ->setCreated(new \DateTime())
            ->setStatus($status);

        $this->wish->persist($newWish);
        $this->wish->flush();
    }

    public function updateWish(Wish $wish): Wish
    {
        $this->wish->persist($wish);
        $this->wish->flush();

        return $wish;
    }

    public function removeWish(Wish $wish)
    {
        $this->wish->remove($wish);
        $this->wish->flush();
    }
}
