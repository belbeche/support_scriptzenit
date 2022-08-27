<?php

namespace App\Controller\Back;

use App\Entity\Article;
use App\Entity\Image;
use App\Entity\User;
use App\Form\Article\Type\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Knp\Component\Pager\PaginatorInterface;


class ArticleController extends AbstractController
{

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return mixed
     * @Route("/admin/articles", name="back_articles_list")
     * @IsGranted("ROLE_ADMIN")
     */
    public function list(
        Request $request,
        PaginatorInterface $paginator)
    {

        $data = $this->entityManager->getRepository(Article::class)->findAll();

        $articles = $paginator->paginate(
            $data,
            $request->query->getInt('page',1),
            12
        );

        return $this->render('back/article/list.html.twig', [
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/admin/articles/new", name="back_articles_new")
     *
     * @param Request $request
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     */
    public function new(
        Request $request
    ): Response
    {
        $article = new Article();

        $form = $this->createForm(ArticleType::class, $article);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $article->setUser($this->getUser());

                $images = $form->get('images')->getData();

                foreach($images as $image){
                    // We generate a new file name
                    $fichier = md5(uniqid()).'.'.$image->guessExtension();

                    // On copie le fichier dans le dossier uploads
                    try {
                        $image->move(
                            $this->getParameter('images_directory'),
                            $fichier
                        );
                    } catch (FileException $e) {
                    return new Response($e->getMessage());
                }


                    // On crée l'image dans la base de données
                    $img = new Image();
                    $img->setName($fichier);
                    $article->addImage($img);
                    $img->setArticle($article);
                }

                if ($article->getCreatedAt() === null) {
                    // on renseigne le slug de l'article
                    $article->setSlug(
                        strtolower($article->getSlug())
                    );
                }

                $this->entityManager->persist($article);
                $this->entityManager->flush();

                $this->addFlash('back_articles_ajouter_success','Article ajouté avec succéss');

                return $this->redirectToRoute('back_articles_list');
            }
        }

        return $this->render('back/article/new.html.twig', [
            'form' => $form->createView(),
            'article' => $article,
        ]);
    }

    /**
     * @Route("/admin/articles/show/{slug}", name="back_articles_show")
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     */
    public function show(
        Article $article
    ): Response
    {
        $article = $this->entityManager->getRepository(Article::class)->find($article);

        return $this->render('back/article/show.html.twig', [
            'article' => $article
        ]);
    }

    /**
     * @Route("/admin/articles/edit/{id}", name="back_articles_edit")
     * @param Request $request
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit($id, Request $request): Response
    {
        $article = $this->entityManager->getRepository(Article::class)->find($id);
        $form = $this->createForm(ArticleType::class, $article);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid())
            {
                $images = $form->get('images')->getData();

                foreach($images as $image){
                    // We generate a new file name
                    $fichier = md5(uniqid()).'.'.$image->guessExtension();

                    // On copie le fichier dans le dossier uploads
                    $image->move(
                        $this->getParameter('images_directory'),
                        $fichier
                    );

                    // On crée l'image dans la base de données
                    $img = new Image();
                    $img->setName($fichier);
                    $article->addImage($img);
                    $img->setArticle($article);
                }

                /*dd($article->getCategories());*/
                $this->entityManager->persist($article);
                $this->entityManager->flush();

                return $this->redirectToRoute('back_articles_list');
            }
        }

        return $this->render('back/article/edit.html.twig', [
            'form' => $form->createView(),
            'article' => $article,
        ]);
    }


    /**
     * @Route("/admin/articles/disable/{id}", name="back_articles_disable")
     * @param $id
     * @return mixed
     * @IsGranted("ROLE_ADMIN")
     */
    public function disable($id): Response
    {
        $article = $this->entityManager->getRepository(Article::class)->find($id);
        $article->setActive(!$article->isActive());
        $this->entityManager->persist($article);
        $this->entityManager->flush();
        if($article->isActive() == true){
            $this->addFlash('back_article_disable', "L'article ".$article->getSlug()." a bien était désactiver :[");
        }else {
            $this->addFlash('back_article_enable', "L'article ".$article->getSlug()." a bien était réactiver :]");
        }

        return $this->redirectToRoute('back_articles_list');
    }

    /**
     * @Route("/admin/article/remove/{id}", name="back_articles_remove")
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     */
    public function remove($id,Request $request): Response
    {
        $article = $this->entityManager->getRepository(Article::class)->find($id);


        if($article->isActive(false)){
            $this->addFlash('back_article_remove', "L'article " . $article->getSlug() . " est supprimé avec success !");
            $this->entityManager->remove($article);
            $this->entityManager->flush();
        }else {
            $this->addFlash('back_article_non_remove', "L'article " . $article->getSlug() . " est activé, disactivez-le puis réessayer, merci.");
        }

        return $this->redirectToRoute('back_articles_list');
    }

    /**
     * @Route("/back/articles/remove/image/{id}", name="back_articles_remove_image", methods={"DELETE"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function deleteImage(Image $image, Request $request){
        $data = json_decode($request->getContent(), true);

        // We check if the token is valid
        if($this->isCsrfTokenValid('delete'.$image->getId(), $data['_token'])){
            // We get the name of the image
            $nom = $image->getName();
            // We delete the file
            unlink($this->getParameter('images_directory').'/'.$nom);

            // We delete the entry from the database
            $this->entityManager->remove($image);
            $this->entityManager->flush();

            // We answer in json
            return new JsonResponse(['success' => 1]);
        }else{
            return new JsonResponse(['error' => 'Token Invalide'], 400);
        }
    }
}
