<?php

namespace App\Controller;

use App\Entity\PasswordUpdate;
use App\Entity\User;
use App\Form\AccountType;
use App\Form\PasswordUpdateType;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AccountController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    protected $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param AuthenticationUtils $utils
     * @return Response
     */
    public function login(AuthenticationUtils $utils): Response
    {
        $error    = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();

        return $this->render('account/login.html.twig', [
            'hasError' => $error !== null,
            'username' => $username
        ]);
    }

    /**
     * return void
     */
    public function logout(): void
    {

    }

    /**
     * @param Request                      $request
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     */
    public function register(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($user, $user->getHash());
            $user->setHash($hash);

            $this->manager->persist($user);
            $this->manager->flush();

            $this->addFlash('success','Inscription effectuée');

            return $this->redirectToRoute('account_login');
        }

        return $this->render('account/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     *
     * @param Request $request
     * @return Response
     */
    public function profile(Request $request): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(AccountType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
//            $this->manager->persist($user); // On utilise persist pour une entité qui n'existe pas. Donc ici pas utile
            $this->manager->flush();

            $this->addFlash('success','Modification(s) effectuée(s)');
        }

        return $this->render('account/profile.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     *
     * @param Request                      $request
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     */
    public function updatePassword(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $user           = $this->getUser();
        $passwordUpdate = new PasswordUpdate();
        $form           = $this->createForm(PasswordUpdateType::class, $passwordUpdate);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$encoder->isPasswordValid($user, $passwordUpdate->getOldPassword())) {
                $form->get('oldPassword')->addError(new FormError('Le mot de passe est incorrect'));
            } else {
                $user->setHash($encoder->encodePassword($user, $passwordUpdate->getNewPassword()));
                $this->manager->flush();
                $this->addFlash('success','Modification mot de passe effectuée');
                return $this->redirectToRoute('home');
            }
        }

        return $this->render('account/password.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     *
     * @return Response
     */
    public function myAccount(): Response
    {
        return $this->render('user/index.html.twig', [
            'user' => $this->getUser()
        ]);
    }
}
