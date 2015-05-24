<?php
namespace TypiCMS\Modules\Pages\Composers;

use Illuminate\Contracts\View\View;
use Maatwebsite\Sidebar\SidebarGroup;
use Maatwebsite\Sidebar\SidebarItem;
use TypiCMS\Modules\Core\Composers\BaseSidebarViewComposer;

class SidebarViewComposer extends BaseSidebarViewComposer
{
    public function compose(View $view)
    {
        $view->sidebar->group(trans('global.menus.content'), function (SidebarGroup $group) {
            $group->id = 'content';
            $group->weight = 30;
            $group->addItem(trans('pages::global.name'), function (SidebarItem $item) {
                $item->icon = config('typicms.pages.sidebar.icon', 'icon fa fa-fw fa-file');
                $item->weight = config('typicms.pages.sidebar.weight');
                $item->route('admin.pages.index');
                $item->append('admin.pages.create');
                $item->authorize(
                    $this->user->hasAccess('pages.index')
                );
            });
        });
    }
}
