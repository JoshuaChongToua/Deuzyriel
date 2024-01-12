<?php

namespace App\Controller\Customers;

use App\Entity\Customers;
use App\Entity\Organization;
use App\Entity\Project;
use App\Form\CustomerFormType;
use App\Repository\CustomersRepository;
use App\Repository\DonationsRepository;
use App\Template\TemplateManager;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\MoralCustomersRepository;
use App\Repository\OrganizationsRepository;
use phpDocumentor\Reflection\DocBlock\Tags\Method;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\PhysicalCustomersRepository;
use App\Repository\ProjectRepository;
use Symfony\Component\Routing\Annotation\Route;

class CustomerController extends TemplateManager
{
    #[Route('/project/customer/moral', name: 'project.customer', methods: ['GET'])]
    public function customerIndex(Request $request, CustomersRepository $customersRepository, ProjectRepository $projectRepository): Response
    {
        $customersEntities = $customersRepository->findAll();
        $list = [];
        foreach ($customersEntities as $customer) {
            $list[] = [
                "id" => $customer->getId(),
                "customerType" => $customer->getCustomerType(),
                "organizationName" => $customer->getOrganization()->getOrganizationName(),
                "email" => $customer->getEmail(),
                "zip" => $customer->getZip(),
                "city" => $customer->getCity(),
                "country" => $customer->getCountry(),
                "tel" => $customer->getTel(),
                "createdAt" => $customer->getCreatedAt(),
                "updatedAt" => $customer->getUpdatedAt(),
                "isNpai" => $customer->isIsNPAI(),
            ];
        }
        return $this->display($request, 'pages/customers/customers_index.html.twig', [
            'listCustomers' => $list
        ]);
    }

    #[Route('/project/customer/create', name: 'project.customer.create', methods: ['GET', 'POST'])]
    public function customerCreate(Request $request, EntityManagerInterface $em, OrganizationsRepository $organizationRepository, ProjectRepository $projectRepository): Response
    {

        $projectEntity = $projectRepository->findOneBy(['id' => $request->get('id_project')]);
        $customerEntity = new Customers();
        $customerId = $request->get('customerId');
        $customerEntity->setId($customerId);
        var_dump($customerId);
        $organizationEntities = $organizationRepository->findAll();
        $customerForm = $this->createForm(CustomerFormType::class, $customerEntity,['organizations' => $organizationEntities]);
        $customerForm->handleRequest($request);


        if ($customerForm->isSubmitted() && $customerForm->isValid()) {
            $organization = $customerForm->get('organization')->getData();
            $customerEntity->setOrganization($organization);
            $em->persist($customerEntity);
            $em->flush();
            $this->addFlash("success", "Le nouveau donateur a bien été pris en compte.");
            $customerType = $customerEntity->getCustomerType();

            /*if ($customerType === "physical") {
                return $this->redirectToRoute('project.customer.create.physical', ['id_project' => $request->get('id_project')]);
            }
            else {
                return $this->redirectToRoute('project.customer.create.moral', ['id_project' => $request->get('id_project')]);
            }*/
            return $this->redirectToRoute('project.customer', ['id_project' => $request->get('id_project')]);

        }

        return $this->display($request, 'pages/customers/customerForm.html.twig', [
            'customerForm' => $customerForm->createView(),
            'project' => $projectEntity
        ]);
    }
}