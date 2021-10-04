<?php
Yii::setAlias('@common', dirname(__DIR__));
Yii::setAlias('@frontend', dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('@shortner', dirname(dirname(__DIR__)) . '/shortner');
Yii::setAlias('@backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('@partner', dirname(dirname(__DIR__)) . '/partner');
Yii::setAlias('@console', dirname(dirname(__DIR__)) . '/console');
Yii::setAlias('@api', dirname(dirname(__DIR__)) . '/api');
Yii::setAlias('@agent', dirname(dirname(__DIR__)) . '/agent');
Yii::setAlias('categoty-image','https://res.cloudinary.com/plugn/image/upload/');

//Image Upload Paths
Yii::setAlias('projectFiles','@backend/web/uploads/project-files');
Yii::setAlias('transferFiles','@backend/web/uploads');


//Image Upload Paths
Yii::setAlias('privateDocuments','@frontend/web');
