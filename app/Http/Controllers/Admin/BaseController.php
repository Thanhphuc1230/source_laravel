<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use App\Services\ImageService;
use App\Services\DataRemovalService;
use App\Services\StatusManagementService;
use App\Traits\ImageHandlerTrait;
use App\Traits\DataRemovalTrait;
use App\Traits\SlugHandlerTrait;

class BaseController extends Controller
{
    use ImageHandlerTrait, DataRemovalTrait, SlugHandlerTrait;

    protected $website = 'admin';
    protected $view = null;
    protected $module = null;
    public $db;
    protected $imageService;
    protected $dataRemovalService;
    protected $statusManagementService;
    protected $imageFolder;

    public function __construct($module, $imageFolder = null)
    {
        $this->module = $module;
        $this->view = $this->website . ".modules." . $module;
        $this->db = DB::table($module);
        // Inject services
        $this->imageService = app(ImageService::class);
        $this->dataRemovalService = app(DataRemovalService::class);
        $this->statusManagementService = app(StatusManagementService::class);
        $this->imageFolder = $imageFolder;
    }

    public function view_admin (string $page, array $data = []) {
        return view($this->view . "." . $page, $data);
    }

    public function route_admin(string $page, array $params = [], array $flash = [], $pageParam = null)
    {
        // Add page parameter if it is not null
        if ($pageParam !== null) {
            $params['page'] = $pageParam;
        }

        if (empty($flash)) {
            return redirect()->route($this->website . "." . $this->module . "." . $page, $params);
        }
        return redirect()->route($this->website . "." . $this->module . "." . $page, $params)->with($flash);
    }

    public function destroy(string $uuid)
    {
        return $this->destroyData($uuid);
    }

    public function destroyAll(Request $request)
    {
        return $this->destroyAllData($request);
    }
}
