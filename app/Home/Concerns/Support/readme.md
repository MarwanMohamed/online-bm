#Imageable

```
this trait is used to make the model able to upload images easly

for example we have Floor Model then we will make that :-

- first we define a property called $imageAttrs ,
where the key is the column that will contain filename in database ,
and the value is the path to public directory

public $imageAttrs = [
     'picture' => 'images/floors'
];

-then we can add a mutator like this :- 
which will handle the upload logic and remove old files for that model,
when uploadin image

public function setPictureAttribute($value)
{
   $this->attributes['picture'] = $this->upload('picture',$value);
}

- finally , if we want to get the image url , we can make this :-

$floor = Floor::find(1);

// for example will return http://localhost/imageName.png
$floor->asset('image');

// for example will return /var/ww/html/Home/public/imageName.png
$floor->public_path('image');
```