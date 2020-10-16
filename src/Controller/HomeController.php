<?php


namespace App\Controller;

use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @param TrickRepository $repository
     * @return Response
     */
    public function index(TrickRepository $repository): Response
    {
        $results = $this->paginate($repository);
        $tricks = $results['tricks'];
        $currentPage = $results['currentPage'];
        $pages = $results['pages'];
        return $this->render('pages/home.html.twig', [
            'tricks' => $tricks,
            'currentPage' => $currentPage,
            'pages' => $pages
        ]);
    }

    private function paginate(TrickRepository $repository)
    {
        if (isset($_GET['page']) && !empty($_GET['page'])) {
            $currentPage = (int)strip_tags($_GET['page']);
        } else {
            $currentPage = 1;
        }

        $nb_tricks = $repository->countTricks();
        $perPages = 9;
        $pages = ceil($nb_tricks / $perPages);
        $first = ($currentPage * $perPages) - $perPages;

        $tricks = $repository->findWithOrder($first, $perPages);
        return ['pages' => $pages, 'currentPage' => $currentPage, 'tricks' => $tricks];
    }
}