<?php

namespace App\Controller;

use App\Entity\Customers;
use App\Entity\Orders;
use App\Entity\OrderItems;
use App\Entity\Products;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/orders", name="orders_")
 */
class OrdersController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index()
    {
        $order = $this->getDoctrine()->getRepository(Orders::class)->findAll();

        $arrayCollection = [];
        $arrayItems = [];
        $arrayCustomer = [];

        foreach($order as $item) {

            $customers = $this->getDoctrine()->getRepository(Customers::class)->find($item->getCustomer());

            if ($customers) {
                $arrayCustomer = array(
                    'id' => $customers->getId(),
                    'name' => $customers->getName(),
                    'cpf' => $customers->getCpf(),
                    'email' => $customers->getEmail()
                );
            }

            $orderItems = $this->getDoctrine()->getRepository(OrderItems::class)->findBy(['orders' => $item->getId()]);

            if ($orderItems) {
                foreach ($orderItems as $order)
                $arrayItems[] = array(
                    'product' => array(
                        'id' => $order->getProduct()->getId(),
                        'sku' => $order->getProduct()->getSku(),
                        'title' => $order->getProduct()->getName()
                    ),
                    'amount' => $order->getAmount(),
                    'price_unit' => $order->getPriceUnit(),
                    'total' => $order->getTotal(),
                );
            }

            $dataHoraCancelamento = '';

            if($item->getStatus() == 'CANCELED') {
                $dataHoraCancelamento = (new \DateTime('now', new \DateTimeZone('America/Buenos_Aires')))
                    ->format('Y-m-d H:i:s');
            }

            $arrayCollection[] = array(
                'id' => $item->getId(),
                'created_at' => $item->getCreatedAt()->format('Y-m-d H:i:s'),
                'cancelDate' => $dataHoraCancelamento,
                'status' => $item->getStatus(),
                'total' => $item->getTotal(),
                'buyer' => $arrayCustomer,
                'items' => $arrayItems
            );

            $arrayItems = [];

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

        $customer = $this->getDoctrine()->getRepository(Customers::class)->find($data['customerId']);

        if (!$customer) {
            throw $this->createNotFoundException('The customer does not exist!');
        }

        $order = (new Orders())
            ->setCustomer($customer)
            ->setTotal($data['total'])
            ->setStatus($data['status'])
            ->setCreatedAt(new \DateTime('now', new \DateTimeZone('America/Buenos_Aires')));

        $product = $this->getDoctrine()->getRepository(Products::class)->find($data['productId']);

        if (!$product) {
            throw $this->createNotFoundException('The product does not exist!');
        }

        $orderItems = (new OrderItems())
            ->setOrders($order)
            ->setProduct($product)
            ->setAmount($data['amount'])
            ->setPriceUnit($data['price_unit'])
            ->setTotal($data['totalItem']);

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($order);
        $manager->persist($orderItems);
        $manager->flush();

        return $this->json([
            'data' => 'Order created sucessfully!'
        ]);
    }

    /**
     * @Route("/{id}", name="update", methods={"PUT", "PATCH"})
     */
    public function update($id)
    {
       $doctrine = $this->getDoctrine();

        $order = $doctrine->getRepository(Orders::class)->find($id);

        if (!$order) {
            return $this->json([
                'data' => 'Order ' . $id . ' not exists!'
            ]);
        }

        $order->setStatus('CANCELED');

        $manager = $doctrine->getManager();
        $manager->flush();
        return $this->json([
            'data' => 'Order canceled!'
        ]);
    }

    private function inputsValidator($data) {

        if ($data['customerId'] == '' || $data['total'] == '' || $data['status'] == '' ||
           $data['productId'] == '' || $data['amount'] == '' || $data['price_unit'] == '' ||
           $data['totalItem'] == '') {
            return 'Todos os campos são obrigatórios!';
        }

        if (!is_numeric($data['total']) || $data['total'] < 1 ||
            !is_numeric($data['totalItem']) || $data['totalItem'] < 1) {
            return 'Total deve ser monetário e maior que zero!';
        }

        if (!is_numeric($data['price_unit']) || $data['price_unit'] < 1) {
            return 'Preço unitário deve ser monetário e maior que zero!';
        }

        return true;
    }
}
