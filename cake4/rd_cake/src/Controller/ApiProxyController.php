<?php
// app/Controller/ApiProxyController.php

namespace App\Controller;

use Cake\Http\Client;
use Cake\Core\Configure;

class ApiProxyController extends AppController 
{
    public function initialize(): void
    {
        parent::initialize();
        // Permettre les requêtes AJAX
        $this->loadComponent('RequestHandler');
    }

    public function getUserStats()
    {
        // Vérifier que c'est une requête POST/GET valide
        if (!$this->request->is(['post', 'get'])) {
            $this->set([
                'success' => false,
                'message' => 'Méthode non autorisée'
            ]);
            $this->viewBuilder()->setOption('serialize', true);
            return;
        }

        // Récupérer les paramètres de la requête
        $username = $this->request->getQuery('username');
        $userType = $this->request->getQuery('type', 'user');
        
        if (empty($username)) {
            $this->set([
                'success' => false,
                'message' => 'Nom d\'utilisateur requis'
            ]);
            $this->viewBuilder()->setOption('serialize', true);
            return;
        }

        // Configuration sécurisée (à mettre dans config/app.php)
        $apiConfig = Configure::read('RadiusApi');
        
        if (empty($apiConfig['url']) || empty($apiConfig['token']) || empty($apiConfig['cloud_id'])) {
            $this->set([
                'success' => false,
                'message' => 'Configuration API manquante'
            ]);
            $this->viewBuilder()->setOption('serialize', true);
            return;
        }

        // Préparer les données pour l'API externe
        $apiData = [
            'username' => $username,
            'from' => date('Y-m-01'), // Premier jour du mois courant
            'to' => date('Y-m-t'),    // Dernier jour du mois courant
            'limit' => 1000,
            'order' => 'Radacct.acctstarttime desc',
            'cloud_id' => $apiConfig['cloud_id'],
            'token' => $apiConfig['token'],
            'page' => 1,
            'type' => $userType
        ];

        // Créer le client HTTP
        $http = new Client();
        
        try {
            // Faire la requête vers l'API externe
            $response = $http->get($apiConfig['url'], $apiData, [
                'headers' => [
                    'Accept' => 'application/json',
                    'X-Api-Key' => $apiConfig['api_key']
                ],
                'timeout' => 30
            ]);

            if ($response->isOk()) {
                $data = $response->getJson();
                
                // Retourner les données nettoyées
                $this->set([
                    'success' => true,
                    'totalIn' => $data['totalIn'] ?? 0,
                    'totalOut' => $data['totalOut'] ?? 0,
                    'totalInOut' => $data['totalInOut'] ?? 0,
                    'items' => $data['items'] ?? []
                ]);
            } else {
                $this->set([
                    'success' => false,
                    'message' => 'Erreur API: ' . $response->getStatusCode()
                ]);
            }
        } catch (\Exception $e) {
            $this->set([
                'success' => false,
                'message' => 'Erreur de connexion à l\'API'
            ]);
        }

        $this->viewBuilder()->setOption('serialize', true);
    }

    public function logout()
    {
        if (!$this->request->is('post')) {
            $this->set([
                'success' => false,
                'message' => 'Méthode non autorisée'
            ]);
            $this->viewBuilder()->setOption('serialize', true);
            return;
        }

        // Configuration pour la déconnexion
        $logoutConfig = Configure::read('RadiusApi.logout');
        
        if (!empty($logoutConfig['url'])) {
            $http = new Client();
            try {
                $http->post($logoutConfig['url']);
            } catch (\Exception $e) {
                // Log de l'erreur mais ne pas bloquer la déconnexion côté client
            }
        }

        $this->set([
            'success' => true,
            'message' => 'Déconnexion réussie'
        ]);
        $this->viewBuilder()->setOption('serialize', true);
    }
}