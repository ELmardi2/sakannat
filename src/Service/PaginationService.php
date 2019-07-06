<?php

namespace App\Service;

use Twig\Environment;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\RequestStack;



class PaginationService
{
    private $entityClass;
    private $limit = 8;
    private $currentPage = 1;
    private $manager;
    private $route;
    private $templatePath;

    public function __construct(ObjectManager $manager, Environment $twig, RequestStack  $request,
    $templatePath)
    {
        //get the current route by using requeststack
        $this->route = $request->getCurrentRequest()->attributes->get('_route');

        $this->manager         = $manager;
        $this->twig            = $twig;
        $this->templatePath = $templatePath;
    }

    public function setTemplatePath($templatePath)
    {
        $this->templatePath = $templatePath;

        return $this;
    }

    public function getTemplatePath()
    {
        return $this->templatePath;
    }

    public function setRoute($route){
        $this->route = $route;

        return $route;
    }

    public function getRoute()
    {
        return $route;
    }

    public function display()
    {
        $this->twig->display($this->templatePath, [
            'page' => $this->currentPage,
            'pages'=> $this->getPages(),
            'route' => $this->route
        ]);
    }

    public function getPages()
    {
        $repo = $this->manager->getRepository($this->entityClass);
        $total = count($repo->findAll());

        //get the average of pages to show
        $pages = $total / $this->limit;

        //return numbers of pages here
        return $pages;
    }

    public function getData()
    {

        if (empty($this->entityClass)) {

            throw new \Exception("You did not precise entityclass here in which one we paginate ! check it it and try again");
            
        }
        //calculate the number of elements by pages
        $offset = $this->currentPage * $this->limit - $this->limit;

        //ask doctrine to presente the class
        $repo = $this->manager->getRepository($this->entityClass);
        $data = $repo->findBy([], [], $this->limit, $offset);

        //return data neccessary
        return $data;

    }


    public function setPage($page){
        $this->currentPage = $page;

        return $this;
    }

    public function getPage(){
        return $this->currentPage;
    }
    public function setLimit($limit)
    {
        $this->limit = $limit;

        return $this;
    }

    public function getLimit(){
        return $this->limit;
    }

    public function setEntityClass($entityClass){
        $this->entityClass = $entityClass;

        return $this;
    }
    public function getEntityClass(){
        return $this->entityClass;
    }
}