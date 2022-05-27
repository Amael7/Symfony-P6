<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserFormType;
use Symfony\Component\Mime\Address;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\DuplicateKeyException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
	
	#[Route('/profil', name: 'user')]
	public function show(): Response
	{
		$user = $this->getUser();

		return $this->render('user/show.html.twig', [
			'user' => $user,
		]);
	}

	#[Route('/profil/edit', name: 'user_edit')]
	public function edit(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
	{
		$userConnected = $this->getUser();
		$user = $entityManager->getRepository(User::class)->findOneBy(['username' => $userConnected->getUserIdentifier()]);
		$form = $this->createForm(UserFormType::class, $user);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$photo = $form->get('photo')->getData();
			if ($photo != null) {
				if ($photo == "default-profil.png") {
					$fileName = $photo;
				} else {
					// On génère un nouveau nom de fichier
					$fileName = md5(uniqid()) . '.' . $photo->guessExtension();
					// on va copier le fichier dans le dossier uploads
					$photo->move(
							$this->getParameter('images_directory'),
							$fileName
					);
				}
				$user->setPhoto($fileName);
			}
			$entityManager->persist($user);
			$entityManager->flush();
			$this->addFlash(
					'success',
					'Votre Compte à bien été mis à jour !'
			);
			return $this->redirectToRoute('user');
		}

		return $this->render('user/edit.html.twig', [
			'user' => $user,
			'userForm' => $form->createView(),
		]);
	}
}
