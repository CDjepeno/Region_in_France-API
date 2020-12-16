<?php

namespace App\Controller;

use App\Entity\Region;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiController extends AbstractController
{
    /**
     * @Route("/listeRegions", name="listeRegions")
     */
    public function listeRegion(SerializerInterface $serializer): Response
    {
        $encoders    = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer  = new Serializer($normalizers, $encoders);

        $mesRegions  = file_get_contents('https://geo.api.gouv.fr/regions');

        $mesRegionsTab = $serializer->decode($mesRegions,'json');
        
        // dd($mesRegionsTab);

        return $this->render('api/index.html.twig',[
            "mesRegions" => $mesRegionsTab
        ]);
    }
}
