<?php


namespace App\Controller\User;


use App\Entity\Trick;
use App\Form\TrickType;
use App\Repository\TrickRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route ("/user/trick")
 */
class UserTrickController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @Route ("/", name="trick.index")
     * @param TrickRepository $repository
     * @return Response
     */
    public function index(TrickRepository $repository)
    {
        $tricks = $repository->findAll();
        return $this->render('user/trick/index.html.twig', [
            'tricks' => $tricks
        ]);
    }

    /**
     * @Route("/edit/{slug}-{id}", name="trick.edit", requirements={"slug": "[a-z0-9\-]*"})
     * @param Trick $trick
     * @param string $slug
     * @param Request $request
     * @return Response
     */
    public function edit(Trick $trick, string $slug, Request $request): Response
    {
        if ($trick->getSlug() !== $slug) {
            $this->redirectToRoute('trick.show', [
                'id' => $trick->getId(),
                'slug' => $trick->getSlug(),
            ], 301);
        }

        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->flush();
            $this->addFlash('success', 'La figure a été correctement modifiée');
            return $this->redirectToRoute('trick.show', [
                'id' => $trick->getId(),
                'slug' => $trick->getSlug(),
            ], 301);
        }
        return $this->render('user/trick/edit.html.twig', [
            'trick' => $trick,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/create", name="trick.create")
     * @param Request $request
     * @return Response
     */
    public function create(Request $request): Response
    {
        $trick = new Trick();

        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->persist($trick);
            $this->manager->flush();
            $this->addFlash('success', 'La figure a été correctement crée');

            return $this->redirectToRoute('home');
        }

        return $this->render('user/trick/create.html.twig', [
            'trick' => $trick,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete/{slug}-{id}", name="trick.delete", requirements={"slug": "[a-z0-9\-]*"}, methods="DELETE")
     * @param Trick $trick
     * @param string $slug
     * @param Request $request
     * @return Response
     */
    public function delete(Trick $trick, string $slug, Request $request)
    {
        if ($trick->getSlug() !== $slug) {
            $this->redirectToRoute('trick.show', [
                'id' => $trick->getId(),
                'slug' => $trick->getSlug(),
            ], 301);
        }

        if ($this->isCsrfTokenValid('delete' . $trick->getId(), $request->get('_token'))) {
            $this->manager->remove($trick);
            $this->manager->flush();
            $this->addFlash('success', 'La figure a été correctement supprimée');

        }
        return $this->redirectToRoute('home');
    }
}