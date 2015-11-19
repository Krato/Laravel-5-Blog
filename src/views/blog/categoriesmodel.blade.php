<?php echo '<?php' ?>
 namespace {{$app_name}}{{$blog_path}}\Models;

use Illuminate\Database\Eloquent\Model;
use Vinkla\Translator\Translatable;
use Vinkla\Translator\Contracts\Translatable as TranslatableContract;

class {{ ucfirst($categories) }} extends Model implements TranslatableContract{

    use Translatable;

    protected $table = '{{ $categories }}';

    protected $fillable = ['slug', 'name'];

    protected $translator = '{{$app_name}}{{$blog_path}}\Models\{{ ucfirst($categories) }}Translation';

    protected $translatedAttributes = ['slug', 'name'];

    /**
	 * Returns the Slug
	 * @return text
	 */
	public function getSlug()
	{
		return $this->slug;
	}

	/**
	 * Returns the Name
	 * @return text
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Belongs to most posts
	 * @return Eloquent
	 */
	public function post()
    {
        return $this->belongsToMany('{{$app_name}}{{$blog_path}}\Models\{{ ucfirst($postsTable) }}', '{{ $pivotCategoriesTable }}', '{{ $categories }}_id', '{{ $postsTable }}_id');
    }

}
