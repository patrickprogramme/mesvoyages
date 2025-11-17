<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Description of VoyagesControllerTest
 *
 * @author Patrick
 */
class VoyagesControllerTest extends WebTestCase {
    
    public function testAccesPage() {
        $client = static::createClient();
        $client->request('GET', '/voyages');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
    
    public function testContenuPage() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/voyages');
        $this->assertSelectorTextContains('h1', 'Mes voyages');
        $this->assertSelectorTextContains('th', 'Ville');
        $this->assertCount(4, $crawler->filter('th'));
        $this->assertSelectorTextContains('td', 'Gorgoroth');
    }
    
    public function testLinkVille(){
        $client = static::createClient();
        $client->request('GET', '/voyages');
        $client->clickLink('Gorgoroth'); // simule un clic sur le lien
        // récupère le réponse de cette nouvelle requête
        $response = $client->getResponse();
        // Affiche le contenu de la requête HTTP simulée par le client (pour debug) :
        // dd($client->getRequest());
        // le lien mène quelque part ?
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        // récupère la route a été effectivement appelée par le clic
        $uri = $client->getRequest()->server->get('REQUEST_URI');
        // le lien amène à la bonne page ?
        $this->assertEquals('/voyages/voyage/301', $uri);
    }
    
    public function testFiltreVille() {
        $client = static::createClient();
        $client->request('GET', '/voyages');
        // simulation de la soumission du formulaire
        $crawler = $client->submitForm('filtrer', [
            'recherche' => 'Gorgoroth'
        ]);
        // vérifie le nombre de lignes obtenues
        // rappelle toi les selectors avec BeautifulSoup dans Python
        $this->assertSelectorCount(1, '#voyages-body tr');
        // vérifie si la ville correspond à la recherche
        $this->assertSelectorTextContains('tbody tr td:first-child', 'Gorgoroth');
        
    }
}
