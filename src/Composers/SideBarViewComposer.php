<?php
namespace TypiCMS\Modules\Pages\Composers;

use Illuminate\View\View;

class SidebarViewComposer
{
    public function compose(View $view)
    {
        $view->menus['content']->put('pages', [
            'weight' => config('typicms.pages.sidebar.weight'),
            'request' => $view->prefix . '/pages*',
            'route' => 'admin.pages.index',
            'icon-class' => 'icon fa fa-fw fa-file',
            'title' => 'Pages',
        ]);
    }
}
