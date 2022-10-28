<?php

namespace Database\Factories;

use App\Models\Pessoa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pessoa>
 */
class PessoaFactory extends Factory
{

    protected $model = Pessoa::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'nome' => $this->faker->name,
            'cpf' => $this->faker->numerify('###.###.###-##'),
            'rg' => $this->faker->randomNumber(8, true),
            'telefone' => $this->faker->numerify('(##) ####-####'),
            'celular' => $this->faker->numerify('(##) #####-####'),
            'email' => $this->faker->unique()->safeEmail,
            'matricula' => $this->faker->randomNumber(8, true),
            'titulacao' => $this->faker->randomElement(['Graduado', 'Especialista', 'Mestre', 'Doutor']),
            'endereco' => $this->faker->address,
            'tipo' => $this->faker->randomElement(['A', 'P']),
        ];
    }
}
