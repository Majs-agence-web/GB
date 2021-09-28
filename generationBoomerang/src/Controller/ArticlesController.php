<?php

namespace App\Controller;

use App\Entity\Pole;
use App\Entity\Image;
use App\Entity\Liker;
use App\Entity\Articles;
use App\Form\ArticlesType;
use Cocur\Slugify\Slugify;
use App\Entity\Commentaires;
use App\Form\CommentairesType;
use App\Repository\LikerRepository;
use Symfony\Component\Mercure\Update;
use App\Repository\ArticlesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Constraints\NotNull;


class ArticlesController extends AbstractController
{
    

    /**
     * @Route("/poles/publications", name="articles_pole_index", methods={"GET", "POST"})
     * @Route("/publications", name="articles_publications_index", methods={"GET", "POST"})
     */
    public function publications(ArticlesRepository $articlesRepository, Request $request, PaginatorInterface $paginator): Response
    {
        
        $currentRoute = $request->attributes->get('_route');
        if ( $currentRoute == 'articles_publications_index'){
            $donnees = $this->getDoctrine()->getRepository(Articles::class)->findBy(['pole'=>null],['createdAt' => 'desc']);
        }else{
            $donnees = $articlesRepository->searchPoleNotNull();
        }
        
        $sort = 'desc';
        $option1 ="Articles les plus récents";
        $option2 = "Articles les plus anciens";  

        if ($request->getMethod() == "POST")
        { 
            $keyWord = $request->request->get('keyWord');
            $donnees = $articlesRepository->search($keyWord);  
            if ($donnees == null) {
                $this->addFlash('erreur', 'Aucun article contenant ce mot clé dans le titre n\'a été trouvé, essayez en un autre.');

            }
        }

        $articles = $paginator->paginate($donnees, $request->query->getInt('page', 1), 8 );

        if(isset($_GET['sort'])){
            if($_GET['sort'] == 'Articles les plus anciens'){
            $sort = 'asc';
            $option1 ="Articles les plus anciens";
            $option2 = "Articles les plus récents";
            }else{
                $sort = 'desc';
                $option1 ="Articles les plus récents";
                $option2 = "Articles les plus anciens";
            }
        }

        if(isset($_GET['inno'])){
            
        }

            return $this->render('articles/index.html.twig', [
                'articles' => $articles,
                'sort' => $sort,
                'option1' => $option1,
                'option2' => $option2

        ]);
    }


    /**
     * @Route("pole/nouvelle-publication", name="articles_pole_new", methods={"GET","POST"})
     * @Route("publications/nouvelle-publication", name="articles_publications_new", methods={"GET","POST"})
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_FONDATEUR') or is_granted('ROLE_ABONNE')")
     */
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $article = new Articles();
        
        $currentRoute = $request->attributes->get('_route');
        if ( $currentRoute == 'articles_pole_new'){
            $poles = $this->getDoctrine()->getRepository(Pole::class)->findBy([
                'responsable' => $this->getUser()
            ]);
        }
        

        $form = $this->createForm(ArticlesType::class, $article);
        $form->handleRequest($request);
        

        if ($form->isSubmitted() && $form->isValid()) {
            

            if ( $currentRoute == 'articles_pole_new'){
                if ($request->request->get('pole') != NULL){
                    $pole=$this->getDoctrine()->getRepository(Pole::class)->find($request->request->get('pole'));            
                $article->setPole($pole);
                }
                
            }
            
           
            
            $images = $form->get("images")->getData();
            foreach( $images as $image){
                $img = new Image();
                $img->setImageFile($image);
                $article->addImage($img);
            }

            $article->setCreatedAt(new \DateTime('now'));
            $article->setAuthor($this->getUser());

            
            $entityManager = $this->getDoctrine()->getManager();
            

            $entityManager->persist($article);
            if($request->request->get('action') !== null){
                return $this->render('articles/preview.html.twig', [
                    'images' => $images,
                    'article' => $article,
                ]);
            }

            $entityManager->flush();

        if ( $currentRoute == 'articles_pole_new'){  
            return $this->redirectToRoute('articles_pole_show',[
                'slug' => $article->getSlug()
            ]);

        }else{ 
            return $this->redirectToRoute('articles_publications_show',[
                'slug' => $article->getSlug()
            ]);
         }   

        }

        if ( $currentRoute == 'articles_pole_new'){ 
                               
            return $this->render('articles/new.html.twig', [
                'poles' => $poles,
                'article' => $article,
                'form' => $form->createView(),
            ]);
        }else{             
            return $this->render('articles/new.html.twig', [
                'article' => $article,
                'form' => $form->createView(),
        ]);}
       
    }

    /**
    * @Route("pole/{slug}", name="articles_pole_show")    * 
    * @Route("publications/{slug}", name="articles_publications_show")
    * 
    */
    public function article($slug, Request $request)
    {
        
        $article = $this->getDoctrine()->getRepository(Articles::class)->findOneBy(['slug' => $slug]);

        $commentaires = $this->getDoctrine()->getRepository(Commentaires::class)->findBy([
            'Article' => $article,
            'actif' => 1
        ],['createdAt' => 'desc']);

        if(!$article)
        {
            throw $this->createNotFoundException('L\'article n\'existe pas');
        }

        $commentaire = new Commentaires();

        $form = $this->createForm(CommentairesType::class, $commentaire);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {
            $commentaire->setArticle($article)
                        ->setCreatedAt(new \DateTime('now'))
                        ->setUser($this->getUser())
                        ->setActif(true)
                        ->setRgpd(true);
            $doctrine = $this->getDoctrine()->getManager();
            $doctrine->persist($commentaire);
            $doctrine->flush();

            $currentRoute = $request->attributes->get('_route');
            if ( $currentRoute == 'articles_pole_show'){
                return $this->redirectToRoute('articles_pole_show',[
                    'slug' => $article->getSlug()
                ]);
            }else{
                return $this->redirectToRoute('articles_publications_show',[
                    'slug' => $article->getSlug()
                ]);
            }
            

        }

        return $this->render('articles/show.html.twig', [
            'form' => $form->createView(),
            'article' => $article,
            'commentaires' => $commentaires,
        ]);
    }

    /**
     * @Route("pole/{slug}/edit", name="articles_pole_edit", methods={"GET","POST"})
     * @Route("publications/{slug}/edit", name="articles_publications_edit", methods={"GET","POST"})
     * @Security("is_granted('ROLE_USER') and user  === article.getAuthor() or is_granted('ROLE_ADMIN')", message="Cette annonce ne vous appartient pas, vous ne pouvez pas la modifier")
     * @param Articles $article
     * @return Response
     */
    public function edit(Request $request, Articles $article): Response
    {
        $poles = $this->getDoctrine()->getRepository(Pole::class)->findAll();
        $form = $this->createForm(ArticlesType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $currentRoute = $request->attributes->get('_route');
            if ( $currentRoute == 'articles_pole_new'){
                $pole=$this->getDoctrine()->getRepository(Pole::class)->find($request->request->get('pole'));            
                $article->setPole($pole);
            }
            

            foreach($form->get("images")->getData() as $image){
                $img = new Image();
                $img->setImageFile($image);
                $article->addImage($img);
            }

            $slugify = new Slugify();
            $article->setSlug($slugify->slugify($article->getTitreArticle()));

            $this->getDoctrine()->getManager()->flush();

            $currentRoute = $request->attributes->get('_route');
            if ( $currentRoute == 'articles_pole_edit'){
                return $this->redirectToRoute('articles_pole_show',[
                    'slug' => $article->getSlug()
                ]);
                
            }else{
                return $this->redirectToRoute('articles_publications_show',[
                    'slug' => $article->getSlug()
                ]);
                
            }
            
        }

        return $this->render('articles/edit.html.twig', [
            'poles' => $poles,
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * permet de supprimer
     * @Route("pole/{slug}/delete", name="articles_pole_delete")
     * @Route("publications/{slug}/delete", name="articles_publications_delete")
     * @Security("is_granted('ROLE_USER') and user  == article.getAuthor()", message="Vous n'avez pas le droit d'accéder à cette ressource")
     * 
     * @param Articles $article
     * 
     * @return Response
     */
    public function delete(Request $request, Articles $article): Response
    {
    
        // if ($this->isCsrfTokenValid('delete'.$article->getSlug(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
           
            $entityManager->remove($article);
            $entityManager->flush();
        // }
        $currentRoute = $request->attributes->get('_route');
            if ( $currentRoute == 'articles_pole_delete'){
                return $this->redirectToRoute('articles_pole_index');
                
            }else{
                return $this->redirectToRoute('articles_publications_index');
                
            }
        
    }

    
    /**
     * @Route("/article/{id}/like", name="article_like")
     *
     * @param Articles $article
     * @param ObjectManager $manager
     * @param ArticlesRepository $ArtRepo
     * @return Response
     */
    public function like(Articles $article,  LikerRepository $likeRepo, MessageBusInterface $bus, \Swift_Mailer $mailer ) : Response {

        $user = $this->getUser();
        $manager = $this->getDoctrine()->getManager();

        if(!$user) return $this->json([
            'code' => 403,
            'message' => "Vous devez être connecté"
        ], 403);

        if($article->islikedByUser($user)){

            $like = $likeRepo->findOneBy([
                'article' => $article,
                'user' => $user
            ]);
            
            
            $manager->remove($like);
            $manager->flush();

            return $this->json([
                'code' => 200,
                'message' => 'Like bien supprimé',
                'isLiked' => false,
                'likes' => $likeRepo->count(['article' => $article])
            ], 200);

        }

        $like = new Liker();
        $like->setArticle($article)
             ->setUser($user);
    
        $manager->persist($like);
        $manager->flush();

        // $message = (new \Swift_Message('Article liké'))
        //     ->setFrom('generation.boomerang2020@gmail.com')
        //     ->setTo($article->getAuthor()->getEmail())
        //     ->setBody(
        //         $this->renderView(
        //             'notification_like.html.twig', ['titreArticle' => $article->getTitreArticle()]
        //         ),
        //         'text/html'
        //     );
        // $mailer->send($message);

        // $update = new Update(
        //     'http://example.com/article/'.$id.'/like',
        //     json_encode(['status' => 'article liké'])
        // );

        // // Sync, or async (RabbitMQ, Kafka...)
        // $bus->dispatch($update);

        return $this->json([
            'code' => 200, 
            'message' => 'Like bien ajouté',
            'isLiked' => true,
            'likes' => $likeRepo->count(['article' => $article])
        ], 200);
    }

    /**
     * @Route("/image/{id}/remove", name="image_remove")
     * 
     */ 
     public function removeImg(Image $image,int $id) : Response {

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($image);
        $entityManager->flush();

        return $this->json([
            'code' => 200, 
            'message' => 'image bien supprimé',
            'id' => $id
        ], 200);
     }
    // /**
    //  * @Route("/", name="articles_index", methods={"GET", "POST"})
    //  */
    // public function index(ArticlesRepository $articlesRepository, Request $request, PaginatorInterface $paginator): Response
    // {
    //     $donnees = $this->getDoctrine()->getRepository(Articles::class)->findBy([],['createdAt' => 'desc']);
    //     $sort = 'desc';
    //     $option1 ="Articles les plus récents";
    //     $option2 = "Articles les plus anciens";  

    //     if ($request->getMethod() == "POST")
    //     { 
    //         $keyWord = $request->request->get('keyWord');
    //         $donnees = $articlesRepository->search($keyWord);  
    //         if ($donnees == null) {
    //             $this->addFlash('erreur', 'Aucun article contenant ce mot clé dans le titre n\'a été trouvé, essayez en un autre.');

    //         }
    //     }

    //     $articles = $paginator->paginate($donnees, $request->query->getInt('page', 1), 8 );

    //     if(isset($_GET['sort'])){
    //         if($_GET['sort'] == 'Articles les plus anciens'){
    //         $sort = 'asc';
    //         $option1 ="Articles les plus anciens";
    //         $option2 = "Articles les plus récents";
    //         }else{
    //             $sort = 'desc';
    //             $option1 ="Articles les plus récents";
    //             $option2 = "Articles les plus anciens";
    //         }
    //     }

    //     if(isset($_GET['inno'])){
            
    //     }

    //         return $this->render('articles/index.html.twig', [
    //             'articles' => $articles,
    //             'sort' => $sort,
    //             'option1' => $option1,
    //             'option2' => $option2

    //     ]);
    // }


    // /**
    //  * @Route("/new", name="articles_new", methods={"GET","POST"})
    //  * @IsGranted("ROLE_USER")
    //  */
    // public function new(Request $request, EntityManagerInterface $manager): Response
    // {
    //     $article = new Articles();
        
    //     $poles = $this->getDoctrine()->getRepository(Pole::class)->findAll();

    //     $form = $this->createForm(ArticlesType::class, $article);
    //     $form->handleRequest($request);

        

    //     if ($form->isSubmitted() && $form->isValid()) {
            
    //         $pole=$this->getDoctrine()->getRepository(Pole::class)->find($request->request->get('pole'));
    //         $article->setPole($pole);
    //         $images = $form->get("images")->getData();
    //         foreach( $images as $image){
    //             $img = new Image();
    //             $img->setImageFile($image);
    //             $article->addImage($img);
    //         }

    //         $article->setCreatedAt(new \DateTime('now'));
    //         $article->setAuthor($this->getUser());

            
    //         $entityManager = $this->getDoctrine()->getManager();
            

    //         $entityManager->persist($article);
    //         if($request->request->get('action') !== null){
    //             return $this->render('articles/preview.html.twig', [
    //                 'images' => $images,
    //                 'article' => $article,
    //             ]);
    //         }

    //         $entityManager->flush();
         
    //         return $this->redirectToRoute('articles_show',[
    //             'slug' => $article->getSlug()
    //         ]);

            
    //     }

    //     return $this->render('articles/new.html.twig', [
    //         'poles' => $poles,
    //         'article' => $article,
    //         'form' => $form->createView(),
    //     ]);
    // }

    // /**
    // * @Route("/{slug}", name="articles_show")
    // */
    // public function article($slug, Request $request)
    // {
        
    //     $article = $this->getDoctrine()->getRepository(Articles::class)->findOneBy(['slug' => $slug]);

    //     $commentaires = $this->getDoctrine()->getRepository(Commentaires::class)->findBy([
    //         'Article' => $article,
    //         'actif' => 1
    //     ],['createdAt' => 'desc']);

    //     if(!$article)
    //     {
    //         throw $this->createNotFoundException('L\'article n\'existe pas');
    //     }

    //     $commentaire = new Commentaires();

    //     $form = $this->createForm(CommentairesType::class, $commentaire);

    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) 
    //     {
    //         $commentaire->setArticle($article)
    //                     ->setCreatedAt(new \DateTime('now'))
    //                     ->setUser($this->getUser())
    //                     ->setActif(true)
    //                     ->setRgpd(true);
    //         $doctrine = $this->getDoctrine()->getManager();
    //         $doctrine->persist($commentaire);
    //         $doctrine->flush();

    //         return $this->redirectToRoute('articles_show',[
    //             'slug' => $article->getSlug()
    //         ]);

    //     }

    //     return $this->render('articles/show.html.twig', [
    //         'form' => $form->createView(),
    //         'article' => $article,
    //         'commentaires' => $commentaires,
    //     ]);
    // }

    // /**
    //  * @Route("/{slug}/edit", name="articles_edit", methods={"GET","POST"})
    //  * @Security("is_granted('ROLE_USER') and user  === article.getAuthor() or is_granted('ROLE_ADMIN')", message="Cette annonce ne vous appartient pas, vous ne pouvez pas la modifier")
    //  * @param Articles $article
    //  * @return Response
    //  */
    // public function edit(Request $request, Articles $article): Response
    // {
    //     $poles = $this->getDoctrine()->getRepository(Pole::class)->findAll();
    //     $form = $this->createForm(ArticlesType::class, $article);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {

    //         $pole=$this->getDoctrine()->getRepository(Pole::class)->find($request->request->get('pole'));
    //         $article->setPole($pole);

    //         foreach($form->get("images")->getData() as $image){
    //             $img = new Image();
    //             $img->setImageFile($image);
    //             $article->addImage($img);
    //         }

    //         $slugify = new Slugify();
    //         $article->setSlug($slugify->slugify($article->getTitreArticle()));

    //         $this->getDoctrine()->getManager()->flush();


    //         return $this->redirectToRoute('articles_show',[
    //             'slug' => $article->getSlug()
    //         ]);
    //     }

    //     return $this->render('articles/edit.html.twig', [
    //         'poles' => $poles,
    //         'article' => $article,
    //         'form' => $form->createView(),
    //     ]);
    // }

    // /**
    //  * permet de supprimer
    //  * @Route("/{slug}/delete", name="articles_delete")
    //  * @Security("is_granted('ROLE_USER') and user  == article.getAuthor()", message="Vous n'avez pas le droit d'accéder à cette ressource")
    //  * 
    //  * @param Articles $article
    //  * 
    //  * @return Response
    //  */
    // public function delete(Request $request, Articles $article): Response
    // {
    
    //     // if ($this->isCsrfTokenValid('delete'.$article->getSlug(), $request->request->get('_token'))) {
    //         $entityManager = $this->getDoctrine()->getManager();
           
    //         $entityManager->remove($article);
    //         $entityManager->flush();
    //     // }

    //     return $this->redirectToRoute('articles_index');
    // }

    
    /**
     * @Route("/article/{id}/like", name="article_like")
     *
     * @param Articles $article
     * @param ObjectManager $manager
     * @param ArticlesRepository $ArtRepo
     * @return Response
     */
    public function likeAd(Articles $article,  LikerRepository $likeRepo, MessageBusInterface $bus, \Swift_Mailer $mailer ) : Response {

        $user = $this->getUser();
        $manager = $this->getDoctrine()->getManager();

        if(!$user) return $this->json([
            'code' => 403,
            'message' => "Vous devez être connecté"
        ], 403);

        if($article->islikedByUser($user)){

            $like = $likeRepo->findOneBy([
                'article' => $article,
                'user' => $user
            ]);
            
            $manager->remove($like);
            $manager->flush();

            return $this->json([
                'code' => 200,
                'message' => 'Like bien supprimé',
                'isLiked' => false,
                'likes' => $likeRepo->count(['article' => $article])
            ], 200);

        }

        $like    = new Liker();
        $like    ->setArticle($article)
                 ->setUser($user);
    
        $manager ->persist($like);
        $manager ->flush();

        // $message = (new \Swift_Message('Article liké'))
        //     ->setFrom('generation.boomerang2020@gmail.com')
        //     ->setTo($article->getAuthor()->getEmail())
        //     ->setBody(
        //         $this->renderView(
        //             'notification_like.html.twig', ['titreArticle' => $article->getTitreArticle()]
        //         ),
        //         'text/html'
        //     );
        // $mailer->send($message);

        // $update = new Update(
        //     'http://example.com/article/'.$id.'/like',
        //     json_encode(['status' => 'article liké'])
        // );

        // // Sync, or async (RabbitMQ, Kafka...)
        // $bus->dispatch($update);

        return $this->json([
            'code' => 200, 
            'message' => 'Like bien ajouté',
            'isLiked' => true,
            'likes' => $likeRepo->count(['article' => $article])
        ], 200);
    }

    // /**
    //  * @Route("/image/{id}/remove", name="image_remove")
    //  * 
    //  */ 
    //  public function removeImg(Image $image,int $id) : Response {

    //     $entityManager = $this->getDoctrine()->getManager();
    //     $entityManager->remove($image);
    //     $entityManager->flush();

    //     return $this->json([
    //         'code' => 200, 
    //         'message' => 'image bien supprimé',
    //         'id' => $id
    //     ], 200);
    //  }
}
