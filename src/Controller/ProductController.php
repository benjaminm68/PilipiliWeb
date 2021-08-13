<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use Cocur\Slugify\Slugify;
use DateTimeZone;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ProductController extends AbstractController
{
    /**
     * @Route("/produits", name="all_product")
     */
    public function index(): Response
    {

        $products = $this->getDoctrine()->getRepository(Product::class)->findAll();

        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }

    /**
     * @Route("/product/add", name="product_add")
     */
    public function add(Product $product = null, Request $request, EntityManagerInterface $em): Response
    {

        if (!$product) {
            $product = new Product();
        }

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // On ajoute le slug au produit
            $slugify = new Slugify();
            $product->setSlug($slugify->slugify($product->getName()));

            // On ajoute la date de création
            $product->setCreatedAt(new \DateTime('now',new DateTimeZone('Europe/Paris')));

            $em->persist($product);
            $em->flush();

            // Affiche un message de succès
            $this->addFlash(
                'success',
                'Nouveau produit ajouté'
            );

            return $this->redirectToRoute('admin_product_list');
        }

        return $this->render('product/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/product/edit/{id}", name="product_edit")
     */
    public function edit(Product $product, Request $request, EntityManagerInterface $em): Response
    {

        if(!$product){
            $this->redirectToRoute('admin_product_list');
        }

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // On ajoute le slug au produit
            $slugify = new Slugify();
            $product->setSlug($slugify->slugify($product->getName()));

            // On ajouté la date de modification
            $product->setUpdatedAt(new \DateTime('now',new DateTimeZone('Europe/Paris')));
            

            $em->persist($product);
            $em->flush();


            // Affiche un message lors de la création
            $this->addFlash(
                'success-edit',
                'Produit modifié'
            );

            return $this->redirectToRoute('admin_product_list');
        }

        return $this->render('product/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/product/supprimer/{id}", name="product_delete")
     */
    public function delete(Product $product, EntityManagerInterface $em): Response
    {

    
        if ($product) {
            $em->remove($product);
            $em->flush();

            
            $this->addFlash(
                'success-delete',
                'L\'argonaute a bien été supprimé !'
            );
        } else {
            $this->addFlash(
                'fail-delete',
                'L\'argonaute n\'existe pas !'
            );
        }
        
        return $this->redirectToRoute('admin_product_list');
    }


    // BACKOFFICE

    /**
     * @Route("/admin", name="admin_index")
     */
    public function adminIndex(): Response
    {

        return $this->render('admin/index.html.twig');
    }

    /**
     * @Route("/admin/product", name="admin_product_list")
     */
    public function adminProduct(): Response
    {

        $products = $this->getDoctrine()->getRepository(Product::class)->findAll();

        return $this->render('admin/product-list.html.twig', [
            'products' => $products
        ]);
    }
}
