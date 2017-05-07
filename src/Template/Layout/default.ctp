<?php
/**
 *
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

/* Define baseURL */
use Cake\Routing\Router;
use Cake\Utility\Inflector;
$baseURL = Router::url('/',true);



?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset(); ?>
    <title>
        <?= $this->fetch('title'); ?>
        phpMyAdmin - Error Reporting Server
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?= $this->Html->meta('icon'); ?>

    <?php foreach ($css_files as $css_file): ?>
        <?= $this->Html->css($css_file); ?>
    <?php endforeach;?>

    <?php foreach ($js_files as $js_file): ?>
        <?= $this->Html->script($js_file); ?>
    <?php endforeach;?>

    <?php
        echo $this->fetch('meta');
        echo $this->fetch('css');
        echo $this->fetch('script');

        // set up js global variable for notifications count
        echo $this->Html->scriptBlock(
            'var notifications_count = ' . $notif_count . ';',
            array('inline' => true)
        );
    ?>
</head>
<body>
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <a class="navbar-header navbar-brand" href="<?php echo $baseURL; ?>">
                phpMyAdmin
            </a>
            <ul class="nav navbar-nav">
                <?php
                    $controllers = array('reports', 'stats', 'notifications');
                    foreach ($controllers as $controller) {
                        $class = '';
                        if ($current_controller === $controller) {
                            $class = 'active';
                        }
                        echo "<li class='$class' id='nav_".$controller."'><a href='".$baseURL.$controller."'>";
                        echo Inflector::humanize($controller);
                        echo "</a></li>";
                    }
                ?>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <?php if ($developer_signed_in): ?>
                    <li>
                        <p class="navbar-text">
                            Hello, <?php echo $current_developer["full_name"]; ?>
                        </p>
                    </li>
                    <li>
                        <a href="<?php echo $baseURL.'developers/logout'; ?>">
                            Logout
                        </a>
                    </li>
                <?php else: ?>
                    <li>
                        <a href="<?php echo $baseURL.'developers/login'; ?>">
                            Login with Github
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <div id="container">
        <div id="header">
        </div>
        <div id="content" class="container">
            <?php echo $this->Flash->render(); ?>

            <?php echo $this->fetch('content'); ?>
        </div>
        <div id="footer">
        </div>
    </div>
</body>
</html>
