<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Employee;
use App\Entity\WorkingDays;
use App\Form\AddTimeType;
use App\Form\EmployeeType;
use App\Manager\AddTimeManager;
use App\Manager\EmployeeManager;
use App\Repository\EmployeeRepository;
use App\Repository\WorkingDaysRepository;

class EmployeeController extends AbstractController
{
    private EmployeeRepository $employeeRepository;
    private EmployeeManager $employeeManager;
    private WorkingDaysRepository $workingDaysRepository;
    private AddTimeManager $addTimeManager;

    public function __construct(EmployeeRepository $employeeRepository,
                                EmployeeManager $employeeManager,
                                WorkingDaysRepository $workingDaysRepository,
                                AddTimeManager $addTimeManager)
    {
        $this->employeeRepository = $employeeRepository;
        $this->employeeManager = $employeeManager;
        $this->workingDaysRepository = $workingDaysRepository;
        $this->addTimeManager = $addTimeManager;
    }

    /**
     * @Route("/employee/{page?1}", name="list_employee", requirements={"page" = "\d+"},methods={"GET"})
     * @param int|null $page
     * @return Response
     */
    public function listEmployee(?int $page = 1): Response
    {
        $countPage = ceil($this->employeeRepository->countEmployees()[1] / 10);
        $employees = $this->employeeRepository->findEmployeeByPage($page);
        return $this->render('employee/listEmployee.html.twig', [
            'employees' => $employees,
            'countPage' => $countPage,
            'actualyPage' => $page,
            'url' => '/employee/'
        ]);
    }

    /**
     * @Route("/employee/edit", name="create_employee",methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function createEmployee(Request $request): Response
    {
        $employee = new Employee();
        $form = $this->createForm(EmployeeType::class, $employee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->employeeManager->save($employee);
            $this->addFlash('success', 'Employee has been created !');
            return $this->redirectToRoute('list_employee');
        }
            
        return $this->render('employee/formEmployee.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/employee/edit/{id}", name="edit_employee")
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function editEmployee(Request $request, int $id): Response
    {
        if ($this->getEmployee()->getId() === $id) {
            $employee = $this->employeeRepository->find($id);
            $form = $this->createForm(EmployeeType::class, $employee);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->employeeManager->update();
                $this->addFlash('success', 'Employee has been updated !');
                return $this->redirectToRoute('list_employee');
            }
            
            return $this->render('employee/formEmployee.html.twig', ['form' => $form->createView()]);

        } else {
            return $this->redirectToRoute('list_employee');
        }
    }

    /**
     * @Route("/employee/show/{id}/{page?1}", name="show_employee")
     * @param Request $request
     * @param int $id
     * @param int|null $page
     * @return Response
     */
    public function showEmployee(Request $request, int $id, ?int $page): Response
    {
        if ($this->getEmployee()->getId() === $id) {
            $hourlists = $this->workingDaysRepository->findAllValue($id, $page);
            $employee = $this->employeeRepository->find($id);
            $url = '/employee/show/' . $id . '/';
            $countPage = ceil($this->workingDaysRepository->countLineByEmployee($id)[1] / 5);

            $addTime = new WorkingDays();
            $addTime->setEmployee($hourlists[0]->getEmployee());
            $form = $this->createForm(AddTimeType::class, $addTime);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->addTimeManager->save($addTime);
                $this->addFlash('success', 'Time has been added to the project !');
                return $this->redirectToRoute('show_employee', ['id' => $id]);
            }

            return $this->render('employee/detailEmployee.html.twig', [
                'employee' => $employee,
                'hourlists' => $hourlists,
                'form' => $form->createView(),
                'countPage' => $countPage,
                'actualyPage' => $page,
                'url' => $url
            ]);

        } else {
            return $this->redirectToRoute('list_employee');
        }
    }

}
