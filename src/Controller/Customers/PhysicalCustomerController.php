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

class PhysicalCustomerController extends TemplateManager
{
    #[Route('/project/customer/physical', name: 'project.customer.physical', methods: ['GET'])]
    public function physicalIndex(Request $request, PhysicalCustomersRepository $physicalCustomersRepository, ProjectRepository $projectRepository): Response
    {
       $physicalCustomersEntities = $physicalCustomersRepository->findAll();
       $list = [];
       foreach ($physicalCustomersEntities as $physicalCustomers) {
           $list[] = [
               "id" => $physicalCustomers->getId(),
               "firstName" => $physicalCustomers->getFirstName(),
               "lastName" => $physicalCustomers->getLastName(),
               "birthDate" => $physicalCustomers->getDateBirthday(),
               "gender" => $physicalCustomers->getGender(),
           ];
       }
        return $this->display($request, 'pages/customers/physical_index.html.twig', [
            'listPhysicals' => $list
        ]);
    }






    #[Route('/project/customer/{id_physical}/physicalDelete', name: 'project.customer.physicalDelete', methods: ['DELETE'])]
    public function physicalDelete(Request $request, PhysicalCustomersRepository $physicalCustomersRepository, EntityManagerInterface $em): Response
    {

        $physicalEntity = $physicalCustomersRepository->findOneBy(['id' => $request->get('id_physical')]);
        // early return

        $em->remove($physicalEntity);
        $em->flush();
        $this->addFlash("success", "La suppression du donateur a bien été prise en compte.");

        return $this->redirectToRoute('project.customer.physical');
    }



    #[Route('/project/{id_project}/customers/list', name: 'project.customers.list', methods: ['GET'])]
    public function showContactsList(ProjectRepository $projectRepository, Request $request, DonationsRepository $donationsRepository): Response
    {


        // on récupère l'id du projet depuis l'url
        $projectId = $request->get('id_project');

        // on retrouve l'entité projet qui correspond à l'id
        $projectEntity = $projectRepository->find($projectId);

        // on récupère le nom de projet
        $projectName = $projectEntity->getProjectName();

        $listMoral = [];
        $listPhysical = [];
        $donationsEntities = $donationsRepository->findAll();


        foreach ($donationsEntities as $donation) {
            if ($donation->getProject() === $projectEntity) {
                if ($donation->getMoralCustomer() != null) {
                    $type = "moral";
                    $moral = $donation->getMoralCustomer();

                    $listMoral[] = [
                        'id' => $moral->getId(),
                        'type' => $type,
                        'organization' => $moral->getOrganization()->getOrganizationName(),
                        'companyName' => $moral->getCompanyName(),
                        'companyType' => $moral->getCompanyType(),
                        'siret' => $moral->getSiret(),
                        'email' => $moral->getEmail(),
                        'address' => $moral->getAddress(),
                        'zip' => $moral->getZip(),
                        'city' => $moral->getCity(),
                        'country' => $moral->getCountry(),
                        'createdAt' => $moral->getCreatedAt(),
                        'updatedAt' => $moral->getUpdatedAt()
                    ];
                } elseif ($donation->getPhysicalCustomer() != null) {
                    $type = "physique";
                    $physical = $donation->getPhysicalCustomer();

                    $listPhysical[] = [
                        'type' => $type,
                        'organization' => $physical->getOrganization()->getOrganizationName(),
                        'id' => $physical->getId(),
                        'firstName' => $physical->getFirstName(),
                        'lastName' => $physical->getLastName(),
                        'gender' => $physical->getGender(),
                        'dateBirthday' => $physical->getDateBirthday()->format('d-m-Y'),
                        'email' => $physical->getEmail(),
                        'address' => $physical->getAddress(),
                        'zip' => $physical->getZip(),
                        'country' => $physical->getCountry(),
                        'city' => $physical->getCity(),
                        'isNpai' => $physical->isIsNPAI(),
                        'createdAt' => $physical->getCreatedAt(),
                        'updatedAt' => $physical->getUpdatedAt()
                    ];
                }
            }
        }

        return $this->display($request, 'pages/customer/customerIndex.html.twig', [
            'listMoral' => $listMoral,
            'listPhysical' => $listPhysical,
            'projectName' => $projectName,
            'project'=> $projectEntity
        ]);
    }

    #[Route('/project/customer/create/physical', name: 'project.customer.create.physical', methods: ['GET', 'POST'])]
    public function physicalCreate(Request $request, EntityManagerInterface $em, OrganizationsRepository $organizationRepository, ProjectRepository $projectRepository): Response
    {

        $projectEntity = $projectRepository->findOneBy(['id' => $request->get('id_project')]);
        $physicalEntity = new CustomerPhysicals();
        $organizationEntities = $organizationRepository->findAll();
        $physicalForm = $this->createForm(PhysicalFormType::class, $physicalEntity,['organizations' => $organizationEntities]);
        $physicalForm->handleRequest($request);


        if ($physicalForm->isSubmitted() && $physicalForm->isValid()) {
            $em->persist($physicalEntity);
            $em->flush();
            $this->addFlash("success", "Le nouveau donateur a bien été pris en compte.");


            return $this->redirectToRoute('project.customer.physical', ['id_project' => $request->get('id_project')]);
        }

        return $this->display($request, 'pages/customers/physicalForm.html.twig', [
            'physicalForm' => $physicalForm->createView(),
            'project' => $projectEntity
        ]);
    }
}
