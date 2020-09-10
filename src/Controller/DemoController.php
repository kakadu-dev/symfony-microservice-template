<?php

namespace App\Controller;

//use App\Repository\DemoRepository;
//use App\Serializers\DemoSerializer;
//use App\Components\MicroserviceRequest;
//use App\Entity\Demo;
//use App\Helpers\MicroservicePaginator;
//use App\Helpers\Query\Symfony\SymfonyQuery;
//use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class DemoController
 * @package App\Controller
 */
class DemoController extends AbstractController
{
    //    /**
    //     * @var DemoRepository
    //     */
    //    private DemoRepository $repository;
    //
    //    /**
    //     * @var EntityManagerInterface
    //     */
    //    private EntityManagerInterface $manager;
    //
    //    /**
    //     * @var DemoSerializer
    //     */
    //    private DemoSerializer $serializer;
    //
    //    /**
    //     * CountriesController constructor.
    //     *
    //     * @param DemoRepository         $repository
    //     * @param EntityManagerInterface $manager
    //     * @param DemoSerializer         $serializer
    //     */
    //    public function __construct(
    //        EntityManagerInterface $manager
    //        DemoRepository $repository,
    //        DemoSerializer $serializer
    //    )
    //    {
    //        $this->manager = $manager;
    //        $this->repository = $repository;
    //        $this->serializer = $serializer;
    //    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function hello(Request $request): array
    {
        return $request->request->all();
    }

    //    /**
    //     * @param MicroserviceRequest   $request
    //     * @param MicroservicePaginator $paginator
    //     *
    //     * @return string
    //     * @throws Exception
    //     */
    //    public function index(
    //        MicroserviceRequest $request,
    //        MicroservicePaginator $paginator
    //    ): string
    //    {
    //        $query = SymfonyQuery::init(
    //            $request->request->all(),
    //            Demo::class,
    //            $this->manager
    //        );
    //
    //        $qb = $this->manager->createQueryBuilder();
    //
    //        $prepareCondition = $qb
    //            ->select($query->getAttributes())
    //            ->from(Demo::class, 'demo');
    //
    //        if ($query->getWhere()) {
    //            $prepareCondition->where($query->getWhere());
    //        }
    //
    //        foreach ($query->getWith() as $item) {
    //            $prepareCondition->join(
    //                $item['join'],
    //                $item['alias'],
    //                $item['conditionType'],
    //                $item['condition']
    //            );
    //        }
    //
    //        if (!empty($query->getParameters())) {
    //            $prepareCondition->setParameters($query->getParameters());
    //        }
    //
    //        if (!empty($query->getOrderBy())) {
    //            foreach ($query->getOrderBy() as $attribute => $sortType) {
    //                $prepareCondition->addOrderBy($attribute, $sortType);
    //            }
    //        }
    //
    //        return $this->serializer->serialize(
    //            $paginator
    //                ->setPage($query->getPage())
    //                ->setLimit($query->getPerPage())
    //                ->setAllPage($query->getAllPage())
    //                ->getData($prepareCondition)
    //        );
    //    }
}
