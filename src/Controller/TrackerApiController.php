<?php

namespace Demoniqus\TrackerBundle\Controller;

use Demoniqus\TrackerBundle\Dto\TrackerApiDtoInterface;
use Demoniqus\TrackerBundle\Exception\TrackerCannotBeSavedException;
use Demoniqus\TrackerBundle\Exception\TrackerInvalidException;
use Demoniqus\TrackerBundle\Exception\TrackerNotFoundException;
use Demoniqus\TrackerBundle\Manager\CommandManagerInterface;
use Demoniqus\TrackerBundle\Manager\QueryManagerInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Evrinoma\DtoBundle\Factory\FactoryDtoInterface;
use Evrinoma\UtilsBundle\Controller\AbstractApiController;
use Evrinoma\UtilsBundle\Controller\ApiControllerInterface;
use Evrinoma\UtilsBundle\Rest\RestInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\Serializer\SerializerInterface;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class TrackerApiController extends AbstractApiController implements ApiControllerInterface
{
    private string $dtoClass;
    /**
     * @var ?Request
     */
    private ?Request $request;
    /**
     * @var QueryManagerInterface|RestInterface
     */
    private QueryManagerInterface $queryManager;
    /**
     * @var CommandManagerInterface|RestInterface
     */
    private CommandManagerInterface $commandManager;
    /**
     * @var FactoryDtoInterface
     */
    private FactoryDtoInterface $factoryDto;

    public function __construct(
        SerializerInterface     $serializer,
        RequestStack            $requestStack,
        FactoryDtoInterface     $factoryDto,
        CommandManagerInterface $commandManager,
        QueryManagerInterface   $queryManager,
        string                  $dtoClass
    ) {
        parent::__construct($serializer);
        $this->request = $requestStack->getCurrentRequest();
        $this->factoryDto = $factoryDto;
        $this->commandManager = $commandManager;
        $this->queryManager = $queryManager;
        $this->dtoClass = $dtoClass;
    }

    /**
     * @Rest\Post("/api/tracker/create", options={"expose"=true}, name="api_tracker_create")
     * @OA\Post(
     *     tags={"tracker"},
     *     description="the method perform create tracker",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *               example={
     *                  "class": "Demoniqus\TrackerBundle\Dto\TrackerApiDto",
     *                  "track":"some track",
     *                  },
     *               type="object",
     *               @OA\Property(property="class",type="string",default="Demoniqus\TrackerBundle\Dto\TrackerApiDto"),
     *               @OA\Property(property="track",type="string"),
     *               @OA\Property(property="type",type="string"),
     *            )
     *         )
     *     )
     * )
     * @OA\Response(response=200,description="Create tracker")
     *
     * @return JsonResponse
     */
    public function postAction(): JsonResponse
    {
        /** @var TrackerApiDtoInterface $trackApiDto */
        $trackApiDto = $this->factoryDto->setRequest($this->request)->createDto($this->dtoClass);
        $commandManager = $this->commandManager;

        $this->commandManager->setRestCreated();
        try {
            $json = [];
            $em = $this->getDoctrine()->getManager();

            $em->transactional(
                function () use ($trackApiDto, $commandManager, &$json) {
                    $json = $commandManager->post($trackApiDto);
                }
            );
        } catch (\Exception $e) {
            $json = $this->setRestStatus($this->commandManager, $e);
        }

        return $this->setSerializeGroup('api_post_tracker')->json(['message' => 'Create track', 'data' => $json], $this->commandManager->getRestStatus());
    }

    /**
     * @Rest\Put("/api/tracker/save", options={"expose"=true}, name="api_tracker_save")
     * @OA\Put(
     *     tags={"tracker"},
     *     description="the method perform save tracker for current entity",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *               example={
     *                  "class":"Demoniqus\TrackerBundle\Dto\TrackerApiDto",
     *                  "id":"1",
     *                  "active": "a",
     *                  "track":"Some track identity",
     *                  },
     *               type="object",
     *               @OA\Property(property="class",type="string",default="Demoniqus\TrackerBundle\Dto\TrackerApiDto"),
     *               @OA\Property(property="id",type="string"),
     *               @OA\Property(property="track",type="string"),
     *               @OA\Property(property="active",type="string")
     *            )
     *         )
     *     )
     * )
     * @OA\Response(response=200,description="Save code")
     *
     * @return JsonResponse
     */
    public function putAction(): JsonResponse
    {
        /** @var TrackerApiDtoInterface $trackApiDto */
        $trackApiDto = $this->factoryDto->setRequest($this->request)->createDto($this->dtoClass);
        $commandManager = $this->commandManager;

        try {
            if ($trackApiDto->hasId()) {
                $json = [];
                $em   = $this->getDoctrine()->getManager();

                $em->transactional(
                    function () use ($trackApiDto, $commandManager, &$json) {
                        $json = $commandManager->put($trackApiDto);
                    }
                );
            } else {
                throw new TrackerInvalidException('The Dto has\'t ID or class invalid');
            }
        } catch (\Exception $e) {
            $json = $this->setRestStatus($this->commandManager, $e);
        }

        return $this->setSerializeGroup('api_put_tracker')->json(['message' => 'Save tracker', 'data' => $json], $this->commandManager->getRestStatus());
    }

    /**
     * @Rest\Delete("/api/tracker/delete", options={"expose"=true}, name="api_tracker_delete")
     * @OA\Delete(
     *     tags={"tracker"},
     *     @OA\Parameter(
     *         description="class",
     *         in="query",
     *         name="class",
     *         required=true,
     *         @OA\Schema(
     *           type="string",
     *           default="Demoniqus\TrackerBundle\Dto\TrackerApiDto",
     *           readOnly=true
     *         )
     *     ),
     *      @OA\Parameter(
     *         description="id Entity",
     *         in="query",
     *         name="id",
     *         required=true,
     *         @OA\Schema(
     *           type="string",
     *           default="3",
     *         )
     *     )
     * )
     * @OA\Response(response=200,description="Delete tracker")
     *
     * @return JsonResponse
     */
    public function deleteAction(): JsonResponse
    {
        /** @var TrackerApiDtoInterface $trackerApiDto */
        $trackerApiDto = $this->factoryDto->setRequest($this->request)->createDto($this->dtoClass);

        $commandManager = $this->commandManager;
        $this->commandManager->setRestAccepted();

        try {
            if ($trackerApiDto->hasId()) {
                $json = [];
                $em = $this->getDoctrine()->getManager();

                $em->transactional(
                    function () use ($trackerApiDto, $commandManager, &$json) {
                        $commandManager->delete($trackerApiDto);
                        $json = ['OK'];
                    }
                );
            }
            else {
                throw new TrackerInvalidException('The Dto has\'t ID or class invalid');
            }
        } catch (\Exception $e) {
            $json = $this->setRestStatus($this->commandManager, $e);
        }

        return $this->json(['message' => 'Delete tracker', 'data' => $json], $this->commandManager->getRestStatus());
    }

    /**
     * @Rest\Get("/api/tracker/criteria", options={"expose"=true}, name="api_tracker_criteria")
     * @OA\Get(
     *     tags={"tracker"},
     *     @OA\Parameter(
     *         description="class",
     *         in="query",
     *         name="class",
     *         required=true,
     *         @OA\Schema(
     *           type="string",
     *           default="Demoniqus\TrackerBundle\Dto\TrackerApiDto",
     *           readOnly=true
     *         )
     *     ),
     *      @OA\Parameter(
     *         description="id Entity",
     *         in="query",
     *         name="id",
     *         @OA\Schema(
     *           type="string",
     *         )
     *     ),
     *      @OA\Parameter(
     *         description="track",
     *         in="query",
     *         name="track",
     *         @OA\Schema(
     *           type="string",
     *         )
     *     ),
     * )
     * @OA\Response(response=200,description="Return tracker")
     *
     * @return JsonResponse
     */
    public function criteriaAction(): JsonResponse
    {
        /** @var TrackerApiDtoInterface $trackerApiDto */
        $trackerApiDto = $this->factoryDto->setRequest($this->request)->createDto($this->dtoClass);

        try {
            $json = $this->queryManager->criteria($trackerApiDto);
        } catch (\Exception $e) {
            $json = $this->setRestStatus($this->queryManager, $e);
        }

        return $this->setSerializeGroup('api_get_tracker')->json(['message' => 'Get tracker', 'data' => $json], $this->queryManager->getRestStatus());
    }

    /**
     * @Rest\Get("/api/tracker", options={"expose"=true}, name="api_tracker")
     * @OA\Get(
     *     tags={"tracker"},
     *     @OA\Parameter(
     *         description="class",
     *         in="query",
     *         name="class",
     *         required=true,
     *         @OA\Schema(
     *           type="string",
     *           default="Demoniqus\TrackerBundle\Dto\TrackerApiDto",
     *           readOnly=true
     *         )
     *     ),
     *      @OA\Parameter(
     *         description="id Entity",
     *         in="query",
     *         name="id",
     *         required=true,
     *         @OA\Schema(
     *           type="string",
     *           default="3",
     *         )
     *     )
     * )
     * @OA\Response(response=200,description="Return track")
     *
     * @return JsonResponse
     */
    public function getAction(): JsonResponse
    {
        /** @var TrackerApiDtoInterface $trackerApiDto */
        $trackerApiDto = $this->factoryDto->setRequest($this->request)->createDto($this->dtoClass);

        try {
            $json = $this->queryManager->get($trackerApiDto);
        } catch (\Exception $e) {
            $json = $this->setRestStatus($this->queryManager, $e);
        }

        return $this->setSerializeGroup('api_get_tracker')->json(['message' => 'Get track', 'data' => $json], $this->queryManager->getRestStatus());
    }

    public function setRestStatus(RestInterface $manager, \Exception $e): array
    {
        switch (true) {
            case $e instanceof TrackerCannotBeSavedException:
                $manager->setRestNotImplemented();
                break;
            case $e instanceof UniqueConstraintViolationException:
                $manager->setRestConflict();
                break;
            case $e instanceof TrackerNotFoundException:
                $manager->setRestNotFound();
                break;
            case $e instanceof TrackerInvalidException:
                $manager->setRestUnprocessableEntity();
                break;
            default:
                $manager->setRestBadRequest();
        }

        return ['errors' => $e->getMessage()];
    }
}