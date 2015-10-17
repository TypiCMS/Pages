<?php


class PagesControllerTest extends TestCase
{
    public function testRoot()
    {
        // $this->call('GET', '/');
        // $this->assertResponseOk();
    }

    public function testAdminIndex()
    {
        $this->call('GET', 'admin/pages');
        $this->assertResponseOk();
    }

    public function testStoreFails()
    {
        $input = [
            '_token'    => csrf_token(),
            'parent_id' => null,
            'fr.title'  => 'test',
            'fr.slug'   => '',
            'fr.body'   => '',
        ];
        $this->call('POST', 'admin/pages', $input);
        $this->assertResponseStatus(302);
        $this->assertSessionHasErrors('fr.slug');
    }

    public function testStoreSuccess()
    {
        $input = [
            '_token'    => csrf_token(),
            'parent_id' => null,
            'fr.title'  => 'test',
            'fr.slug'   => 'test',
            'fr.body'   => '',
        ];
        $this->call('POST', 'admin/pages', $input);
        $this->assertRedirectedToRoute('admin.pages.edit', 4);
    }

    public function testUpdateSuccess()
    {
        $input = [
            '_token'    => csrf_token(),
            'id'        => 1,
            'parent_id' => null,
            'fr.title'  => 'test',
            'fr.slug'   => 'test',
        ];
        $this->call('PUT', 'admin/pages/1', $input);
        $this->assertResponseStatus(302);
        $this->assertRedirectedToRoute('admin.pages.edit', 1);
    }

    public function testUpdateFails()
    {
        $input = [
            '_token'    => csrf_token(),
            'id'        => 1,
            'parent_id' => null,
            'fr.title'  => 'test',
            'fr.slug'   => '',
        ];
        $this->call('PUT', 'admin/pages/1', $input);
        $this->assertResponseStatus(302);
        $this->assertSessionHasErrors('fr.slug');
    }

    public function testStoreSuccessWithRedirectToList()
    {
        $input = [
            '_token'    => csrf_token(),
            'parent_id' => null,
            'fr.title'  => 'test',
            'fr.slug'   => 'test',
            'fr.body'   => '',
            'exit'      => true,
        ];
        $this->call('POST', 'admin/pages', $input);
        $this->assertRedirectedToRoute('admin.pages.index');
    }
}
