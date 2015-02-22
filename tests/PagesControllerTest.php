<?php
use TypiCMS\Modules\Pages\Models\Page;

class PagesControllerTest extends TestCase
{

    public function testRoot()
    {
        $this->get('/');
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testAdminIndex()
    {
        $response = $this->call('GET', 'admin/pages');
    }

    public function testStoreFails()
    {
        $input = array('fr.title' => 'test', 'fr.slug' => '');
        $this->call('POST', 'admin/pages', $input);
        $this->assertRedirectedToRoute('admin.pages.create');
        $this->assertSessionHasErrors('fr.slug');
    }

    public function testStoreSuccess()
    {
        $object = new Page;
        $object->id = 1;
        Page::shouldReceive('create')->once()->andReturn($object);
        $input = array('fr.title' => 'test', 'fr.slug' => 'test');
        $this->call('POST', 'admin/pages', $input);
        $this->assertRedirectedToRoute('admin.pages.edit', array('id' => 1));
    }

    public function testUpdateSuccess()
    {
        $object = new Page;
        $object->id = 1;
        $input = array('id' => 1, 'fr.title' => 'test', 'fr.slug' => '');
        $this->call('PATCH', 'admin/pages/1', $input);
        $this->assertRedirectedToRoute('admin.pages.edit', array('id' => 1));
    }

    public function testUpdateFails()
    {
        $object = new Page;
        $object->id = 1;
        $input = array('id' => 1, 'fr.title' => 'test', 'fr.slug' => '');
        $this->call('PATCH', 'admin/pages/1', $input);
        $this->assertRedirectedToRoute('admin.pages.edit', array('id' => 1));
        $this->assertSessionHasErrors('fr.slug');
    }

    public function testStoreSuccessWithRedirectToList()
    {
        $object = new Page;
        $object->id = 1;
        Page::shouldReceive('create')->once()->andReturn($object);
        $input = array('fr.title' => 'test', 'fr.slug' => 'test', 'exit' => true);
        $this->call('POST', 'admin/pages', $input);
        $this->assertRedirectedToRoute('admin.pages.index');
    }

}
