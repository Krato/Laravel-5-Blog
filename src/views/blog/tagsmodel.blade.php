<?php echo '<?php' ?>
 namespace {{$app_name}}{{$blog_path}}\Models;

use Illuminate\Database\Eloquent\Model;

class {{ ucfirst($tagsTable) }} extends Model  {

    protected $table = '{{ $tagsTable }}';

    protected $fillable = ['name'];

	/**
	 * Belongs to most posts
	 * @return Eloquent
	 */
	public function post()
    {
        return $this->belongsToMany('{{$app_name}}{{$blog_path}}\Models\{{ ucfirst($postsTable) }}', '{{ $pivotTagsTable }}', '{{ $postsTable }}_id', '{{ $tagsTable }}_id');
    }
}
