<?php

namespace CTO\AppBundle\Controller\DashboardControllers\CTO;

use CTO\AppBundle\Entity\CtoUser;
use CTO\AppBundle\Entity\DTO\StatisticFilterDTO;
use CTO\AppBundle\Form\DTO\StatisticFilterDTOType;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class StatisticsController
 * @package CTO\AppBundle\Controller\DashboardControllers\CTO
 *
 * @Route("/statistics")
 */
class StatisticsController extends Controller
{
    /**
     * @param Request $request
     * @return array
     *
     * @Route("/", name="cto_statistics_filter")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function filterAction(Request $request)
    {
        /** @var CtoUser $ctoUser */
        $ctoUser = $this->getUser();
        $filterDTO = new StatisticFilterDTO();
        $form = $this->createForm(StatisticFilterDTOType::class, $filterDTO, ["cto" => $ctoUser]);
        $form->handleRequest($request);
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $jobs = $em->getRepository("CTOAppBundle:CarJob")->getStatisticsWithFilters($ctoUser, $filterDTO);

        return [
            "filterForm" => $form->createView(),
            "jobs" => $jobs
        ];
    }
}
