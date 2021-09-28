<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Document;
use App\Entity\Abonnement;
use App\Form\InscriptionType;
use App\Repository\UserRepository;
use App\Form\UpdateInscriptionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class AccountController extends AbstractController
{
    /**
     * permet d'afficher le profil de l utilisateur connecté
     * @Route("/account", name="account")
     * @IsGranted("ROLE_USER")
     */
    public function myAccount(){
        return $this->render('account/index.html.twig',[
            'user' => $this->getUser()
        ]);
    }


    /**
     * permet d'afficher le formulaire d'incription
     * 
     * @Route("/inscription", name="account_register")
     *
     * @return Response
     */
    public function register(Request $request, EntityManagerInterface $manager,UserPasswordEncoderInterface $encoder, \Swift_Mailer $mailer) : Response
    {
        $user= new User();
                      

        $form= $this->createForm(InscriptionType::class, $user);

        $form->handleRequest($request);
        

        if($form->isSubmitted() && $form->isValid()){

            $documents = $form->get("documents")->getData();
            foreach( $documents as $document){
                $doc = new Document();
                $doc->setDocFile($document)
                     ->setType('Justificatif') ;                  
                     
                $user->addDocument($doc);
                
            }
            
            // $abonnement = $this->getDoctrine()->getRepository(Abonnement::class)->find(1);
            $hash= $encoder->encodePassword($user, $user->getPassword());
            $user->setActivationToken(md5(uniqid()));
            $user->setPassword($hash)
                //   ->setAbonnement($abonnement)
                  ->setCompte('À valider');
             
            $manager->persist($user); 
                if($user->getDocuments()->first() != null){
                    $filename = $user->getDocuments()->first()->getFileName();
                    $user->getDocuments()->first()->setFileName('/docs/users/'.$filename);
                    $manager->persist($user); 
                }
                $manager->flush();
            
            // $message = (new \Swift_Message('Nouveau compte'))
            // // On attribue l'expéditeur
            // ->setFrom('generation.boomerang2020@gmail.com')
            // // On attribue le destinataire
            // ->setTo($user->getEmail())
            // // On crée le texte avec la vue
            // ->setBody(
            //     $this->renderView(
            //         'emails/activation.html.twig', ['token' => $user->getActivationToken()]
            //     ),
            //     'text/html'
            // );
            // $mailer->send($message);
            $this->addFlash(
                'success',
                "Votre compte a bien été crée! En attente de validation par un administrateur."
            );
            
            // return $this->redirectToRoute('app_login');
            return $this->redirectToRoute('choix_abonnement');

        }

        return $this->render('account/inscription.html.twig', [
            'form' =>$form->createView()

        ]);
    }

    /**
    * @Route("/activation/{token}", name="activation")
    */
    public function activation($token, UserRepository $user)
    {
        // On recherche si un utilisateur avec ce token existe dans la base de données
        $user = $user->findOneBy(['activation_token' => $token]);

        // Si aucun utilisateur n'est associé à ce token
        if(!$user){
            // On renvoie une erreur 404
            throw $this->createNotFoundException('Cet utilisateur n\'existe pas');
        }

        // On supprime le token
        $user->setActivationToken("");
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        // On génère un message
        $this->addFlash('message', 'Utilisateur activé avec succès');

        // On retourne à l'accueil
        return $this->redirectToRoute('home');
    }

    /**
     * permet d'afficher et de modifier les infos du profil
     * @Route("/account/profile", name="account_profile")
     * @IsGranted("ROLE_USER")
     * @return Response
     */
    public function profile(Request $request,EntityManagerInterface $manager){
        $user = new User;
        $form =$this->createForm(UpdateInscriptionType::class,$user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($user);
            $manager->flush();


            $this->addFlash(
                'success',
                "les données du compte ont bien été modifié "
            );
        }

        return $this->render('account/profile.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * mon compte
     * @Route("/mon-compte/", name="mon_compte")
     * @IsGranted("ROLE_USER")
     */

   public function monCompte(Request $request,EntityManagerInterface $manager){
        $user = $this->getUser();

        $form =$this->createForm(UpdateInscriptionType::class,$user);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($user);
            $manager->flush();
        }
            return $this->render('account/monCompte.html.twig',[
                'form' => $form->createView()
            ]);
    }

     /**
     * permet d'afficher la gestion des newsletter 
     * @Route("/account/newsletter", name="gestion_newsletter")
     * @IsGranted("ROLE_USER")
     */
    public function newsletter(){
        return $this->render('account/gestionNewsletter.html.twig');
    }

    /**
     * permet d'afficher le formulaire d'abonnement
     * @Route("/account/abonnements", name="abonnements_index") 
     */
    public function abonnementsIndex(){
        return $this->render('account/abonnement/abonnement_index.html.twig',[
           
        ]);
    }
    
     /**
     * permet d'afficher le formulaire d'abonnement
     * @Route("/account/abonnement", name="choix_abonnement")
     *  
     */
    public function abonnement(){
        return $this->render('account/abonnement/abonnement_form.html.twig',[
            
        ]);
    }
}
