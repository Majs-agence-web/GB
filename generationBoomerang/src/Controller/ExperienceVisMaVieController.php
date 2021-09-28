<?php

namespace App\Controller;

use App\Entity\Metier;
use App\Entity\Secteur;
use App\Entity\Temoignage;
use Cocur\Slugify\Slugify;
use App\Form\TemoignageType;
use Knp\Component\Pager\PaginatorInterface;
use App\Entity\OffreVisMaVie;
use App\Entity\FonctionMetier;
use App\Entity\SecteurActivite;
use App\Form\OffreVisMaVieType;
use App\Entity\ExperienceVisMaVie;
use App\Form\ExperienceVisMaVie1Type;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ExperienceVisMaVieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


/**
 * @Route("/experienceVisMaVie")
 */
class ExperienceVisMaVieController extends AbstractController
{
    /**
     * @Route("/", name="experience_vis_ma_vie_index")
     */
    public function index(ExperienceVisMaVieRepository $experienceVisMaVieRepository, ExperienceVisMaVieRepository $ExperienceVisMaVieRepository,Request $request, PaginatorInterface $paginator)
    {

        if ($request->getMethod() == "POST")
        {
            $donnees = $experienceVisMaVieRepository->search($request->request->get('villeEntreprise'));

            if ($donnees == null) {
                $this->addFlash('erreur', 'Pas d\'annonce pour la ville selectionnée.');
               
            }

        }else{
            $donnees = $ExperienceVisMaVieRepository->findall();
        }

        $experienceVisMaVieRepository = $paginator->paginate($donnees, $request->query->getInt('page', 1), 12 );

        return $this->render('experience_vis_ma_vie/index.html.twig', [
            'experience_vis_ma_vies' => $experienceVisMaVieRepository,
        ]);
    }

    /**
     * @Route("/presentation", name="experience_vis_ma_vie_presentation", methods={"GET"})
     */
    public function presentation(): Response
    {
        return $this->render('experience_vis_ma_vie/presentation.html.twig', [
            
        ]);
    }


    /**
     * @Route("/new", name="experience_vis_ma_vie_new", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function new(Request $request): Response
    {
        $experienceVisMaVie = new ExperienceVisMaVie();
        

        $secteurs= $this->getDoctrine()->getRepository(Secteur::class)->findAll();
        $secteurActivites= $this->getDoctrine()->getRepository(SecteurActivite::class)->findAll();
        $fonctionMetiers= $this->getDoctrine()->getRepository(FonctionMetier::class)->findAll();
        $user= $this->getUser();
        
        $form = $this->createForm(ExperienceVisMaVie1Type::class, $experienceVisMaVie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slugify = new Slugify();
            $experienceVisMaVie->setSlug($slugify->slugify($experienceVisMaVie->getTitreAnnonce()));
            $experienceVisMaVie->setUser($user);
            $metier= $request->request->get('formationValue3');
            $profession= $this->getDoctrine()->getRepository(Metier::class)->findOneBy([
                'nomMetier' => $metier
            ]);
            $experienceVisMaVie->setMetier($profession);
            $experienceVisMaVie ->setCreatedAt(new \DateTime('now'));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($experienceVisMaVie);
            $entityManager->flush();

            return $this->redirectToRoute('experience_vis_ma_vie_index');
        }

        return $this->render('experience_vis_ma_vie/new.html.twig', [
            'experience_vis_ma_vie' => $experienceVisMaVie,
            'form' => $form->createView(),
            'secteurs' => $secteurs,
            'secteurActivites' => $secteurActivites,
            'fonctionMetiers' => $fonctionMetiers,
        ]);
    }

    /**
     * @Route("/{slug}", name="experience_vis_ma_vie_show", methods={"GET"})
     */
    public function show($slug, ExperienceVisMaVie $experienceVisMaVie): Response
    {
        $experienceVisMaVie= $this->getDoctrine()->getRepository(ExperienceVisMaVie::class)->findOneBy(['slug' => $slug]);

        // return $this->redirectToRoute('experience_vis_ma_vie_index',[
        //     'slug' => $offre->getSlug()
        // ]);

        return $this->render('experience_vis_ma_vie/show.html.twig', [
            'experience_vis_ma_vie' => $experienceVisMaVie,
        ]);
    }

    /**
     * @Route("/{slug}/edit", name="experience_vis_ma_vie_edit", methods={"GET","POST"})
     * @param ExperienceVisMaVie $experienceVisMaVie
     * @return Response
     */
    public function edit(Request $request, ExperienceVisMaVie $experienceVisMaVie): Response
    {
        $secteurs= $this->getDoctrine()->getRepository(Secteur::class)->findAll();
        $secteurActivites= $this->getDoctrine()->getRepository(SecteurActivite::class)->findAll();
        $fonctionMetiers= $this->getDoctrine()->getRepository(FonctionMetier::class)->findAll();
        $form = $this->createForm(ExperienceVisMaVie1Type::class, $experienceVisMaVie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $slugify = new Slugify();
            $experienceVisMaVie->setSlug($slugify->slugify($experienceVisMaVie->getTitreAnnonce()));
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('experience_vis_ma_vie_index');
        }

        return $this->render('experience_vis_ma_vie/edit.html.twig', [
            'experience_vis_ma_vie' => $experienceVisMaVie,
            'form' => $form->createView(),
            'secteurs' => $secteurs,
            'secteurActivites' => $secteurActivites,
            'fonctionMetiers' => $fonctionMetiers,
        ]);
    }

    /**
     * @Route("/{slug}", name="experience_vis_ma_vie_delete", methods={"DELETE"})
     */
    public function delete(Request $request, ExperienceVisMaVie $experienceVisMaVie): Response
    {
        // if ($this->isCsrfTokenValid('delete'.$experienceVisMaVie->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($experienceVisMaVie);
            $entityManager->flush();
        // }

        return $this->redirectToRoute('experience_vis_ma_vie_index');
    }

    /**
     * @Route("/{slug}/postule", name="postule_experience")
     * @IsGranted("ROLE_USER")
     */

    public function postuleVisMaVie(Request $request, EntityManagerInterface $manager, ExperienceVisMaVie $experienceVisMaVie):Response 
    { 

        $userStagiaire = new OffreVisMaVie();

        $user          = $this->getUser();
        $userStagiaire ->setUser($user);
        $form          = $this->createForm(OffreVisMaVieType::class, $userStagiaire);
        $form          ->handleRequest($request);
        dump($request->request->get('offreVisMaVie'));
        if($form->isSubmitted() && $form->isValid()){
            // $cvFile = $form->get('UploadCV')->getData();

            // // this condition is needed because the 'brochure' field is not required
            // // so the PDF file must be processed only when a file is uploaded
            // if ($cvFile) {
            //     $originalFilename = pathinfo($cvFile->getClientOriginalName(), PATHINFO_FILENAME);
            //     // this is needed to safely include the file name as part of the URL
            //     $safeFilename = $slugger->slug($originalFilename);
            //     $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

            //     // Move the file to the directory where brochures are stored
            //     try {
            //         $brochureFile->move(
            //             $this->getParameter('brochures_directory'),
            //             $newFilename
            //         );
            //     } catch (FileException $e) {
            //         // ... handle exception if something happens during file upload
            //     }

            //     // updates the 'brochureFilename' property to store the PDF file name
            //     // instead of its contents
            //     $product->setBrochureFilename($newFilename);
           
            $userStagiaire->setVisMaVie($experienceVisMaVie) ;                           
            $manager->persist($userStagiaire);
            $filename = $userStagiaire->getUploadCV();
            $userStagiaire->setUploadCV("/CV/users/".$filename) ;
            $manager->persist($userStagiaire);
            $manager->flush();
            return $this->redirectToRoute('experience_vis_ma_vie_index');
        }
        return $this->render('experience_vis_ma_vie/PostulerVisMaVie.html.twig', [
            'form' =>$form->createView(),
            'experienceVisMaVie' => $experienceVisMaVie
        ]);

    }

    // /**
    //  * @Route("/temoignages", name="temoignage_experience")
    //  */

    // public function temoignage(Request $request, EntityManagerInterface $manager):Response
    // {
    //     $temoignage= new Temoignage;
    //     $form= $this->createForm(TemoignageType::class, $temoignage);

    //     $form->handleRequest($request);
    //     if($form->isSubmitted() && $form->isValid()){

    //         $manager->persist($temoignage);
    //         $manager->flush();

    //     }
    //     return $this->render('experience_vis_ma_vie/Temoignage.html.twig', [
    //         'form' =>$form->createView()
    //     ]);



    // }
}

