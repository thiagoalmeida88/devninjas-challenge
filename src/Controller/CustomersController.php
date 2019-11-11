<?php

namespace App\Controller;

use App\Entity\Customers;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/customers", name="customers_")
 */
class CustomersController extends AbstractController
{
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

        $customers = (new Customers())
            ->setName($data['name'])
            ->setCpf($data['cpf'])
            ->setEmail($data['email'])
            ->setCreatedAt(new \DateTime('now', new \DateTimeZone('America/Buenos_Aires')))
            ->setUpdatedAt(new \DateTime('now', new \DateTimeZone('America/Buenos_Aires')));

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($customers);
        $manager->flush();

        return $this->json([
            'data' => 'Customer created sucessfully!'
        ]);
    }

    private function inputsValidator($data) {

        $customers = $this->getDoctrine()->getRepository(Customers::class)->findAll();

        if ($data['name'] == '' || $data['cpf'] == '' || $data['email'] == '') {
            return 'Todos os campos são obrigatórios!';
        }

        foreach ($customers as $item) {
            if ($data['cpf'] == $item->getCpf()) {
                return 'CPF já existe!';
            }

            if ($data['email'] == $item->getEmail()) {
                return 'Email já existe!';
            }
        }

        if (!\App\Helpers\Functions::validaCPF($data['cpf'])) {
            return 'CPF: '. $data['cpf'] . ' inválido!';
        }

        return true;
    }
}
