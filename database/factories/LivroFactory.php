<?php

namespace Database\Factories;

use App\Models\Livro;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class LivroFactory extends Factory
{

    protected $model = Livro::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'titulo' => $this->faker->sentence(3),
            'autores' => "{$this->faker->name}, {$this->faker->name}",
            'editora' => "Editora {$this->faker->word()}",
            'edicao' => $this->faker->randomDigitNotNull(),
            'ano' => $this->faker->numberBetween(1900, 2021),
            'assunto' => $this->faker->word(),
            'quantidade' => $this->faker->numberBetween(1, 10),
        ];
    }
}
