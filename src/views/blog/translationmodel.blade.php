<?php echo '<?php' ?>
 namespace {{$app_name}}{{$blog_path}}\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;


class {{ucfirst($translationTable)}}Translation extends Model implements SluggableInterface{

	use SluggableTrait;

	public $timestamps = false;

    protected $sluggable = array(
        'build_from' => 'name',
        'save_to'    => 'slug',
    );
}
