<?php

namespace App\Controller;

use App\Entity\Products;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/products", name="products_")
 */
class ProductsController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index()
    {

        $products = $this->getDoctrine()->getRepository(Products::class)->findAll();

        $arrayCollection = [];

        foreach($products as $item) {
            $arrayCollection[] = array(
                'id' => $item->getId(),
                'sku' => $item->getSku(),
                'name' => $item->getName(),
                'price' => $item->getPrice(),
                'created_at' => $item->getCreatedAt()->format('Y-m-d H:i:s'),
                'updated_at' => $item->getUpdatedAt()->format('Y-m-d H:i:s')
            );
        }

        return $this->json(
            $arrayCollection
        );
    }

    /**
     * @Route("/", name="create", methods={"POST"})
     */
    public function create(Request $request)
    {
        $data = $request->request->all();

        // Realiza as validações dos campos
        $resultValidator = $this->inputsValidator($data);

        if($resultValidator !== true) {
            return $this->json([
                'data' => $resultValidator
            ]);
        }

        $products = (new Products())
            ->setSku($data['sku'])
            ->setName($data['name'])
            ->setPrice($data['price'])
            ->setCreatedAt(new \DateTime('now', new \DateTimeZone('America/Buenos_Aires')))
            ->setUpdatedAt(new \DateTime('now', new \DateTimeZone('America/Buenos_Aires')));

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($products);
        $manager->flush();

        return $this->json([
            'data' => 'Product created sucessfully!'
        ]);
    }

    private function inputsValidator($data) {

        $products = $this->getDoctrine()->getRepository(Products::class)->findAll();

        if ($data['sku'] == '' || $data['name'] == '' || $data['price'] == '') {
            return 'Todos os campos são obrigatórios!';
        }

        if (!is_numeric($data['price']) || $data['price'] < 1) {
            return 'Preço deve ser monetário e maior que zero!';
        }

        foreach ($products as $item) {
            if ($data['sku'] == $item->getSku()) {
                return 'SKU já existe!';
            }

            if ($data['name'] == $item->getName()) {
                return 'Nome já existe!';
            }
        }

        return true;
    }

}
