<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use DateTime;

use App\Entity\WorkingHours;
use App\Entity\Project;
use App\Form\AddTimeInProjectType;
use App\Form\AddTimeInProjectWithoutEmployeeType;
use App\Form\ProjectType;
use App\Manager\AddTimeManager;
use App\Manager\ProjectManager;
use App\Repository\WorkingHoursRepository;
use App\Repository\ProjectRepository;

class ProjectController extends AbstractController
{
    private ProjectRepository $projectRepository;
    private ProjectManager $projectManager;
    private WorkingHoursRepository $workingHoursRepository;
    private AddTimeManager $addTimeManager;

    public function __construct(ProjectRepository $projectRepository,
                                ProjectManager $projectManager,
                                WorkingHoursRepository $workingHoursRepository,
                                AddTimeManager $addTimeManager)
    {
        $this->projectRepository = $projectRepository;
        $this->projectManager = $projectManager;
        $this->workingHoursRepository = $workingHoursRepository;
        $this->addTimeManager = $addTimeManager;
    }

    /**
     * @Route("/project/{page?1}", name="list_project", requirements={"page" = "\d+"},methods={"GET"})
     * @param int|null $page
     * @return Response
     */
    public function listProject(?int $page = 1): Response
    {
        $projects = $this->projectRepository->findProjectByPage($page);
        $countPage = ceil($this->projectRepository->countProjects()[1] / 10);

        return $this->render('project/listProject.html.twig', [
            'projects' => $projects,
            'countPage' => $countPage,
            'actualyPage' => $page,
            'url' => '/project/'
        ]);
    }

    /**
     * @Route("/project/edit", name="create_project")
     * @param Request $request
     * @return Response
     */
    public function createProject(Request $request): Response
    {
        $project = new Project();
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->projectManager->save($project);
            $this->addFlash('success', 'Project has been created !');
            return $this->redirectToRoute('list_project');
        }

        return $this->render('project/formProject.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/project/edit/{id}", name="edit_project",methods={"GET","POST"})
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function editProject(Request $request, int $id): Response
    {
        $project = $this->projectRepository->find($id);
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->projectManager->update();
            $this->addFlash('success', 'Project has been updated !');
            return $this->redirectToRoute('list_project');
        }

        return $this->render('project/formProject.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/project/show/{id}/{page?1}", name="show_project")
     * @param Request $request
     * @param int $id
     * @param int|null $page
     * @return Response
     */
    public function showProject(Request $request, int $id, ?int $page): Response
    {
        $project = $this->projectRepository->find($id);
        $infoEmployeeOnPrj = $this->workingHoursRepository->findValuePersonByProject($id, $page);
        $infoCostProject = $this->workingHoursRepository->findEmployeeByProject($id);
        $url = '/project/show/' . $id . '/';
        $countPage = ceil($this->workingHoursRepository->countLineByProject($id)[1] / 5);

            if ($project->getDeliveryDate() === null) {
                $addTime = new WorkingHours();
                $addTime->setProject($project);
                $addTime->setEmployee($this->getEmployee());
                $form = $this->createForm(AddTimeInProjectType::class, $addTime);
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                    $this->addTimeManager->save($addTime);
                    $this->addFlash('success', 'Employee has been added to the project !');
                    return $this->redirectToRoute('show_project', ['id' => $id]);
                }
                return $this->render('project/detailProject.html.twig', [
                    'project' => $project,
                    'infoEmployeeOnPrj' => $infoEmployeeOnPrj,
                    'infoCostProject' => $infoCostProject,
                    'form' => $form->createView(),
                    'countPage' => $countPage,
                    'actualyPage' => $page,
                    'url' => $url
                ]);
            } else {

                return $this->render('project/detailProject.html.twig', [
                    'project' => $project,
                    'infoEmployeeOnPrj' => $infoEmployeeOnPrj,
                    'infoCostProject' => $infoCostProject,
                    'countPage' => $countPage,
                    'actualyPage' => $page,
                    'url' => $url
                ]);
            }
    }

    /**
     * @Route("/project/push/{id}", name="push_project")
     * @param int $id
     * @return Response
     */
    public function pushProject(int $id): Response
    {
        $project = $this->projectRepository->find($id);
        $project->setDeliveryDate(new DateTime());
        $this->projectManager->update();
        $this->addFlash('success', 'Project has been delivered !');     
    }

}
