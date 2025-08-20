<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'category' => $this->faker->word(),
            'barcode'   => $this->faker->unique()->numerify('##########'),
            'nafdac_number' => $this->faker->bothify('NFDAC-#######'),
            'batch_number' => $this->faker->bothify('BATCH-####') ,
            'expiry_date' => $this->faker->dateTimeBetween('now', '+2 years'),
            'manufacture_date' => $this->faker->dateTimeBetween('-2 years', 'now'),
            'manufacturer' => $this->faker->company(),
            'country_of_origin' => $this->faker->country(),
            'image_url' => $this->faker->imageUrl(640, 480, 'product', true),
            'status' => $this->faker->randomElement(['authentic', 'counterfeit', 'expired', 'pending', 'verified', 'active', 'inactive']),
            'description' => $this->faker->paragraph(),
            'active_ingredient' => $this->faker->word(),
            'dosage_form' => $this->faker->word(),
            'strength' => $this->faker->randomFloat(2, 0, 100) ,
            'packaging' => $this->faker->word(),
            'prescription_required' => $this->faker->boolean(),
            'volume_or_weight' => $this->faker->randomFloat(2, 0, 1000) . ' ' . $this->faker->randomElement(['ml', 'g']),
            'nutritional_info' => [
                'calories' => $this->faker->randomFloat(2, 0, 500),
                'fat' => $this->faker->randomFloat(2, 0, 100) . 'g',
                'carbohydrates' => $this->faker->randomFloat(2, 0, 100) . 'g',
                'protein' => $this->faker->randomFloat(2, 0, 100) . 'g',
            ],
            'flavour' => $this->faker->word(),
            'storage_instructions' => $this->faker->sentence(),
        ];
    }
}