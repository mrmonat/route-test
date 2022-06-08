<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Routing\RouteCollection;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoutingTest extends TestCase
{
    use RefreshDatabase;

    public function test_routing_without_defaults()
    {
        $url = new UrlGenerator(
            $routes = new RouteCollection,
            Request::create('http://www.foo.com/')
        );

        $route = new Route(['GET'], '{locale}/posts/{post:slug}', ['as' => 'routable']);
        $routes->add($route);

        $post = Post::create(['title' => 'Test title', 'slug' => 'test-slug']);

        $this->assertSame('/de/posts/test-slug', $url->route('routable', ['locale' => 'de', 'post' => $post], false));
    }

    public function test_routing_with_defaults()
    {
        $url = new UrlGenerator(
            $routes = new RouteCollection,
            Request::create('http://www.foo.com/')
        );
        $url->defaults(['locale' => 'de']);

        $route = new Route(['GET'], '{locale}/posts/{post:slug}', ['as' => 'routable']);
        $routes->add($route);

        $post = Post::create(['title' => 'Test title', 'slug' => 'test-slug']);

        $this->assertSame('/de/posts/test-slug', $url->route('routable', $post, false));
        $this->assertSame('/de/posts/test-slug', $url->route('routable', ['post' => $post], false));
        $this->assertSame('/de/posts/test-slug', $url->route('routable', [$post], false));
    }
}
