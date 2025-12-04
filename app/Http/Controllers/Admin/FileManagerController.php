<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class FileManagerController extends Controller
{
    protected $basePath = 'public/uploads';
    protected $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'pdf', 'doc', 'docx', 'xls', 'xlsx'];
    protected $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
    protected $maxFileSize = 10240; // 10MB in KB

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display file manager interface
     */
    public function index(Request $request)
    {
        $currentPath = $request->get('path', '');
        $files = $this->getFiles($currentPath);

        return view('admin.filemanager.index', compact('files', 'currentPath'));
    }

    /**
     * Upload files
     */
    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'files.*' => 'required|file|max:' . $this->maxFileSize,
            'path' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $uploadedFiles = [];
        $currentPath = $request->get('path', '');

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $result = $this->processFileUpload($file, $currentPath);
                if ($result) {
                    $uploadedFiles[] = $result;
                }
            }
        }

        return response()->json([
            'success' => true,
            'files' => $uploadedFiles,
            'message' => 'Upload thành công ' . count($uploadedFiles) . ' file(s)'
        ]);
    }

    /**
     * Delete file
     */
    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'path' => 'required|string',
            'filename' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $fullPath = $this->basePath . '/' . trim($request->path . '/' . $request->filename, '/');

        if (!Storage::exists($fullPath)) {
            return response()->json([
                'success' => false,
                'message' => 'File không tồn tại'
            ], 404);
        }

        try {
            Storage::delete($fullPath);
            return response()->json([
                'success' => true,
                'message' => 'Xóa file thành công'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi xóa file: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create folder
     */
    public function createFolder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|regex:/^[a-zA-Z0-9_-]+$/',
            'path' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $folderPath = $this->basePath . '/' . trim($request->path . '/' . $request->name, '/');

        try {
            Storage::makeDirectory($folderPath);
            return response()->json([
                'success' => true,
                'message' => 'Tạo thư mục thành công'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi tạo thư mục: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get file info for CKEditor integration
     */
    public function ckeditor(Request $request)
    {
        $files = $this->getImageFiles('');
        $fileUrls = [];

        foreach ($files as $file) {
            if ($file['type'] === 'file') {
                $fileUrls[] = [
                    'url' => Storage::url($file['path']),
                    'name' => $file['name'],
                    'size' => $file['size']
                ];
            }
        }

        return response()->json($fileUrls);
    }

    /**
     * Get files in directory
     */
    private function getFiles($path = '')
    {
        $fullPath = $this->basePath . '/' . trim($path, '/');
        $files = [];

        if (!Storage::exists($fullPath)) {
            return $files;
        }

        $directories = Storage::directories($fullPath);
        $fileItems = Storage::files($fullPath);

        // Add directories
        foreach ($directories as $directory) {
            $dirName = basename($directory);
            $files[] = [
                'type' => 'directory',
                'name' => $dirName,
                'path' => str_replace($this->basePath . '/', '', $directory),
                'size' => 0,
                'modified' => Storage::lastModified($directory),
                'url' => null
            ];
        }

        // Add files
        foreach ($fileItems as $file) {
            $fileName = basename($file);
            $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            if (in_array($extension, $this->allowedExtensions)) {
                $files[] = [
                    'type' => 'file',
                    'name' => $fileName,
                    'path' => str_replace($this->basePath . '/', '', $file),
                    'size' => Storage::size($file),
                    'modified' => Storage::lastModified($file),
                    'url' => Storage::url($file),
                    'extension' => $extension,
                    'is_image' => in_array($extension, $this->imageExtensions)
                ];
            }
        }

        // Sort by type (directories first) then by name
        usort($files, function($a, $b) {
            if ($a['type'] !== $b['type']) {
                return $a['type'] === 'directory' ? -1 : 1;
            }
            return strcmp($a['name'], $b['name']);
        });

        return $files;
    }

    /**
     * Get only image files
     */
    private function getImageFiles($path = '')
    {
        $files = $this->getFiles($path);
        return array_filter($files, function($file) {
            return $file['type'] === 'file' && $file['is_image'];
        });
    }

    /**
     * Process file upload with validation and optimization
     */
    private function processFileUpload($file, $currentPath = '')
    {
        $originalName = $file->getClientOriginalName();
        $extension = strtolower($file->getClientOriginalExtension());

        // Validate extension
        if (!in_array($extension, $this->allowedExtensions)) {
            return false;
        }

        // Generate unique filename
        $filename = Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '_' . time() . '.' . $extension;
        $fullPath = trim($currentPath . '/' . $filename, '/');

        try {
            // Handle image optimization
            if (in_array($extension, $this->imageExtensions) && $extension !== 'svg') {
                $image = Image::make($file);

                // Resize if too large
                if ($image->width() > 1920) {
                    $image->resize(1920, null, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                }

                // Compress quality
                $image->save(storage_path('app/' . $this->basePath . '/' . $fullPath), 85);
            } else {
                // Save non-image files directly
                $file->storeAs($this->basePath . '/' . dirname($fullPath), $filename);
            }

            return [
                'name' => $filename,
                'original_name' => $originalName,
                'path' => $fullPath,
                'url' => Storage::url($this->basePath . '/' . $fullPath),
                'size' => $file->getSize(),
                'extension' => $extension
            ];

        } catch (\Exception $e) {
            \Log::error('File upload error: ' . $e->getMessage());
            return false;
        }
    }
}
