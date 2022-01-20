<?php

declare(strict_types=1);

use App\Entity\User;

require_once 'bootstrap.php';

[$filename,$username,$password] = $argv;

$user = new User();

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$user->setUsername($username)->setPassword($hashedPassword);
/** EntityManager $em */
$em = $entityManager;

$em->persist($user);
$em->flush();

echo 'Created a user with id ' . $user->getId() . PHP_EOL;