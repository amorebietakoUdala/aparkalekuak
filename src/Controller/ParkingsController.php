<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;


class ParkingsController extends AbstractController
{
    private $client = null;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    #[Route('/{_locale}/parkings', name: 'app_parkings')]
    public function index(): Response
    {
        $response = $this->client->request(
            'GET',
            $this->getParameter('apiURL'),
        );

        $parkings = $response->toArray();

        return $this->render("parkings/table.html.twig", [
            'parkings' => $parkings,
            'googleMapsKey' => $this->getParameter('googleMapsKey')
        ]);
    }

    #[Route('/{_locale}/parkings/map', name: 'app_parkings_map')]
    public function map(): Response
    {
        $response = $this->client->request(
            'GET',
            $this->getParameter('apiURL'),
        );

        $parkings = $response->toArray();

        return $this->render("parkings/map.html.twig", [
            'parkings' => $parkings,
            'googleMapsKey' => $this->getParameter('googleMapsKey')
        ]);
    }

    #[Route('/{_locale}/parkings/map/source.kml', name: 'app_parkings_map_source')]
    public function source(): Response
    {
        $response = $this->client->request(
            'GET',
            $this->getParameter('apiURL'),
        );
        $parkings = $response->toArray();
        foreach ($parkings as $parking ) {
            $parkingsWithCoordinates[] = array_merge($this->getCoordinates($parking['nombre']), $parking); 
        }
        $response = new Response();
        $response->headers->set('Content-Type', 'application/vnd.google-earth.kml+xml');

        return  $this->render("parkings/parkingsLayer.kml.twig", [
            'parkings' => $parkingsWithCoordinates,
        ],$response);
    }

    private function getCoordinates($parking) {
        $coordinates = [
            'Betarragane' => [ 'lat'=>'43.22072','lon'=>'-2.73078' ],
            'Elizondo' => [ 'lat'=>'43.21693','lon'=>'-2.73557' ],
            'Ibaizabal' => [ 'lat'=>'43.21962','lon'=>'-2.73480' ],
            'Ixer' => [ 'lat'=>'43.22223','lon'=>'-2.73462' ],
            'Nafarroa' => [ 'lat'=>'43.21595','lon'=>'-2.73008' ],
            'Zubiondo' => [ 'lat'=>'43.21859','lon'=>'-2.73337' ],
        ];
        return $coordinates[$parking];
    }
}
