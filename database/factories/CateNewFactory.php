<?php

namespace Database\Factories;

use App\Models\CateNew;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CateNew>
 */
class CateNewFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CateNew::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->unique()->words(2, true);
        
        return [
            'uuid' => Str::uuid(),
            'name_vn' => $name,
            'name_en' => $this->faker->words(2, true),
            'slug' => Str::slug($name),
            'keywords' => $this->faker->words(3, true),
            'description' => $this->faker->sentence(),
            'image' => null,
            'status' => $this->faker->randomElement([0, 1]),
            'home' => $this->faker->randomElement([0, 1]),
            'stt' => $this->faker->numberBetween(1, 100),
            'parent_id' => 0,
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }

    /**
     * Indicate that the category is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 1,
        ]);
    }

    /**
     * Indicate that the category is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 0,
        ]);
    }

    /**
     * Indicate that the category is featured on homepage.
     */
    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'home' => 1,
        ]);
    }

    /**
     * Indicate that the category is a child category.
     */
    public function child(int $parentId = 1): static
    {
        return $this->state(fn (array $attributes) => [
            'parent_id' => $parentId,
        ]);
    }
}
