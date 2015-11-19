<?php echo '<?php' ?>
 namespace {{$app_name}}{{$blog_path}}\Models;

use Illuminate\Database\Eloquent\Model;

class {{ ucfirst($commentsTable) }} extends Model  {

    protected $table = '{{ $commentsTable }}';

    protected $fillable = ['content'];



	/**
	 * Post belongs to User
	 * @return Eloquent
	 */
	public function user()
    {
        return $this->belongsTo('{{$usersModel}}');
    }

	/**
	 * Get the comments for the post
	 * @return Eloquent
	 */
	public function post(){
		return $this->belongsTo('{{$app_name}}{{$blog_path}}\Models\{{ ucfirst($postsTable) }}');
	}

}
