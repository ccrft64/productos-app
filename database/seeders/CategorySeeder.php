<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Alimentos'],
            ['name' => 'Electrónica'],
            ['name' => 'Libros'],
            ['name' => 'Ropa'],
            ['name' => 'Hogar y Jardín'],
            ['name' => 'Juguetes'],
        ];

        foreach ($categories as $categoryData) {
            Category::firstOrCreate(['name' => $categoryData['name']]);
        }

        $this->command->info('¡Categorías creadas correctamente!');
    }
}