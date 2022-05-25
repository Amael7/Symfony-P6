<?php

namespace App\Controller;

use App\Form\UserFormType;
use Symfony\Component\Mime\Address;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
		$user = $this->getUser();
		$form = $this->createForm(UserFormType::class, $user);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$entityManager->persist($user);
			$entityManager->flush();

			return $this->redirectToRoute('user');
		}

		return $this->render('user/edit.html.twig', [
			'user' => $user,
			'userForm' => $form->createView(),
		]);
	}
}
