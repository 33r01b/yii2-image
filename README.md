## Installing / Getting started

0. add to composer.json & update composer
```json
    "require": {
        "zeroonebeatz/yii2-image": "dev-master",
    },
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/zeroonebeatz/yii2-image.git"
        }
    ]
```

1. Add to model file attribute for upload & set rules
```php
/**
 * This is the model class for table "your_model".
 *
 * @property int $id
 * @property string $image
 */

class Model extends \yii\db\ActiveRecord
{
    public $imageFile;

    public function rules()
    {
        return [
            ['image', 'string', 'max' => 255],
            ['imageFile', 'image']
        ];
    }

    public static function tableName()
    {
        return 'table_name';
    }
}
```

2. create 'web/uploads' folder //TODO delete this step

3. Upload
```php
$model = Model::findOne(1);
$image = new Image($model);
$image->upload('image', 'imageFile');
$model->save();
```
Example:
```php
public function actionCreate()
{
    $model = new Model();

    if ($model->load(Yii::$app->request->post())) {

        $image = new Image($model); // create Image object
        $image->upload('image', 'imageFile'); // 'image' - is a name of image attribute on table, 'imageFile' - is a uploded file field name

        if ($model->save()){
            return $this->redirect(['index']);
        }
    }

    return $this->render('create', [
        'model' => $model,
    ]);
}
```

4. Delete
```php
$image = new Image(Model::findOne(1));
$image->delete('image');
```
Example:
```php
public function actionDeleteImage($id)
{
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    $image = new Image($this->findModel($id));
    return ['deleted' => $image->delete('image')];
}
```