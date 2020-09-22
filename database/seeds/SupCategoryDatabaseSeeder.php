<?php

use Illuminate\Database\Seeder;

class SupCategoryDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Category::class, 10)->create([
            'parent_id' => $this->getRandomParentId(),
        ]);
    }
    private function getRandomParentId()
    {
       $parent_id =  \App\Models\Category::inRandomOrder()->first();
       return $parent_id;
    }
}
