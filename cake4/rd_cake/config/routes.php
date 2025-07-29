<?php
/**
 * Routes configuration.
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * It's loaded within the context of `Application::routes()` method which
 * receives a `RouteBuilder` instance `$routes` as method argument.
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;

return static function (RouteBuilder $routes) {
    /*
     * The default class to use for all routes
     */
    $routes->setRouteClass(DashedRoute::class);

    $routes->scope('/', function (RouteBuilder $builder) {
        // Activer les extensions .json et .xml
        $builder->setExtensions(['json', 'xml']);

        // Route pour la page d'accueil
        $builder->connect('/', ['controller' => 'Pages', 'action' => 'display', 'home']);

        // Routes pour les autres pages du contrôleur Pages
        $builder->connect('/pages/*', 'Pages::display');

        // Routes pour l'interface utilisateur
        $builder->connect('/status', ['controller' => 'Pages', 'action' => 'status']);
        $builder->connect('/login', ['controller' => 'Pages', 'action' => 'login']);

        // Routes catch-all pour les autres contrôleurs
        $builder->fallbacks();
    });

    // Routes pour l'API proxy sécurisée
    $routes->scope('/api-proxy', function (RouteBuilder $builder) {
        // Activer les extensions .json pour les réponses API
        $builder->setExtensions(['json']);

        // Route pour récupérer les statistiques utilisateur
        $builder->connect('/get-user-stats', 
            ['controller' => 'ApiProxy', 'action' => 'getUserStats'],
            ['_method' => ['GET', 'POST']]
        );

        // Route pour la déconnexion
        $builder->connect('/logout', 
            ['controller' => 'ApiProxy', 'action' => 'logout'],
            ['_method' => 'POST']
        );
    });
};