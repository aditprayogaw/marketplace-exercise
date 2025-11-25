<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class CategoryService
{
    /**
     * Mendapatkan semua kategori, diurutkan, dengan eager loading relasi parent/children.
     * Digunakan untuk daftar utama.
     */
    public function getAllCategories(): Collection
    {
        // Optimasi Query: Menggunakan eager loading untuk children dan parent 
        return Category::with(['parent', 'children'])
                       ->orderBy('parent_id')
                       ->orderBy('name')
                       ->get();
    }
    
    /**
     * Membuat kategori baru dengan slug otomatis.
     */
    public function createCategory(array $data): Category
    {
        $data['slug'] = Str::slug($data['name']);
        $data['parent_id'] = $data['parent_id'] ?? null;

        return Category::create($data);
    }

    /**
     * Memperbarui kategori yang ada.
     */
    public function updateCategory(Category $category, array $data): Category
    {
        // Regenerasi slug jika nama berubah
        if ($category->name !== $data['name']) {
            $data['slug'] = Str::slug($data['name']);
        }
        
        $data['parent_id'] = $data['parent_id'] ?? null;

        $category->update($data);
        return $category;
    }

    /**
     * Menghapus kategori (Soft Deletes).
     */
    public function deleteCategory(Category $category): bool
    {
        // Soft Delete: Menandai sebagai dihapus
        return $category->delete(); 
    }
    
    /**
     * Fungsi bantuan untuk mendapatkan tampilan kategori bertingkat (hierarki) untuk dropdown.
     */
    public function getCategoryHierarchy(Collection $categories = null, $parentId = null, $level = 0, $excludeId = null): array
    {
        $categories = $categories ?? $this->getAllCategories();
        $hierarchy = [];

        foreach ($categories->where('parent_id', $parentId) as $category) {
            
            // Skip kategori yang harus dikecualikan (misal dirinya sendiri saat edit)
            if ($excludeId && $category->id == $excludeId) {
                continue;
            }
            
            $hierarchy[] = [
                'id' => $category->id,
                'name' => str_repeat('- ', $level) . $category->name,
                'category' => $category, 
            ];
            
            // Rekursif: Panggil children
            $children = $this->getCategoryHierarchy($categories, $category->id, $level + 1, $excludeId);
            $hierarchy = array_merge($hierarchy, $children);
        }

        return $hierarchy;
    }
}