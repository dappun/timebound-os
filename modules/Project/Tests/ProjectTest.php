<?php namespace TB\Project\Tests;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use TestCase;

class ProjectTest extends TestCase
{
    private $url = [
        'index' => '/projects',
        'create' => '/projects/create'
    ];

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testAnonVisit()
    {
        $this->call('get', $this->url['index']);
        $this->assertResponseStatus(302);
        $this->assertRedirectedTo('login');
    }

    public function testAuthTeamUser()
    {
        $user = \TB\User\Entities\User::find('4');

        $this->actingAs($user);
        $this->call('get', $this->url['index']);
        $this->assertResponseStatus(403);
    }

    public function testAuthClientUser()
    {
        $user = \TB\User\Entities\User::find('3');

        $this->actingAs($user);
        $this->call('get', $this->url['index']);
        $this->assertResponseStatus(403);
    }

    public function testAuthProjectManagerUser()
    {
        $user = \TB\User\Entities\User::find('2');

        $this->actingAs($user);
        $this->call('get', $this->url['index']);
        $this->assertResponseStatus(403);
    }

    public function testAuthAdminUser()
    {
        $user = \TB\User\Entities\User::find('1');

        $this->actingAs($user)
            ->visit($this->url['index']);

        $this->assertResponseStatus(200);
    }

    public function testAddNewSuccess()
    {
        $user = \TB\User\Entities\User::find('1');

        $this->actingAs($user)
            ->visit($this->url['create'])
            ->see('Create New Project')
            ->type('Test project name', 'title')
            ->press('Save')
            ->seePageIs($this->url['index'])
            ->see('Test project name');

        $this->assertResponseStatus(200);
    }

    public function testAddNewError()
    {
        $user = \TB\User\Entities\User::find('1');

        $this->actingAs($user)
            ->visit($this->url['create'])
            ->see('Create New Project')
            ->press('Save')
            ->seePageIs($this->url['create'])
            ->see('The title field is required.');
    }
}
