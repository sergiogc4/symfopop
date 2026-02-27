<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ProductController extends AbstractController
{
    #[Route('/product', name: 'app_product_index')]
    public function index(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findBy([], ['createdAt' => 'DESC']);

        return $this->render('product/index.html.twig', [
            'products' => $products,
            'title' => 'Tots els productes',
            'show_actions' => false,
            'show_new_button' => false,
            'empty_message' => 'No hi ha productes disponibles.',
        ]);
    }

    #[Route('/product/new', name: 'app_product_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$product->getImage()) {
                $product->setImage('https://picsum.photos/seed/' . uniqid() . '/400/300');
            }
            $product->setOwner($this->getUser());
            $product->setCreatedAt(new \DateTime());

            $em->persist($product);
            $em->flush();

            $this->addFlash('success', 'Producte creat correctament!');
            return $this->redirectToRoute('app_product_show', ['id' => $product->getId()]);
        }

        return $this->render('product/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/product/my/products', name: 'app_my_products')]
    #[IsGranted('ROLE_USER')]
    public function myProducts(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findBy(
            ['owner' => $this->getUser()],
            ['createdAt' => 'DESC']
        );

        return $this->render('product/index.html.twig', [
            'products' => $products,
            'title' => 'Els meus productes',
            'show_actions' => true,
            'show_new_button' => true,
            'empty_message' => 'Encara no has publicat cap producte.',
        ]);
    }

    #[Route('/product/{id}', name: 'app_product_show', methods: ['GET'])]
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/product/{id}/edit', name: 'app_product_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function edit(Request $request, Product $product, EntityManagerInterface $em): Response
    {
        if ($product->getOwner() !== $this->getUser()) {
            throw $this->createAccessDeniedException('No pots editar aquest producte.');
        }

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Producte actualitzat correctament!');
            return $this->redirectToRoute('app_product_show', ['id' => $product->getId()]);
        }

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/product/{id}', name: 'app_product_delete', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function delete(Request $request, Product $product, EntityManagerInterface $em): Response
    {
        if ($product->getOwner() !== $this->getUser()) {
            throw $this->createAccessDeniedException('No pots esborrar aquest producte.');
        }

        if ($this->isCsrfTokenValid('delete' . $product->getId(), $request->request->get('_token'))) {
            $em->remove($product);
            $em->flush();
            $this->addFlash('success', 'Producte esborrat correctament!');
        }

        return $this->redirectToRoute('app_product_index');
    }
}
