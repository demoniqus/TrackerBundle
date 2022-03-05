<?php

namespace Demoniqus\UidBundle\Controller;

use Demoniqus\UidBundle\Dto\UidApiDtoInterface;
use Demoniqus\UidBundle\Exception\UidCannotBeSavedException;
use Demoniqus\UidBundle\Exception\UidInvalidException;
use Demoniqus\UidBundle\Exception\UidNotFoundException;
use Demoniqus\UidBundle\Manager\CommandManagerInterface;
use Demoniqus\UidBundle\Manager\QueryManagerInterface;
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

final class UidApiController extends AbstractApiController implements ApiControllerInterface
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
     * @Rest\Post("/api/uid/create", options={"expose"=true}, name="api_uid_create")
     * @OA\Post(
     *     tags={"uid"},
     *     description="the method perform create uid",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *               example={
     *                  "class": "Evrinoma\UidBundle\Dto\UidApiDto",
     *                  "uid":"some uid",
     *                  },
     *               type="object",
     *               @OA\Property(property="class",type="string",default="Evrinoma\UidBundle\Dto\UidApiDto"),
     *               @OA\Property(property="uid",type="string"),
     *               @OA\Property(property="type",type="string"),
     *            )
     *         )
     *     )
     * )
     * @OA\Response(response=200,description="Create uid")
     *
     * @return JsonResponse
     */
    public function postAction(): JsonResponse
    {
        /** @var UidApiDtoInterface $uidApiDto */
        $uidApiDto = $this->factoryDto->setRequest($this->request)->createDto($this->dtoClass);
        $commandManager = $this->commandManager;

        $this->commandManager->setRestCreated();
        try {
            $json = [];
            $em = $this->getDoctrine()->getManager();

            $em->transactional(
                function () use ($uidApiDto, $commandManager, &$json) {
                    $json = $commandManager->post($uidApiDto);
                }
            );
        } catch (\Exception $e) {
            $json = $this->setRestStatus($this->commandManager, $e);
        }

        return $this->setSerializeGroup('api_post_uid')->json(['message' => 'Create uid', 'data' => $json], $this->commandManager->getRestStatus());
    }

    /**
     * @Rest\Put("/api/uid/save", options={"expose"=true}, name="api_uid_save")
     * @OA\Put(
     *     tags={"uid"},
     *     description="the method perform save uid for current entity",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *               example={
     *                  "class":"Evrinoma\UidBundle\Dto\UidApiDto",
     *                  "id":"1",
     *                  "active": "a",
     *                  "uid":"Some uid identity",
     *                  },
     *               type="object",
     *               @OA\Property(property="class",type="string",default="Evrinoma\UidBundle\Dto\UidApiDto"),
     *               @OA\Property(property="id",type="string"),
     *               @OA\Property(property="uid",type="string"),
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
        /** @var UidApiDtoInterface $uidApiDto */
        $uidApiDto = $this->factoryDto->setRequest($this->request)->createDto($this->dtoClass);
        $commandManager = $this->commandManager;

        try {
            if ($uidApiDto->hasId()) {
                $json = [];
                $em   = $this->getDoctrine()->getManager();

                $em->transactional(
                    function () use ($uidApiDto, $commandManager, &$json) {
                        $json = $commandManager->put($uidApiDto);
                    }
                );
            } else {
                throw new UidInvalidException('The Dto has\'t ID or class invalid');
            }
        } catch (\Exception $e) {
            $json = $this->setRestStatus($this->commandManager, $e);
        }

        return $this->setSerializeGroup('api_put_uid')->json(['message' => 'Save uid', 'data' => $json], $this->commandManager->getRestStatus());
    }

    /**
     * @Rest\Delete("/api/uid/delete", options={"expose"=true}, name="api_uid_delete")
     * @OA\Delete(
     *     tags={"uid"},
     *     @OA\Parameter(
     *         description="class",
     *         in="query",
     *         name="class",
     *         required=true,
     *         @OA\Schema(
     *           type="string",
     *           default="Evrinoma\UidBundle\Dto\UidApiDto",
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
     * @OA\Response(response=200,description="Delete uid")
     *
     * @return JsonResponse
     */
    public function deleteAction(): JsonResponse
    {
        /** @var UidApiDtoInterface $UidApiDto */
        $UidApiDto = $this->factoryDto->setRequest($this->request)->createDto($this->dtoClass);

        $commandManager = $this->commandManager;
        $this->commandManager->setRestAccepted();

        try {
            if ($UidApiDto->hasId()) {
                $json = [];
                $em = $this->getDoctrine()->getManager();

                $em->transactional(
                    function () use ($UidApiDto, $commandManager, &$json) {
                        $commandManager->delete($UidApiDto);
                        $json = ['OK'];
                    }
                );
            }
            else {
                throw new UidInvalidException('The Dto has\'t ID or class invalid');
            }
        } catch (\Exception $e) {
            $json = $this->setRestStatus($this->commandManager, $e);
        }

        return $this->json(['message' => 'Delete uid', 'data' => $json], $this->commandManager->getRestStatus());
    }

    /**
     * @Rest\Get("/api/uid/criteria", options={"expose"=true}, name="api_uid_criteria")
     * @OA\Get(
     *     tags={"uid"},
     *     @OA\Parameter(
     *         description="class",
     *         in="query",
     *         name="class",
     *         required=true,
     *         @OA\Schema(
     *           type="string",
     *           default="Evrinoma\UidBundle\Dto\UidApiDto",
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
     *         description="uid",
     *         in="query",
     *         name="name",
     *         @OA\Schema(
     *           type="string",
     *         )
     *     ),
     * )
     * @OA\Response(response=200,description="Return uid")
     *
     * @return JsonResponse
     */
    public function criteriaAction(): JsonResponse
    {
        /** @var UidApiDtoInterface $UidApiDto */
        $UidApiDto = $this->factoryDto->setRequest($this->request)->createDto($this->dtoClass);

        try {
            $json = $this->queryManager->criteria($UidApiDto);
        } catch (\Exception $e) {
            $json = $this->setRestStatus($this->queryManager, $e);
        }

        return $this->setSerializeGroup('api_get_uid')->json(['message' => 'Get uid', 'data' => $json], $this->queryManager->getRestStatus());
    }

    /**
     * @Rest\Get("/api/uid", options={"expose"=true}, name="api_uid")
     * @OA\Get(
     *     tags={"uid"},
     *     @OA\Parameter(
     *         description="class",
     *         in="query",
     *         name="class",
     *         required=true,
     *         @OA\Schema(
     *           type="string",
     *           default="Evrinoma\UidBundle\Dto\UidApiDto",
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
     * @OA\Response(response=200,description="Return uid")
     *
     * @return JsonResponse
     */
    public function getAction(): JsonResponse
    {
        /** @var UidApiDtoInterface $UidApiDto */
        $UidApiDto = $this->factoryDto->setRequest($this->request)->createDto($this->dtoClass);

        try {
            $json = $this->queryManager->get($UidApiDto);
        } catch (\Exception $e) {
            $json = $this->setRestStatus($this->queryManager, $e);
        }

        return $this->setSerializeGroup('api_get_uid')->json(['message' => 'Get uid', 'data' => $json], $this->queryManager->getRestStatus());
    }

    public function setRestStatus(RestInterface $manager, \Exception $e): array
    {
        switch (true) {
            case $e instanceof UidCannotBeSavedException:
                $manager->setRestNotImplemented();
                break;
            case $e instanceof UniqueConstraintViolationException:
                $manager->setRestConflict();
                break;
            case $e instanceof UidNotFoundException:
                $manager->setRestNotFound();
                break;
            case $e instanceof UidInvalidException:
                $manager->setRestUnprocessableEntity();
                break;
            default:
                $manager->setRestBadRequest();
        }

        return ['errors' => $e->getMessage()];
    }
}