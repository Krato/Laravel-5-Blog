<?php echo '<?php' ?>
 namespace {{$app_name}}{{$blog_path}}\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class {{ ucfirst($tagsTable) }} extends Model implements SluggableInterface {

	use SluggableTrait;

    protected $table = '{{ $tagsTable }}';

    protected $fillable = ['name'];

    protected $sluggable = array(
        'build_from' => 'name',
        'save_to'    => 'slug',
    );

	/**
	 * Belongs to most posts
	 * @return Eloquent
	 */
	public function post()
    {
        return $this->belongsToMany('{{$app_name}}{{$blog_path}}\Models\{{ ucfirst($postsTable) }}', '{{ $pivotTagsTable }}', '{{ $postsTable }}_id', '{{ $tagsTable }}_id');
    }
}
