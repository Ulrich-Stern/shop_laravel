<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Menu\CreateFormRequest;
use App\Http\Services\Menu\MenuService;
use App\Models\Menu;

class MenuController extends Controller
{

    protected $menuService;

    public function __construct(MenuService $menuService) {
        $this->menuService = $menuService;
    }

    public function create() {
        return view('admin/menus/add', [
            'title' => 'Thêm danh mục mới',
            'menus' => $this->menuService->getParent()
        ]);
    }

    public function store(CreateFormRequest $request) {
        $this->menuService->create($request);

        return redirect()->back();
    } 

    public function index() {

        return view('admin/menus/list', [
            'title' => 'Danh sách danh mục',
            'menus' => $this->menuService->getAll()
        ]);
    }

    public function show(Menu $menu) {
        return view('admin/menus/edit', [
            'title' => 'Chỉnh sửa danh mục:' . $menu->name,
            'menu' => $menu,
            'menus' => $this->menuService->getParent()
        ]);
    }

    public function update(Menu $menu, CreateFormRequest $request) {
        $this->menuService->update($request, $menu);

        return redirect('/admin/menus/list');
    }

    public function destroy(Request $request): \Illuminate\Http\JsonResponse {
        $result = $this->menuService->destroy($request);

        if ($result) {
            return response()->json([
                'error' => false,
                'message' => 'Xóa danh mục thành công'
            ]);
        }

        return response()->json([
            'error' => true
        ]);
    }
}
