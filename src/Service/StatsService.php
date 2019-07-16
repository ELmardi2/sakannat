<?php
namespace App\Service;

use Doctrine\Common\Persistence\ObjectManager;


class StatsService 
{
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

     public function getStats()
    {
        $users    = $this->getUsersCount();
        $ads      = $this->getAdsCount();
        $bookings = $this->getBookingsCount();
        $comments = $this->getCommentsCount();

        return compact('users', 'ads', 'bookings', 'comments');
    }
    //get numbers of users
    public function getUsersCount()
    {
        return $this->manager->createQuery('SELECT count(u) FROM App\Entity\User u')->getSingleScalarResult();
    }
    //get numbers of announce
    public function getAdsCount()
    {
        return $this->manager->createQuery('SELECT count(a) FROM App\Entity\Ad a')->getSingleScalarResult();
    }
    //get numbers of reservations
    public function getBookingsCount()
    {
        return $this->manager->createQuery('SELECT count(b) FROM App\Entity\Booking b')->getSingleScalarResult();
    }
    //get numbers of comments
    public function getCommentsCount()
    {
        return $this->manager->createQuery('SELECT count(c) FROM App\Entity\Comment c')->getSingleScalarResult();
    }
    public function getAdsStats($direction)
    {
        return $this->manager->createQuery(
            'SELECT AVG(c.rating) as note, a.title, a.id, u.firstName, u.lastName, u.picture 
            FROM App\Entity\Comment c
            JOIN c.ad a
            JOIN a.author u
            GROUP BY a
            ORDER BY note ' . $direction
        )->setMaxResults(5)->getResult();
    }

}
