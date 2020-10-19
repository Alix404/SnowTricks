<?php


namespace App\Controller;

use App\Entity\TrickSearch;
use App\Form\TrickSearchType;
use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @param TrickRepository $repository
     * @param Request $request
     * @return Response
     */
    public function index(TrickRepository $repository, Request $request): Response
    {
        $search = new TrickSearch();
        $form = $this->createForm(TrickSearchType::class, $search);
        $form->handleRequest($request);

        $results = $this->paginate($repository, $search);
        $tricks = $results['tricks'];
        $currentPage = $results['currentPage'];
        $pages = $results['pages'];
        return $this->render('pages/home.html.twig', [
            'tricks' => $tricks,
            'currentPage' => $currentPage,
            'pages' => $pages,
            'form' => $form->createView()
        ]);
    }

    private function paginate(TrickRepository $repository, TrickSearch $search)
    {
        if (isset($_GET['page']) && !empty($_GET['page'])) {
            $currentPage = (int)strip_tags($_GET['page']);
        } else {
            $currentPage = 1;
        }

        $nb_tricks = $repository->countTricks($search);
        $perPages = 9;
        $pages = ceil($nb_tricks / $perPages);
        $first = ($currentPage * $perPages) - $perPages;

        $tricks = $repository->findWithOrder($first, $perPages, $search);
        return ['pages' => $pages, 'currentPage' => $currentPage, 'tricks' => $tricks];
    }
}