<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\EmployeeRepository;
use App\Repository\JobRepository;
use App\Repository\WorkingDaysRepository;
use App\Repository\ProjectRepository;

class MainController extends AbstractController
{
    private JobRepository $jobRepository;
    private EmployeeRepository $employeeRepository;
    private ProjectRepository $projectRepository;
    private WorkingDaysRepository $workingDaysRepository;

    public function __construct(JobRepository $jobRepository,
                                EmployeeRepository $employeeRepository,
                                ProjectRepository $projectRepository,
                                WorkingDaysRepository $workingDaysRepository)
    {
        $this->jobRepository = $jobRepository;;
        $this->employeeRepository = $employeeRepository;
        $this->projectRepository = $projectRepository;
        $this->workingDaysRepository = $workingDaysRepository;
    }

    /**
     * @Route("/", name="main_homepage")
     */
    public function index(): Response
    {
        $countEmployees = $this->employeeRepository->countEmployees()[1];
        $projects = $this->projectRepository->findCostByProject();
        $productionTimes = $this->workingDaysRepository->findTenLatestCreateInfos();
        $finishCountProject = $this->projectRepository->finishCountProject()[1];
        $notFinishCountProject = $this->projectRepository->notFinishCountProject()[1];
        $countDays = $this->workingDaysRepository->countDays();
        $bestEmployee = $this->workingDaysRepository->bestEmployee();
        $deliveryRate = ($finishCountProject / ($finishCountProject + $notFinishCountProject)) * 100;
        $profitable = $this->calculateProfitability($this->projectRepository->projectListFinish());
        return $this->render('main/homepage.html.twig', [
            'countEmployees' => $countEmployees,
            'projects' => $projects,
            'productionTimes' => $productionTimes,
            'finishCountProject' => $finishCountProject,
            'notFinishCountProject' => $notFinishCountProject,
            'countDays' => $countDays,
            'bestEmployee' => $bestEmployee,
            'deliveryRate' => $deliveryRate,
            'profitable' => $profitable
        ]);
    }

    private function calculateProfitability($projects)
    {
        $nbproject = 0;
        $nbprojectProfitability = 0;
        foreach ($projects as &$value) {
            if ($value['total'] === null) {
                $value['total'] = 0;
            } else {
                $value['total'] = intval($value['total']);
            }
            $nbproject++;
            if ($value['project']->getPrice() < $value['total']) {
                $nbprojectProfitability++;
            }
        }
        if ($nbproject === 0) {
            return 0;
        }
        return ((($nbproject - $nbprojectProfitability) / $nbproject) * 100);
    }
}
