<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Contact;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contact>
 */
class ContactFactory extends Factory
{

    protected $model = Contact::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

  
    
    public function definition(): array
    {
        $source = storage_path('app/public/defaults/default.jpeg');

        // create profile image
            $filename = Str::random(10) . '.jpg';
            $destination = storage_path('app/public/Profile_image/' . $filename);

            
            if (!File::exists(dirname($destination))) {
                File::makeDirectory(dirname($destination), 0755, true);
            }

            File::copy($source, $destination);
        // create profile image end

        // create doc
        $filename1 = Str::random(10) . '.jpg';
        $destination1 = storage_path('app/public/Doc/' . $filename1);

        
        if (!File::exists(dirname($destination1))) {
            File::makeDirectory(dirname($destination1), 0755, true);
        }

        File::copy($source, $destination1);
    // create doc  end



        return [
            'name' =>  $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'Phone' =>$this->faker->numerify('##########'),
            'gender' =>$this->faker->numberBetween(1,3),
            'profile_image' =>'Profile_image/' . $filename,
            'doc' => 'Doc/' . $filename1,
        ];
    }
}
