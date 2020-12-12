<?php


namespace App\Controller;


use App\Entity\Comment;
use App\Entity\Trick;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Repository\TrickRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrickController extends AbstractController
{
    /**
     * @var TrickRepository
     */
    private $trickRepository;
    /**
     * @var EntityManagerInterface
     */
    private $manager;
    /**
     * @var CommentRepository
     */
    private $commentRepository;

    public function __construct(TrickRepository $trickRepository, EntityManagerInterface $manager, CommentRepository $commentRepository)
    {

        $this->trickRepository = $trickRepository;
        $this->manager = $manager;
        $this->commentRepository = $commentRepository;
    }

    /**
     * @Route("/tricks/{slug}-{id}", name="trick.show", requirements={"slug": "[a-z0-9\-]*"})
     * @param Trick $trick
     * @param string $slug
     * @param Request $request
     * @return Response
     */
    public function show(Trick $trick, string $slug, Request $request): Response
    {
        $commentEntity = new Comment();
        $form = $this->createForm(CommentType::class, $commentEntity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentEntity->setTrick($trick);
            $commentEntity->setParentId($_POST['parent_id']);
            $this->manager->persist($commentEntity);
            $this->manager->flush();
            $this->addFlash('success', 'Votre comentaire a été correctement ajouté');

            return $this->redirectToRoute('trick.show', ['slug' => $trick->getSlug(), 'id' => $trick->getId()]);
        }

        $comments = $this->commentRepository->findAllByTrick($trick);
        $comments_by_id = [];

        foreach ($comments as $comment) {
            $comments_by_id[$comment->getId()] = $comment;
        }

        foreach ($comments as $k => $comment) {
            if ($comment->getParentId() != 0) {
                $comments_by_id[$comment->getParentId()]->setChildren($comment);
                unset($comments[$k]);
            }
        }
        if ($trick->getSlug() !== $slug) {
            $this->redirectToRoute('trick.show', [
                'id' => $trick->getId(),
                'slug' => $trick->getSlug(),
            ], 301);
        }
        return $this->render('trick/show.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
            'comments' => $comments,
        ]);
    }
}