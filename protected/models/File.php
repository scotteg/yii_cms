
<?php

/**
 * This is the model class for table "file".
 *
 * The followings are the available columns in table 'file':
 * @property string $id
 * @property string $user_id
 * @property string $name
 * @property string $type
 * @property string $size
 * @property string $description
 * @property string $date_entered
 * @property string $date_updated
 *
 * The followings are the available model relations:
 * @property User $user
 * @property Page[] $pages
 */
class File extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return File the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'file';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
            // From the user or user's actions
            array('name, type, size', 'required'),

            // Description is optional and should be filtered
            array('description', 'default', 'value'=>NULL),
            array('description', 'filter', 'filter'=>'strip_tags'),
            
            array('name', 'length', 'max'=>80),

            array('type', 'validateFileType'),

            // Set date_entered and date_updated to NOW():
            array('date_entered', 'default', 'value'=>new CDbExpression('NOW()'), 'on'=>'insert'),
            array('date_updated', 'default', 'value'=>new CDbExpression('NOW()'), 'on'=>'update'),
            
            /* Auto-generated rules...
			array('user_id, name, type, size, date_entered', 'required'),
			array('user_id, size', 'length', 'max'=>10),
			array('name', 'length', 'max'=>80),
			array('type', 'length', 'max'=>45),
			array('description, date_updated', 'safe'),
            */

			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, name, type, size, description, date_entered, date_updated', 'safe', 'on'=>'search'),
		);
	}
    
    public function validateFileType($attr, $params)
    {
        // Allow PDFs and Word docs:
        $allowed = array('application/pdf', 'application/msword');

        // Make sure this is an allowed type:
        if (!in_array($this->type, $allowed))
        {
            $this->addError('type', 'You can only update PDF or Word docs.');
        }
    }
    
    
    protected function beforeValidate()
    {
        if(empty($this->user_id))
        {
            $this->user_id = Yii::app()->user->id;
        }
        
        return parent::beforeValidate();
    }

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
			'pages' => array(self::MANY_MANY, 'Page', 'page_has_file(file_id, page_id)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => 'Uploaded By',
			'name' => 'File Name',
			'type' => 'File Type',
			'size' => 'File Size',
			'description' => 'Description',
			'date_entered' => 'Date Entered',
			'date_updated' => 'Date Updated',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('size',$this->size,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('date_entered',$this->date_entered,true);
		$criteria->compare('date_updated',$this->date_updated,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
