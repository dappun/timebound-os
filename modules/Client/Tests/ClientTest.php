<?php namespace TB\Client\Tests;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use TestCase;

class ClientTest extends TestCase
{
    private $url = [
        'index' => '/clients',
        'create' => '/clients/create'
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
            ->see('Create New Client')
            ->type('Test client name', 'name')
            ->press('Save')
            ->seePageIs($this->url['index'])
            ->see('Test client name');

        $this->assertResponseStatus(200);
    }

    public function testAddNewError()
    {
        $user = \TB\User\Entities\User::find('1');

        $this->actingAs($user)
            ->visit($this->url['create'])
            ->see('Create New Client')
            ->press('Save')
            ->seePageIs($this->url['create'])
            ->see('The name field is required.');
    }
}
