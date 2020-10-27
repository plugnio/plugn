<?php
Yii::setAlias('@common', dirname(__DIR__));
Yii::setAlias('@frontend', dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('@shortner', dirname(dirname(__DIR__)) . '/shortner');
Yii::setAlias('@backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('@console', dirname(dirname(__DIR__)) . '/console');
Yii::setAlias('@api', dirname(dirname(__DIR__)) . '/api');

//Image Upload Paths
Yii::setAlias('projectFiles','@backend/web/uploads/project-files');


//Image Upload Paths
Yii::setAlias('privateDocuments','@frontend/web');
