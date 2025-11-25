<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\CategoryService;
use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Http\Requests\Admin\UpdateCategoryRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CategoryController extends Controller
{
    // Menggunakan AuthorizesRequests yang diwarisi dari Controller induk (untuk otorisasi)
    use AuthorizesRequests; 

    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
        // Middleware sudah di route group (auth:admin)
    }

    /**
     * Tampilkan daftar semua kategori.
     */
    public function index()
    {
        // UBAH: Mengambil semua kategori dengan Paginasi.
        // Paginasi kategori bertingkat (hierarki) akan sedikit terganggu, 
        // namun ini adalah cara termudah untuk memberikan kontrol navigasi dasar.
        $categories = Category::with('parent')
                        ->orderBy('parent_id') // Urutkan berdasarkan parent
                        ->orderBy('name')
                        ->paginate(10); // Paginasi, 15 item per halaman
        
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Tampilkan form untuk membuat kategori baru.
     */
    public function create()
    {
        // Mengambil semua kategori dalam format hierarki untuk dropdown
        $parentCategories = $this->categoryService->getCategoryHierarchy();
        return view('admin.categories.create', compact('parentCategories'));
    }

    /**
     * Simpan kategori baru.
     */
    public function store(StoreCategoryRequest $request)
    {
        // Data sudah divalidasi dan aman
        $this->categoryService->createCategory($request->validated());
        
        return Redirect::route('admin.categories.index')->with('success', 'Kategori baru berhasil ditambahkan.');
    }

    /**
     * Tampilkan form untuk mengedit kategori.
     */
    public function edit(Category $category)
    {
        // Mengambil semua kategori, mengecualikan kategori yang sedang diedit (mencegah loop)
        $parentCategories = $this->categoryService->getCategoryHierarchy(
            null, 
            null, 
            0,
            $category->id 
        );

        return view('admin.categories.edit', compact('category', 'parentCategories'));
    }

    /**
     * Perbarui kategori yang ada.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $this->categoryService->updateCategory($category, $request->validated());

        return Redirect::route('admin.categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Hapus kategori (Soft Delete).
     */
    public function destroy(Category $category)
    {
        $this->categoryService->deleteCategory($category);
        
        return Redirect::route('admin.categories.index')->with('success', 'Kategori berhasil dihapus (soft delete).');
    }
}