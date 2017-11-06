<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AuthoritiesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AuthoritiesTable Test Case
 */
class AuthoritiesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\AuthoritiesTable
     */
    public $Authorities;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.authorities',
        'app.users',
        'app.groups'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Authorities') ? [] : ['className' => AuthoritiesTable::class];
        $this->Authorities = TableRegistry::get('Authorities', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Authorities);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
