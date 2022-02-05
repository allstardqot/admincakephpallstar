<!DOCTYPE html>
<html lang="en-gb">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php 
        $controller =   $this->request->getParam('controller');
        $action     =   $this->request->getParam('action');

        if($controller == 'Home' && $action == 'index'){
            echo '<title>LFG DRAFT- Make Your Team & Play Online Fantasy Sports | Win Cash Daily</title>';
            echo '<meta name="description" content="LFG DRAFT is the most thrilling online fantasy gaming platform in India. Play fantasy Cricket & fantasy Football and win cash prizes! Download the app and join contests." />';
    
        }else if ($action =='page'){		
            
            echo '<title>About Us - LFG DRAFT</title>';
            echo '<meta name="description" content="We are an authentic and thrilling online fantasy sports platform in India that serves fantasy games such as online fantasy cricket and football for ardent sports lovers." />';
            
        } else {

            echo '<title>LFG DRAFT- Make Your Team & Play Online Fantasy Sports | Win Cash Daily</title>';
            echo '<meta name="description" content="LFG DRAFT is the most thrilling online fantasy gaming platform in India. Play fantasy Cricket & fantasy Football and win cash prizes! Download the app and join contests." />';
            
        }
    ?>
    

    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet" type="text/css">
    <link href="<?php echo SITE_URL; ?>assets/images/favicon.png" rel="shortcut icon" type="image/vnd.microsoft.icon">
    <link href="<?php echo SITE_URL; ?>assets/css/akslider.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo SITE_URL; ?>assets/css/donate.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo SITE_URL; ?>assets/css/theme.css" rel="stylesheet" type="text/css" />

   

    
</head>

<body class="tm-isblog">

    <div class="preloader">
        <div class="loader"></div>
    </div>


    <div class="over-wrap">
        <?php echo $this->Flash->render() ?>
        <?php 
            if(!isset($app) || !$app || $app==2){
                echo $this->element('header'); 
            }
        ?>
        <?php echo $this->fetch('content'); ?>

        <div class="bottom-wrapper">
            
            
            <?php 
                if(!isset($app) || !$app || $app==2){
                    echo $this->element('footer'); 
                }
            ?>

        </div>
    </div>

</body>

</html>