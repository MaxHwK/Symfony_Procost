<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Job;
use App\Form\JobType;
use App\Manager\JobManager;
use App\Repository\JobRepository;

class JobController extends AbstractController
{
    private JobRepository $jobRepository;
    private JobManager $jobManager;

    public function __construct(JobRepository $jobRepository, JobManager $jobManager)
    {
        $this->jobRepository = $jobRepository;
        $this->jobManager = $jobManager;
    }

    /**
     * @Route("/job/{page?1}", name="list_job",requirements={"page" = "\d+"},methods={"GET"} )
     * @param int|null $page
     * @return Response
     */
    public function listJob(?int $page = 1): Response
    {
        $jobs = $this->jobRepository->findAllJobsPossibilitiesToDelete($page);
        $countPage = ceil($this->jobRepository->countJobs()[1] / 10);
        return $this->render('job/listJob.html.twig', [
            'jobs' => $jobs,
            'countPage' => $countPage,
            'actualyPage' => $page,
            'url' => '/job/'
        ]);
    }

    /**
     * @Route("/job/edit", name="create_job")
     * @param Request $request
     * @return Response
     */
    public function createJob(Request $request): Response
    {
        $job = new Job();
        $form = $this->createForm(JobType::class, $job);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->jobManager->save($job);
            $this->addFlash('success', 'Job has been created !');
            return $this->redirectToRoute('list_job');
        }

        return $this->render('job/formJob.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/job/edit/{id}", name="edit_job")
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function editJob(Request $request, int $id): Response
    {
        $job = $this->jobRepository->find($id);
        $form = $this->createForm(JobType::class, $job);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->jobManager->update();
            $this->addFlash('success', 'Job has been updated !');
            return $this->redirectToRoute('list_job');
        }

        return $this->render('job/formJob.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return Response
     * @Route("/job/delete/{id}", name="delete_job")
     */
    public function deleteJob(Request $request, int $id): Response
    {
        $job = $this->jobRepository->find($id);
        $this->jobManager->delete($job);
        return $this->redirectToRoute('list_job');
        
    }

}
