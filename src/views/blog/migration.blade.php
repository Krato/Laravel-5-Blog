<?php echo '<?php' ?>

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BlogSetupTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('{{ $postsTable }}', function(Blueprint $table) {
            $table->increments('id');
            $table->string('featured_image')->nullable();
            $table->date('publish_date');
            $table->boolean('state')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('{{ $postsTable }}_translations', function(Blueprint $table) {
            $table->increments('id');
            $table->string('slug')->unique();
            $table->string('title');
            $table->longText('content');
            $table->integer('{{ $postsTable }}_id')->unsigned()->index();
            $table->foreign('{{ $postsTable }}_id')->references('id')->on('{{ $postsTable }}')->onDelete('cascade');
            $table->integer('locale_id')->unsigned()->index();
            $table->foreign('locale_id')->references('id')->on('locales')->onDelete('cascade');
            $table->unique(['{{ $postsTable }}_id', 'locale_id']);
        });

        Schema::create('{{ $userPostsTable }}', function(Blueprint $table) {
            $table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('{{ $usersTable }}')->onDelete('cascade');
            $table->integer('{{ $postsTable }}_id')->unsigned()->index();
            $table->foreign('{{ $postsTable }}_id')->references('id')->on('{{ $postsTable }}')->onDelete('cascade');
        });

        Schema::create('{{ $commentsTable }}', function(Blueprint $table) {
            $table->increments('id');
            $table->text('content');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('{{ $usersTable }}');
            $table->integer('{{ $postsTable }}_id')->unsigned();
            $table->foreign('{{ $postsTable }}_id')->references('id')->on('{{ $postsTable }}');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('{{ $tagsTable }}', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::create('{{ $pivotTagsTable }}', function(Blueprint $table) {
            $table->integer('{{ $postsTable }}_id')->unsigned()->index();
            $table->foreign('{{ $postsTable }}_id')->references('id')->on('{{ $postsTable }}')->onDelete('cascade');
            $table->integer('{{ $tagsTable }}_id')->unsigned()->index();
            $table->foreign('{{ $tagsTable }}_id')->references('id')->on('{{ $tagsTable }}')->onDelete('cascade');
        });

        Schema::create('{{ $categories }}', function(Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
        });

        Schema::create('{{ $categories }}_translations', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug')->unique();
            $table->integer('{{ $categories }}_id')->unsigned()->index();
            $table->foreign('{{ $categories }}_id')->references('id')->on('{{ $categories }}')->onDelete('cascade');
            $table->integer('locale_id')->unsigned()->index();
            $table->foreign('locale_id')->references('id')->on('locales')->onDelete('cascade');
            $table->unique(['{{ $categories }}_id', 'locale_id']);
        });

        Schema::create('{{ $pivotCategoriesTable }}', function(Blueprint $table) {
            $table->integer('{{ $postsTable }}_id')->unsigned()->index();
            $table->foreign('{{ $postsTable }}_id')->references('id')->on('{{ $postsTable }}')->onDelete('cascade');
            $table->integer('{{ $categories }}_id')->unsigned()->index();
            $table->foreign('{{ $categories }}_id')->references('id')->on('{{ $categories }}')->onDelete('cascade');
        });

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{		
		Schema::drop('{{ $pivotCategoriesTable }}');
        Schema::drop('{{ $categories }}_translations');
        Schema::drop('{{ $categories }}');
        Schema::drop('{{ $pivotTagsTable }}');
        Schema::drop('{{ $tagsTable }}');
        Schema::drop('{{ $userPostsTable }}');
        Schema::drop('{{ $commentsTable }}');
        Schema::drop('{{ $postsTable }}_translations');
        Schema::drop('{{ $postsTable }}');

	}

}
