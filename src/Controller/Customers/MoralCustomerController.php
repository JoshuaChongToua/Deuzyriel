<?php

namespace App\Controller\Customers;

use App\Entity\Organization;
use App\Entity\Project;
use App\Form\MoralFormType;
use App\Entity\CustomerMorals;
use App\Form\PhysicalFormType;
use App\Entity\CustomerPhysicals;
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

class MoralCustomerController extends TemplateManager
{
    #[Route('/project/customer/moral', name: 'project.customer.moral', methods: ['GET'])]
    public function moralIndex(Request $request, MoralCustomersRepository $moralCustomersRepository, ProjectRepository $projectRepository): Response
    {
        $moralCustomersEntities = $moralCustomersRepository->findAll();
        $list = [];
        foreach ($moralCustomersEntities as $physicalCustomers) {
            $list[] = [
                "id" => $physicalCustomers->getId(),
                "companyName" => $physicalCustomers->getCompanyName(),
                "companyType" => $physicalCustomers->getCompanyType(),
                "siret" => $physicalCustomers->getSiret(),
                "contactName" => $physicalCustomers->getContactName(),
                "contactFirstName" => $physicalCustomers->getContactFirstName()
            ];
        }
        return $this->display($request, 'pages/customers/moral_index.html.twig', [
            'listMorals' => $list
        ]);
    }

    #[Route('/project/customer/create/moral', name: 'project.customer.create.moral', methods: ['GET', 'POST'])]
    public function moralCreate(Request $request, EntityManagerInterface $em, OrganizationsRepository $organizationRepository, ProjectRepository $projectRepository): Response
    {

        $projectEntity = $projectRepository->findOneBy(['id' => $request->get('id_project')]);
        $moralEntity = new CustomerMorals();
        //$organizationEntities = $organizationRepository->findAll();
        $moralForm = $this->createForm(MoralFormType::class, $moralEntity);
        $moralForm->handleRequest($request);


        if ($moralForm->isSubmitted() && $moralForm->isValid()) {
            $em->persist($moralEntity);
            $em->flush();
            $this->addFlash("success", "Le nouveau donateur a bien été pris en compte.");


            return $this->redirectToRoute('project.customer.moral', ['id_project' => $request->get('id_project')]);
        }

        return $this->display($request, 'pages/customers/moralForm.html.twig', [
            'moralForm' => $moralForm->createView(),
            'project' => $projectEntity
        ]);
    }
    #[Route('/project/customer/{id_moral}/moralDelete', name: 'project.customer.moralDelete', methods: ['DELETE'])]
    public function moralDelete(Request $request, MoralCustomersRepository $moralCustomersRepository, EntityManagerInterface $em): Response
    {

        $moralEntity = $moralCustomersRepository->findOneBy(['id' => $request->get('id_moral')]);
        // early return

        $em->remove($moralEntity);
        $em->flush();
        $this->addFlash("success", "La suppression du donateur a bien été prise en compte.");

        return $this->redirectToRoute('project.customer.moral');
    }

}