<?php

namespace App\Controller;

use App\Entity\Region;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ApiController extends AbstractController
{
    /**
     * Permet de lister les régions
     * 
     * @Route("/listeRegions", name="listeRegions")
     * 
     * @return Response
     */
    public function listeRegion(SerializerInterface $serializer): Response
    {
        $mesRegions    = file_get_contents('https://geo.api.gouv.fr/regions');
        
        $mesRegionsObj = $serializer->deserialize($mesRegions,'App\Entity\Region[]', 'json');

        return $this->render('api/index.html.twig',[
            "mesRegions" => $mesRegionsObj
        ]);
    }

    /**
     * Permet de récupérer la liste des département par régions
     * 
     * @Route("/regions/departements", name="list_dep_reg")
     *
     * @return Response
     */
    public function listeDeDepartementsParRegion(SerializerInterface $serializer, Request $request): Response
    {
        // Je récupère la région séléctionner dans le formulaire
        $codeRegion = $request->query->get('region');

        // Je récupère mes régions
        $mesRegions = file_get_contents('https://geo.api.gouv.fr/regions');
        $mesRegions = $serializer->deserialize($mesRegions,'App\Entity\Region[]', 'json');

        // Je récupère la liste des départements
        if($codeRegion == null || $codeRegion == "Toutes"){
            $mesDeps = file_get_contents('https://geo.api.gouv.fr/departements');
        } else {
            $mesDeps = file_get_contents('https://geo.api.gouv.fr/regions/'.$codeRegion.'/departements');
        }

        // Décodage du format Json en tableau
        $mesDeps = $serializer->decode($mesDeps,'json');

        return $this->render('api/ListeDepRegion.html.twig',[
            "mesRegions" => $mesRegions, 
            "mesDeps"    => $mesDeps
        ]);
    }
}
