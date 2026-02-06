<?php

namespace App\Controller;

use App\Entity\{User, Movie, Director, Category};
use App\Repository\CategoryRepository;
use App\Repository\DirectorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\MovieRepository;
use App\Repository\UserRepository;

#[Route('/movie')]
final class MovieController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer,
        private EntityManagerInterface $em,
        private MovieRepository $mr,
        private DirectorRepository $dr,
        private UserRepository $ur,
        private CategoryRepository $cr
    ) {}

    #[Route('/all', name: 'app_movies', methods: ['GET'])]
    public function getAll(): JsonResponse
    {
        $movies = $this->mr->findAll();
        return $this->json($movies, context: [
            'groups' => ['movie-read']
        ]);
    }

    #[Route('/one/{id}', name: 'app_movie', methods: ['GET'])]
    public function getOne(int $id): JsonResponse
    {
        $movie = $this->mr->find($id);
        return $this->json($movie, context: [
            'groups' => ['movie-read']
        ]);
    }

    #[Route('/add', name: 'app_movie_add', methods: ['POST'])]
    public function addMovie(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $movie = $this->serializer->deserialize($request->getContent(), Movie::class, "json", context: ['groups' => ['movie-write']]);

        if (isset($data['user']['id'])) {
            $user = $this->em->getReference(User::class, $data['user']['id']);
            $movie->setUser($user);
        }

        if (isset($data['directors'])) {
            foreach ($data['directors'] as $dirData) {
                $director = $this->em->getReference(Director::class, $dirData['id']);
                $movie->addDirector($director);
            }
        }

        if (isset($data['categories'])) {
            foreach ($data['categories'] as $catData) {
                $category = $this->em->getReference(Category::class, $catData['id']);
                $movie->addCategory($category);
            }
        }

        $this->em->persist($movie);
        $this->em->flush();
        return $this->json(["message" => "Movie '" . $movie->getTitleMovie() . "' bien enregistré!"]);
    }

    #[Route('/update/{id}', name: 'app_movie_update', methods: ['PUT'])]
    public function updateMovie(Request $request, int $id): JsonResponse
    {
        $movie = $this->mr->find($id);

        if (!$movie) {
            return $this->json(['error' => 'Movie pas trouvé'], 404);
        }

        $data = json_decode($request->getContent(), true);

        $this->serializer->deserialize(
            $request->getContent(),
            Movie::class,
            'json',
            [
                'groups' => ['movie-write'],
                'object_to_populate' => $movie
            ]
        );

        if (!empty($data['user']['id'])) {
            $user = $this->em->getReference(User::class, $data['user']['id']);
            $movie->setUser($user);
        }

        if (isset($data['directors'])) {
            $movie->clearDirector();
            foreach ($data['directors'] as $dirData) {
                $director = $this->em->getReference(Director::class, $dirData['id']);
                if ($director) {
                    $movie->addDirector($director);
                }
            }
        }

        if (isset($data['categories'])) {
            $movie->clearCategory();
            foreach ($data['categories'] as $catData) {
                $category = $this->em->getReference(Category::class, $catData['id']);
                if ($category) {
                    $movie->addCategory($category);
                }
            }
        }

        $this->em->flush();

        return $this->json([
            "message" => "Movie '" . $movie->getTitleMovie() . "' bien mis à jour !"
        ]);
    }

    #[Route('/delete/{id}', name: 'app_movie_delete', methods: ['DELETE'])]
    public function deleteOne(int $id): JsonResponse
    {
        $movie = $this->mr->find($id);
        $this->em->remove($movie);
        $this->em->flush();
        return $this->json(["message" => "Movie '" . $movie->getTitleMovie() . "' bien effacé!"]);
    }
}
