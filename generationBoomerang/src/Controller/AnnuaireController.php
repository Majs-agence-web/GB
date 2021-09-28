<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Secteur;
use App\Entity\Annuaire;
use App\Entity\Formation;
use App\Entity\Metier;
use App\Form\AnnuaireType;
use App\Form\FormulaireType;
use App\Entity\UserFormation;
use App\Entity\FonctionMetier;
use App\Entity\SecteurActivite;
use App\Repository\UserRepository;
use App\Repository\AnnuaireRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


/**
 * @Route("/annuaire")
 */
class AnnuaireController extends AbstractController
{
    /**
     * @Route("/", name="annuaire")
     */
    public function index(AnnuaireRepository $AnnuRepo, AnnuaireRepository $annuRepo,Request $request, PaginatorInterface $paginator)
    {
        if ($request->getMethod() == "POST")
        {
            $donnees = $annuRepo->search($request->request->all());

            if ($donnees == null) {
                $this->addFlash('erreur', 'Aucun membre de ce nom a été trouvé.');
           
            }

        }else{
            $donnees = $AnnuRepo->findall();
        }

        
        $annuaires = $paginator->paginate($donnees, $request->query->getInt('page', 1), 12 );

        return $this->render('annuaire/index.html.twig', [
            'annuaires' => $annuaires,
        ]);
    }

    /**
     * @Route("/recherche", name="annuaireRecherche")
     */
    public function annuaireRecherche(AnnuaireRepository $annuRepo, Request $request,PaginatorInterface $paginator)
    {
        $userForm = new UserFormation();

        if ($request->getMethod() == "POST")
        {
            $donnees = $annuRepo->searchAdvanced($request->request->all());

            if ($donnees == null) {
                $this->addFlash('erreur', 'Aucun membre de ce nom a été trouvé.');
           
            }
            
            $annuaires = $paginator->paginate($donnees, $request->query->getInt('page', 1), 12 );

            return $this->render('annuaire/index.html.twig', [
                'annuaires' => $annuaires,
            ]);
            

        }

        $secteurs= $this->getDoctrine()->getRepository(Secteur::class)->findAll();
        $secteurActivites= $this->getDoctrine()->getRepository(SecteurActivite::class)->findAll();
        $fonctionMetiers= $this->getDoctrine()->getRepository(FonctionMetier::class)->findAll();

        
        return $this->render('annuaire/searchForm.html.twig', [
            'secteurs' => $secteurs,
            'secteurActivites' => $secteurActivites,
            'fonctionMetiers' => $fonctionMetiers,
        ]);
    }

    /**
     * @Route("/formulaire", name="formulaireAnnuaire")
     */

     public function inscriptionAnnuaire(Request $request):Response {

        $userAnnuaire = new Annuaire();
        $userForm = new UserFormation();

        $secteurs= $this->getDoctrine()->getRepository(Secteur::class)->findAll();
        $fonctionMetiers= $this->getDoctrine()->getRepository(FonctionMetier::class)->findAll();

        $form= $this->createForm(AnnuaireType::class, $userAnnuaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {
            $metier2= $request->request->get('fonction');
            $metier= $this->getDoctrine()->getRepository(Metier::class)->findOneBy([
                'nomMetier' => $metier2
            ]);
              
            $intitule= $request->request->get('formationValue');
            $promo= $request->request->get('promo');
            $formation= $this->getDoctrine()->getRepository(Formation::class)->findOneBy([
                'intitule' => $intitule
            ]);

            $userForm->setFormation($formation)
                     ->setanneePromo($promo);
            $userAnnuaire->addUserFormation($userForm);
            $user= $this->getUser();
            $userAnnuaire->setUser($user);
            $userAnnuaire->setFonction($metier);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($userForm);
            $entityManager->flush();

            return $this->redirectToRoute('annuaire');
        }



        return $this->render('annuaire/InscriptionAnnuaire.html.twig', [
            'form' => $form->createView(),
            'secteurs' => $secteurs,
            'fonctionMetiers' => $fonctionMetiers,
         
        ]);
     }

     /**
      * @Route("/profil/{firstName}_{lastName}", name="profilAnnuaire")
      * @ParamConverter("User", options={"mapping": {"firstName": "firstName", "lastName": "lastName"}})
      */
      public function profilAnnuaire(User $user):Response {

        $annuaire= $this->getDoctrine()->getRepository(Annuaire::class)->findOneBy(['user' => $user]);

        return $this->render('annuaire/profilAnnuaire.html.twig', [
            'annuaire' => $annuaire
        ]);

      }
}
