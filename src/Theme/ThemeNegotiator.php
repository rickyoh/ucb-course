<?php
/**
 * @file
 * Contains \Drupal\ucb_course\Theme\ThemeNegotiator
 */
namespace Drupal\ucb_course\Theme;

use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Theme\ThemeNegotiatorInterface;

class ThemeNegotiator implements ThemeNegotiatorInterface {

    /**
     * @param RouteMatchInterface $route_match
     * @return bool
     */
    public function applies(RouteMatchInterface $route_match)
    {
        return $this->negotiateRoute($route_match) ? true : false;
    }
    /**
     * @param RouteMatchInterface $route_match
     * @return null|string
     */
    public function determineActiveTheme(RouteMatchInterface $route_match)
    {
        return $this->negotiateRoute($route_match) ?: null;
    }
    /**
     * Function that does all of the work in selecting a theme
     * @param RouteMatchInterface $route_match
     * @return bool|string
     */
    private function negotiateRoute(RouteMatchInterface $route_match)
    {
        if ($route_match->getRouteName() == 'entity.ucb_class_entity.canonical')
        {
            return \Drupal::configFactory()->get('system.theme')->get('default');
        }
    }
}