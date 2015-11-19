<?php echo '<?php' ?>

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class BlogTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('es_ES');

        DB::table('{{ $postsTable }}')->delete();
        DB::table('{{ $postsTable }}_translations')->delete();
        DB::table('{{ $userPostsTable }}')->delete();

        DB::table('{{ $categories }}')->delete();
        DB::table('{{ $categories }}_translations')->delete();
        DB::table('{{ $pivotCategoriesTable }}')->delete();

        DB::table('{{ $tagsTable }}')->delete();
        DB::table('{{ $pivotTagsTable }}')->delete();
        
        DB::unprepared('ALTER TABLE {{ $postsTable }} AUTO_INCREMENT = 1');
        DB::unprepared('ALTER TABLE {{ $postsTable }}_translations AUTO_INCREMENT = 1');

        DB::unprepared('ALTER TABLE {{ $categories }} AUTO_INCREMENT = 1');
        DB::unprepared('ALTER TABLE {{ $categories }}_translations AUTO_INCREMENT = 1');

        DB::unprepared('ALTER TABLE {{ $tagsTable }} AUTO_INCREMENT = 1');
        DB::unprepared('ALTER TABLE {{ $pivotTagsTable }} AUTO_INCREMENT = 1');

        /* CATEGORIES  */
        $category_id = DB::table('{{ $categories }}')->insertGetId(array(
            'created_at'        => $faker->dateTimeThisMonth($max = 'now')
        ));

        $languages = DB::table('locales')->get();
        foreach ($languages as $language)
        {
            DB::table('{{ $categories }}_translations')->insert(array(
                'name'          => 'Demo',
                'slug'          => $faker->word,
                'categories_id' => $category_id,
                'locale_id'     => $language->id
            ));

        };

        /* TAGS */
        $tag_id = DB::table('{{ $tagsTable }}')->insertGetId(array(
            'name'        => 'Patata'
        ));

        $tag_id_2 = DB::table('{{ $tagsTable }}')->insertGetId(array(
            'name'        => 'Ciruela'
        ));

        /* POSTS  */
        for($i = 0; $i < 5; $i++){

            $randArr = array(6,9);
            $random = array_rand($randArr, 1);
            $random = $random[0];

            $post_id = DB::table('{{ $postsTable }}')->insertGetId(array(
                'featured_image'=> 'http://jesusavila.com/wp-content/uploads/que-es-un-blog-y-para-que-sirve.jpg',
                'publish_date'  => $faker->dateTimeThisMonth($max = 'now'),
                'state'         => 1
            ));

            $languages = DB::table('locales')->get();

            foreach ($languages as $language)
            {
                DB::table('{{ $postsTable }}_translations')->insert(array(
                    'slug'          => $faker->word,    
                    'title'       => $faker->sentence(6),
                    'content'       => $faker->paragraph(rand(2,5)),
                    '{{ $postsTable }}_id' => $post_id,
                    'locale_id' => $language->id
                ));
            }

            DB::table('{{ $userPostsTable }}')->insert(array(
                'user_id'=> 1,
                '{{ $postsTable }}_id' => $post_id,                
            ));

            DB::table('{{ $pivotTagsTable }}')->insert(array(
                '{{ $postsTable }}_id' => $post_id,           
                '{{ $tagsTable }}_id' => $tag_id
            ));

            DB::table('{{ $pivotCategoriesTable }}')->insert(array(
                '{{ $postsTable }}_id' => $post_id,           
                '{{ $categories }}_id' => $tag_id
            ));
        }

         //$this->command->info('Accomodation table seeded!');
    }
}


